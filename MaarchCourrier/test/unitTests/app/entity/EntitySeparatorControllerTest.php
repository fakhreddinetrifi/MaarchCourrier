<?php

/**
* Copyright Maarch since 2008 under licence GPLv3.
* See LICENCE.txt file at the root folder for more details.
* This file is part of Maarch software.
*
*/

use PHPUnit\Framework\TestCase;

class EntitySeparatorControllerTest extends TestCase
{
    public function testCreate()
    {
        $entityController = new \Entity\controllers\EntitySeparatorController();

        //  CREATE
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'POST']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        $aArgs = [
            'type'      => 'qrcode',
            'entities'  => ['PJS']
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($aArgs, $request);

        $response     = $entityController->create($fullRequest, new \Slim\Http\Response());
        $this->assertSame(200, $response->getStatusCode());

        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertNotEmpty($responseBody);

        $aArgs = [
            'type'      => 'barcode',
            'target'    => 'generic'
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($aArgs, $request);

        $response     = $entityController->create($fullRequest, new \Slim\Http\Response());
        $this->assertSame(200, $response->getStatusCode());

        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertNotEmpty($responseBody);

        // ERRORS
        $aArgs = [
            'type'      => 'barcode',
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($aArgs, $request);

        $response     = $entityController->create($fullRequest, new \Slim\Http\Response());
        $this->assertSame(400, $response->getStatusCode());

        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Body entities is not set or empty', $responseBody['errors']);

        $aArgs = [
            'type'      => 'code',
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($aArgs, $request);

        $response     = $entityController->create($fullRequest, new \Slim\Http\Response());
        $this->assertSame(400, $response->getStatusCode());

        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Body type value must be qrcode or barcode', $responseBody['errors']);

        $fullRequest = \httpRequestCustom::addContentInBody([], $request);

        $response     = $entityController->create($fullRequest, new \Slim\Http\Response());
        $this->assertSame(400, $response->getStatusCode());

        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Body type is not set or empty', $responseBody['errors']);


        $GLOBALS['login'] = 'sstar';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];

        $fullRequest = \httpRequestCustom::addContentInBody([], $request);

        $response     = $entityController->create($fullRequest, new \Slim\Http\Response());
        $this->assertSame(403, $response->getStatusCode());

        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Service forbidden', $responseBody['errors']);

        $GLOBALS['login'] = 'superadmin';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];
    }
}
