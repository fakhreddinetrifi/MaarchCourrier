<?php
/**
* Copyright Maarch since 2008 under licence GPLv3.
* See LICENCE.txt file at the root folder for more details.
* This file is part of Maarch software.

*
* @brief   HomeControllerTest
*
* @author  dev <dev@maarch.org>
* @ingroup core
*/

use PHPUnit\Framework\TestCase;

class HomeControllerTest extends TestCase
{
    private static $id = null;

    public function testGet()
    {
        $GLOBALS['login'] = 'bblier';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];

        $homeController = new \Home\controllers\HomeController();

        $environment = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request = \Slim\Http\Request::createFromEnvironment($environment);

        $response = $homeController->get($request, new \Slim\Http\Response());
        $responseBody = json_decode((string) $response->getBody());
        
        $this->assertNotNull($responseBody->regroupedBaskets);
        $this->assertNotNull($responseBody->assignedBaskets);
        $this->assertNotEmpty($responseBody->homeMessage);

        $GLOBALS['login'] = 'superadmin';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];
    }

    public function testGetLastRessources()
    {
        $GLOBALS['login'] = 'bblier';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];

        $homeController = new \Home\controllers\HomeController();

        $environment = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request = \Slim\Http\Request::createFromEnvironment($environment);

        $response = $homeController->getLastRessources($request, new \Slim\Http\Response());
        $responseBody = json_decode((string) $response->getBody());
        
        $this->assertIsArray($responseBody->lastResources);

        $GLOBALS['login'] = 'superadmin';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];
    }

    public function testGetMaarchParapheurDocuments()
    {
        $GLOBALS['login'] = 'jjane';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];

        $homeController = new \Home\controllers\HomeController();

        $environment = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request = \Slim\Http\Request::createFromEnvironment($environment);

        $response = $homeController->getMaarchParapheurDocuments($request, new \Slim\Http\Response());
        $responseBody = json_decode((string) $response->getBody());
        
        $this->assertIsArray($responseBody->documents);
        foreach ($responseBody->documents as $document) {
            $this->assertIsInt($document->id);
            $this->assertNotEmpty($document->title);
            $this->assertNotEmpty($document->reference);
            $this->assertNotEmpty($document->mode);
            $this->assertIsBool($document->owner);
        }

        $GLOBALS['login'] = 'superadmin';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];

        // ERROR
        $environment = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request = \Slim\Http\Request::createFromEnvironment($environment);

        $response = $homeController->getMaarchParapheurDocuments($request, new \Slim\Http\Response());
        $responseBody = json_decode((string) $response->getBody(), true);
        $this->assertSame('User is not linked to Maarch Parapheur', $responseBody['errors']);
    }
}
