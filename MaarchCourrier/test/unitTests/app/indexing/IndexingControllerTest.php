<?php

/**
* Copyright Maarch since 2008 under licence GPLv3.
* See LICENCE.txt file at the root folder for more details.
* This file is part of Maarch software.
*
*/

use PHPUnit\Framework\TestCase;
use SrcCore\models\DatabaseModel;

class IndexingControllerTest extends TestCase
{
    public function testGetIndexingActions()
    {
        $GLOBALS['login'] = 'bbain';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];

        $indexingController = new \Resource\controllers\IndexingController();

        //  GET
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        $response     = $indexingController->getIndexingActions($request, new \Slim\Http\Response(), ['groupId' => 2]);
        $this->assertSame(200, $response->getStatusCode());

        $responseBody = json_decode((string)$response->getBody());

        $this->assertNotEmpty($responseBody->actions);
        foreach ($responseBody->actions as $action) {
            $this->assertNotEmpty($action->id);
            $this->assertIsInt($action->id);
            $this->assertNotEmpty($action->label);
            $this->assertNotEmpty($action->component);
        }

        //ERROR
        $response = $indexingController->getIndexingActions($request, new \Slim\Http\Response(), ['groupId' => 99999]);
        $this->assertSame(403, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody());
        $this->assertSame('This user is not in this group', $responseBody->errors);

        $response = $indexingController->getIndexingActions($request, new \Slim\Http\Response(), ['groupId' => 'wrong format']);
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody());
        $this->assertSame('Param groupId must be an integer val', $responseBody->errors);

        $GLOBALS['login'] = 'ddur';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];

        $response = $indexingController->getIndexingActions($request, new \Slim\Http\Response(), ['groupId' => 8]);
        $this->assertSame(403, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody());
        $this->assertSame('This group can not index document', $responseBody->errors);

        $GLOBALS['login'] = 'superadmin';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];
    }

    public function testGetIndexingEntities()
    {
        $GLOBALS['login'] = 'bbain';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];

        $indexingController = new \Resource\controllers\IndexingController();

        //  GET
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        $response     = $indexingController->getIndexingEntities($request, new \Slim\Http\Response(), ['groupId' => 2]);
        $this->assertSame(200, $response->getStatusCode());

        $responseBody = json_decode((string)$response->getBody());

        $this->assertNotEmpty($responseBody->entities);
        foreach ($responseBody->entities as $entity) {
            $this->assertNotEmpty($entity->id);
            $this->assertIsInt($entity->id);
            $this->assertNotEmpty($entity->entity_label);
            $this->assertNotEmpty($entity->entity_id);
        }

        //ERROR
        $response = $indexingController->getIndexingEntities($request, new \Slim\Http\Response(), ['groupId' => 99999]);
        $responseBody = json_decode((string)$response->getBody());
        $this->assertSame('This user is not in this group', $responseBody->errors);

        $response = $indexingController->getIndexingEntities($request, new \Slim\Http\Response(), ['groupId' => 'wrong format']);
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody());
        $this->assertSame('Param groupId must be an integer val', $responseBody->errors);

        $GLOBALS['login'] = 'superadmin';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];
    }

    public function testGetProcessLimitDate()
    {
        $GLOBALS['login'] = 'bbain';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];

        $indexingController = new \Resource\controllers\IndexingController();

        //  GET BY DOCTYPE
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        $aArgs = [
            "doctype" => 101
        ];
        $fullRequest = $request->withQueryParams($aArgs);
        $response     = $indexingController->getProcessLimitDate($fullRequest, new \Slim\Http\Response());
        $this->assertSame(200, $response->getStatusCode());

        $responseBody = json_decode((string)$response->getBody());
        $this->assertNotEmpty($responseBody->processLimitDate);

        //  GET BY PRIORITY
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        $priorities = DatabaseModel::select([
            'select'    => ['id'],
            'table'     => ['priorities'],
            'limit'     => 1
        ]);

        $aArgs = [
            "priority" => $priorities[0]['id']
        ];
        $fullRequest = $request->withQueryParams($aArgs);
        $response     = $indexingController->getProcessLimitDate($fullRequest, new \Slim\Http\Response());
        $this->assertSame(200, $response->getStatusCode());

        $responseBody = json_decode((string)$response->getBody());
        $this->assertNotEmpty($responseBody->processLimitDate);

        // ERROR
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        $aArgs = [
            "priority" => "12635"
        ];
        $fullRequest = $request->withQueryParams($aArgs);
        $response     = $indexingController->getProcessLimitDate($fullRequest, new \Slim\Http\Response());

        $responseBody = json_decode((string)$response->getBody());
        $this->assertSame('Priority does not exists', $responseBody->errors);

        $GLOBALS['login'] = 'superadmin';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];
    }

    public function testGetFileInformations()
    {
        $indexingController = new \Resource\controllers\IndexingController();

        //  GET
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        $response     = $indexingController->getFileInformations($request, new \Slim\Http\Response());
        $this->assertSame(200, $response->getStatusCode());

        $responseBody = json_decode((string)$response->getBody());
        $this->assertNotEmpty($responseBody->informations);
        $this->assertNotEmpty($responseBody->informations->maximumSize);
        $this->assertNotEmpty($responseBody->informations->maximumSizeLabel);
        $this->assertNotEmpty($responseBody->informations->allowedFiles);
        foreach ($responseBody->informations->allowedFiles as $value) {
            $this->assertNotEmpty($value->extension);
            $this->assertNotEmpty($value->mimeType);
            $this->assertIsBool($value->canConvert);
        }
    }

    public function testGetPriorityWithProcessLimitDate()
    {
        $GLOBALS['login'] = 'bbain';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];

        $indexingController = new \Resource\controllers\IndexingController();

        // GET
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        $aArgs = [
            "processLimitDate" => 'Fri Dec 16 2044'
        ];
        $fullRequest = $request->withQueryParams($aArgs);
        $response     = $indexingController->getPriorityWithProcessLimitDate($fullRequest, new \Slim\Http\Response());
        $this->assertSame(200, $response->getStatusCode());

        $responseBody = json_decode((string)$response->getBody());
        $this->assertNotEmpty($responseBody->priority);

        // ERROR
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        $response     = $indexingController->getPriorityWithProcessLimitDate($request, new \Slim\Http\Response());

        $responseBody = json_decode((string)$response->getBody());
        $this->assertSame('Query params processLimitDate is empty', $responseBody->errors);

        $GLOBALS['login'] = 'superadmin';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];
    }

    public function testSetAction()
    {
        $GLOBALS['login'] = 'bbain';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];

        $indexingController = new \Resource\controllers\IndexingController();

        // GET
        // ERROR
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'PUT']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        $response     = $indexingController->setAction($request, new \Slim\Http\Response(), []);
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Body resource is empty or not an integer', $responseBody['errors']);


        $body = [
            'resource' => 1
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);
        $response     = $indexingController->setAction($fullRequest, new \Slim\Http\Response(), ['groupId' => 10000 ]);
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody());
        $this->assertSame('Route groupId does not exist', $responseBody->errors);

        $body = [
            'resource' => 1
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);
        $response     = $indexingController->setAction($fullRequest, new \Slim\Http\Response(), ['groupId' => 1 ]);
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody());
        $this->assertSame('Group is not linked to this user', $responseBody->errors);

        $body = [
            'resource' => 1
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);
        $response     = $indexingController->setAction($fullRequest, new \Slim\Http\Response(), ['groupId' => 2, 'actionId' => 2]);
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody());
        $this->assertSame('Action is not linked to this group', $responseBody->errors);

        $body = [
            'resource' => 1
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);
        $response     = $indexingController->setAction($fullRequest, new \Slim\Http\Response(), ['groupId' => 2, 'actionId' => 22]);
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody());
        $this->assertSame('Resource does not exist', $responseBody->errors);

        \Resource\models\ResModel::update([
            'set'   => ['status' => ''],
            'where' => ['res_id = ?'],
            'data'  => [$GLOBALS['resources'][2]]
        ]);

        $body = [
            'resource' => $GLOBALS['resources'][2]
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);
        $response     = $indexingController->setAction($fullRequest, new \Slim\Http\Response(), ['groupId' => 2, 'actionId' => '20']);
        $responseBody = json_decode((string)$response->getBody());
        print_r($responseBody);
        $this->assertSame(204, $response->getStatusCode());

        \Resource\models\ResModel::update([
            'set'   => ['status' => 'NEW'],
            'where' => ['res_id = ?'],
            'data'  => [$GLOBALS['resources'][2]]
        ]);

        $GLOBALS['login'] = 'superadmin';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];
    }
}
