<?php

/**
* Copyright Maarch since 2008 under licence GPLv3.
* See LICENCE.txt file at the root folder for more details.
* This file is part of Maarch software.
*
*/

use PHPUnit\Framework\TestCase;

class LinkControllerTest extends TestCase
{
    private static $firstResourceId = null;
    private static $secondResourceId = null;

    public function testLinkResources()
    {
        $GLOBALS['login'] = 'cchaplin';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];

        $resController = new \Resource\controllers\ResController();

        //  CREATE
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'POST']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        $aArgs = [
            'modelId'           => 1,
            'status'            => 'NEW',
            'confidentiality'   => false,
            'documentDate'      => '2019-01-01 17:18:47',
            'arrivalDate'       => '2019-01-01 17:18:47',
            'processLimitDate'  => '2029-01-01',
            'doctype'           => 102,
            'destination'       => 15,
            'initiator'         => 15,
            'subject'           => 'Lorsque l\'on se cogne la tête contre un pot et que cela sonne creux, ça n\'est pas forcément le pot qui est vide.',
            'typist'            => 19,
            'priority'          => 'poiuytre1357nbvc',
            'senders'           => [['type' => 'contact', 'id' => 1], ['type' => 'user', 'id' => 21], ['type' => 'entity', 'id' => 1]],
        ];

        $fullRequest = \httpRequestCustom::addContentInBody($aArgs, $request);

        $response     = $resController->create($fullRequest, new \Slim\Http\Response());
        $this->assertSame(200, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody());
        self::$firstResourceId = $responseBody->resId;
        $this->assertIsInt(self::$firstResourceId);

        $response     = $resController->create($fullRequest, new \Slim\Http\Response());
        $this->assertSame(200, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody());
        self::$secondResourceId = $responseBody->resId;
        $this->assertIsInt(self::$secondResourceId);

        $GLOBALS['login'] = 'superadmin';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];


        $linkController = new \Resource\controllers\LinkController();

        $args = [
            'linkedResources' => [self::$secondResourceId]
        ];

        $fullRequest = \httpRequestCustom::addContentInBody($args, $request);
        $response     = $linkController->linkResources($fullRequest, new \Slim\Http\Response(), ['resId' => self::$firstResourceId]);
        $this->assertSame(204, $response->getStatusCode());


        // ERRORS
        $args['linkedResources'][] = self::$firstResourceId;
        $fullRequest = \httpRequestCustom::addContentInBody($args, $request);
        $response     = $linkController->linkResources($fullRequest, new \Slim\Http\Response(), ['resId' => self::$firstResourceId]);
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Body linkedResources contains resource', $responseBody['errors']);

        $GLOBALS['login'] = 'cchaplin';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];

        $args['linkedResources'] = [9999999];
        $fullRequest = \httpRequestCustom::addContentInBody($args, $request);
        $response     = $linkController->linkResources($fullRequest, new \Slim\Http\Response(), ['resId' => self::$firstResourceId]);
        $this->assertSame(403, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Service forbidden', $responseBody['errors']);

        $GLOBALS['login'] = 'superadmin';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];

        $args['linkedResources'] = [];
        $fullRequest = \httpRequestCustom::addContentInBody($args, $request);
        $response     = $linkController->linkResources($fullRequest, new \Slim\Http\Response(), ['resId' => self::$firstResourceId]);
        $this->assertSame(403, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Body linkedResources is empty or not an array', $responseBody['errors']);
    }

    public function testGetLinkedResources()
    {
        $linkController = new \Resource\controllers\LinkController();

        //  GET
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        $response     = $linkController->getLinkedResources($request, new \Slim\Http\Response(), ['resId' => self::$firstResourceId]);
        $this->assertSame(200, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);

        $this->assertNotEmpty($responseBody['linkedResources']);
        $this->assertSame(self::$secondResourceId, $responseBody['linkedResources'][0]['resId']);
        $this->assertSame('Lorsque l\'on se cogne la tête contre un pot et que cela sonne creux, ça n\'est pas forcément le pot qui est vide.', $responseBody['linkedResources'][0]['subject']);
        $this->assertNotEmpty($responseBody['linkedResources'][0]['status']);
        $this->assertNotEmpty($responseBody['linkedResources'][0]['destination']);
        $this->assertNotEmpty($responseBody['linkedResources'][0]['destinationLabel']);
        $this->assertIsBool($responseBody['linkedResources'][0]['canConvert']);

        $response     = $linkController->getLinkedResources($request, new \Slim\Http\Response(), ['resId' => self::$secondResourceId]);
        $this->assertSame(200, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);

        $this->assertNotEmpty($responseBody['linkedResources']);
        $this->assertSame(self::$firstResourceId, $responseBody['linkedResources'][0]['resId']);
        $this->assertSame('Lorsque l\'on se cogne la tête contre un pot et que cela sonne creux, ça n\'est pas forcément le pot qui est vide.', $responseBody['linkedResources'][0]['subject']);
        $this->assertNotEmpty($responseBody['linkedResources'][0]['status']);
        $this->assertNotEmpty($responseBody['linkedResources'][0]['destination']);
        $this->assertNotEmpty($responseBody['linkedResources'][0]['destinationLabel']);
        $this->assertIsBool($responseBody['linkedResources'][0]['canConvert']);
    }

    public function testUnlinkResources()
    {
        $linkController = new \Resource\controllers\LinkController();

        //  DELETE
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'DELETE']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        $response     = $linkController->unlinkResources($request, new \Slim\Http\Response(), ['resId' => self::$firstResourceId, 'id' => self::$secondResourceId]);
        $this->assertSame(204, $response->getStatusCode());

        $response     = $linkController->getLinkedResources($request, new \Slim\Http\Response(), ['resId' => self::$firstResourceId]);
        $this->assertSame(200, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);

        $this->assertEmpty($responseBody['linkedResources']);

        $response     = $linkController->getLinkedResources($request, new \Slim\Http\Response(), ['resId' => self::$secondResourceId]);
        $this->assertSame(200, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);

        $this->assertEmpty($responseBody['linkedResources']);

        \SrcCore\models\DatabaseModel::delete([
            'table' => 'res_letterbox',
            'where' => ['res_id in (?)'],
            'data'  => [[self::$firstResourceId, self::$secondResourceId]]
        ]);
    }
}
