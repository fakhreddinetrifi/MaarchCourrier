<?php

/**
* Copyright Maarch since 2008 under licence GPLv3.
* See LICENCE.txt file at the root folder for more details.
* This file is part of Maarch software.
*
*/

use PHPUnit\Framework\TestCase;

class ResourceListControllerTest extends TestCase
{
    public function testGet()
    {
        $listDisplay = \Basket\models\GroupBasketModel::get([
            'select' => ['list_display'],
            'where' => ['basket_id = ?', 'group_id = ?'],
            'data'  => ['MyBasket', 'AGENT']
        ]);
        $listDisplay = json_decode($listDisplay[0]['list_display'], true);
        $listDisplay[] = ['value' => 'getVisaWorkflow', 'cssClasses' => ['align_leftData'], 'icon' => 'fa-list-ol'];
        $listDisplay[] = ['value' => 'getSignatories', 'cssClasses' => ['align_leftData'], 'icon' => 'fa-list-ol'];
        $listDisplay[] = ['value' => 'getParallelOpinionsNumber', 'cssClasses' => ['align_leftData'], 'icon' => 'fa-list-ol'];

        \Basket\models\GroupBasketModel::update([
            'set'   => ['list_display' => json_encode($listDisplay)],
            'where' => ['basket_id = ?', 'group_id = ?'],
            'data'  => ['MyBasket', 'AGENT']
        ]);

        $userInfo = \User\models\UserModel::getByLogin(['login' => 'bbain', 'select' => ['id']]);

        \Entity\models\ListInstanceModel::create([
            'res_id'              => $GLOBALS['resources'][0],
            'sequence'            => 0,
            'item_id'             => $userInfo['id'],
            'item_type'           => 'user_id',
            'item_mode'           => 'dest',
            'added_by_user'       => $GLOBALS['id'],
            'viewed'              => 0,
            'difflist_type'       => 'VISA_CIRCUIT',
            'requested_signature' => true,
        ]);

        $GLOBALS['login'] = 'bbain';
        $GLOBALS['id'] = $userInfo['id'];
        $myBasket = \Basket\models\BasketModel::getByBasketId(['basketId' => 'MyBasket', 'select' => ['id', 'basket_id']]);

        $resListController = new \Resource\controllers\ResourceListController();

        //  GET
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        $aArgs = [
        ];
        $fullRequest = $request->withQueryParams($aArgs);

        $response     = $resListController->get($fullRequest, new \Slim\Http\Response(), ['userId' => 19, 'groupId' => 2, 'basketId' => $myBasket['id']]);
        $responseBody = json_decode((string)$response->getBody());

        $this->assertIsInt($responseBody->count);
        $this->assertNotNull($responseBody->basketLabel);

        //  GET
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        $aArgs = [
            'limit'  => 2,
            'offset' => 1,
            'order'  => 'creation_date DESC'
        ];
        $fullRequest = $request->withQueryParams($aArgs);

        $response     = $resListController->get($fullRequest, new \Slim\Http\Response(), ['userId' => 19, 'groupId' => 2, 'basketId' => $myBasket['id']]);
        $responseBody = json_decode((string)$response->getBody());

        $this->assertSame(2, count($responseBody->resources));

        //  GET
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);
    
        $aArgs = [
                'order'            => 'priority DESC',
                'search'           => '2 Breaking News',
                'priorities'       => 'poiuytre1379nbvc,poiuytre1391nbvc',
                'categories'       => 'incoming',
                'statuses'         => 'COU',
                'entitiesChildren' => 'PJS'
            ];
        $fullRequest = $request->withQueryParams($aArgs);
    
        $response     = $resListController->get($fullRequest, new \Slim\Http\Response(), ['userId' => 19, 'groupId' => 2, 'basketId' => $myBasket['id']]);
        $responseBody = json_decode((string)$response->getBody());
    
        $this->assertGreaterThanOrEqual(1, count($responseBody->resources));
        $this->assertNotNull($responseBody->resources[0]->priorityColor);
        $this->assertNotNull($responseBody->resources[0]->statusImage);
        $this->assertNotNull($responseBody->resources[0]->statusLabel);
        $this->assertIsInt($responseBody->resources[0]->resId);
        $this->assertSame('2 Breaking News : 12345 Superman is alive - PHP unit', $responseBody->resources[0]->subject);

        //  GET
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);
    
        $aArgs = [
                'order'            => 'alt_identifier ASC',
                'search'           => '2 Breaking News',
                'priorities'       => 'poiuytre1379nbvc,poiuytre1391nbvc',
                'categories'       => 'incoming',
                'statuses'         => 'COU',
                'entities'         => 'PJS'
            ];
        $fullRequest = $request->withQueryParams($aArgs);
    
        $response     = $resListController->get($fullRequest, new \Slim\Http\Response(), ['userId' => 19, 'groupId' => 2, 'basketId' => $myBasket['id']]);
        $responseBody = json_decode((string)$response->getBody());
    
        $this->assertGreaterThanOrEqual(1, count($responseBody->resources));

        //  ERRORS
        $response     = $resListController->get($request, new \Slim\Http\Response(), ['userId' => 19, 'groupId' => 777, 'basketId' => $myBasket['id']]);
        $responseBody = json_decode((string)$response->getBody());
        $this->assertSame('Group or basket does not exist', $responseBody->errors);

        $response     = $resListController->get($request, new \Slim\Http\Response(), ['userId' => 19, 'groupId' => 2, 'basketId' => 9999]);
        $responseBody = json_decode((string)$response->getBody());
        $this->assertSame('Group or basket does not exist', $responseBody->errors);

        $response     = $resListController->get($request, new \Slim\Http\Response(), ['userId' => 777, 'groupId' => 2, 'basketId' => $myBasket['id']]);
        $responseBody = json_decode((string)$response->getBody());
        $this->assertSame('Basket out of perimeter', $responseBody->errors);

        $GLOBALS['login'] = 'superadmin';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];
    }

    public function testGetFilters()
    {
        $GLOBALS['login'] = 'bbain';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];
        $myBasket = \Basket\models\BasketModel::getByBasketId(['basketId' => 'MyBasket', 'select' => ['id']]);

        $resListController = new \Resource\controllers\ResourceListController();

        //  GET
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        $aArgs = [
        ];
        $fullRequest = $request->withQueryParams($aArgs);

        $response     = $resListController->getFilters($fullRequest, new \Slim\Http\Response(), ['userId' => 19, 'groupId' => 2, 'basketId' => $myBasket['id']]);
        $responseBody = json_decode((string)$response->getBody());

        $this->assertIsArray($responseBody->entities);
        $this->assertIsArray($responseBody->priorities);
        $this->assertIsArray($responseBody->categories);
        $this->assertIsArray($responseBody->statuses);
        $this->assertIsArray($responseBody->entitiesChildren);

        //  GET
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        $aArgs = [
            'search'   => 'Breaking News',
            'statuses' => 'NEW,COU'
        ];
        $fullRequest = $request->withQueryParams($aArgs);

        $response     = $resListController->getFilters($fullRequest, new \Slim\Http\Response(), ['userId' => 19, 'groupId' => 2, 'basketId' => $myBasket['id']]);
        $responseBody = json_decode((string)$response->getBody());

        $this->assertGreaterThanOrEqual(2, count($responseBody->priorities));
        $this->assertGreaterThanOrEqual(3, count($responseBody->statuses));

        foreach ([$responseBody->priorities, $responseBody->statuses] as $response) {
            foreach ($response as $value) {
                $this->assertNotNull($value->id);
                $this->assertNotNull($value->label);
                $this->assertIsInt($value->count);
            }
        }

        //  GET
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        $aArgs = [
            'priorities'       => 'poiuytre1379nbvc,poiuytre1391nbvc',
            'categories'       => 'incoming',
            'entitiesChildren' => 'PJS'
        ];
        $fullRequest = $request->withQueryParams($aArgs);

        $response     = $resListController->getFilters($fullRequest, new \Slim\Http\Response(), ['userId' => 19, 'groupId' => 2, 'basketId' => $myBasket['id']]);
        $responseBody = json_decode((string)$response->getBody());

        $this->assertIsArray($responseBody->entities);
        $this->assertIsArray($responseBody->priorities);
        $this->assertIsArray($responseBody->categories);
        $this->assertIsArray($responseBody->statuses);
        $this->assertIsArray($responseBody->entitiesChildren);

        $aArgs = [
            'priorities'       => 'poiuytre1379nbvc,poiuytre1391nbvc',
            'categories'       => 'incoming',
            'entitiesChildren' => 'PJS',
            'doctypes' => 102,
            'entities' => 'PJS',
            'folders' => '1'
        ];
        $fullRequest = $request->withQueryParams($aArgs);

        $response     = $resListController->getFilters($fullRequest, new \Slim\Http\Response(), ['userId' => 19, 'groupId' => 2, 'basketId' => $myBasket['id']]);
        $responseBody = json_decode((string)$response->getBody());

        $this->assertIsArray($responseBody->entities);
        $this->assertIsArray($responseBody->priorities);
        $this->assertIsArray($responseBody->categories);
        $this->assertIsArray($responseBody->statuses);
        $this->assertIsArray($responseBody->entitiesChildren);

        // Errors
        $response     = $resListController->getFilters($request, new \Slim\Http\Response(), ['userId' => 19, 'groupId' => 2, 'basketId' => $myBasket['id'] * 1000]);
        $this->assertSame(403, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Group or basket does not exist', $responseBody['errors']);

        $GLOBALS['login'] = 'superadmin';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];
    }

    public function testGetActions()
    {
        $GLOBALS['login'] = 'bbain';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];
        $myBasket = \Basket\models\BasketModel::getByBasketId(['basketId' => 'MyBasket', 'select' => ['id']]);

        $resListController = new \Resource\controllers\ResourceListController();

        //  GET
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        $queryParams = ['resId' => $GLOBALS['resources'][0]];
        $fullRequest = $request->withQueryParams($queryParams);
        $response     = $resListController->getActions($fullRequest, new \Slim\Http\Response(), ['userId' => 19, 'groupId' => 2, 'basketId' => $myBasket['id']]);
        $this->assertSame(200, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);

        $this->assertIsArray($responseBody['actions']);
        $this->assertNotNull($responseBody['actions']);

        // Errors
        $response     = $resListController->getActions($request, new \Slim\Http\Response(), ['userId' => 19, 'groupId' => 2, 'basketId' => $myBasket['id'] * 1000]);
        $this->assertSame(403, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Group or basket does not exist', $responseBody['errors']);

        $GLOBALS['login'] = 'superadmin';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];
    }

    public function testSetAction()
    {
        $GLOBALS['login'] = 'bbain';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];

        $resListController = new \Resource\controllers\ResourceListController();
        $myBasket = \Basket\models\BasketModel::getByBasketId(['basketId' => 'MyBasket', 'select' => ['id']]);

        // GET
        // ERROR
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'PUT']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        $response     = $resListController->setAction($request, new \Slim\Http\Response(), []);
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Data resources is empty or not an array', $responseBody['errors']);


        $body = [
            'resources' => [1]
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);
        $response     = $resListController->setAction($fullRequest, new \Slim\Http\Response(), ['userId' => $GLOBALS['id'], 'basketId' => $myBasket['id'], 'groupId' => 10000 ]);
        $this->assertSame(403, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody());
        $this->assertSame('Group or basket does not exist', $responseBody->errors);

        $body = [
            'resources' => [1]
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);
        $response     = $resListController->setAction($fullRequest, new \Slim\Http\Response(), ['userId' => $GLOBALS['id'], 'basketId' => $myBasket['id'], 'groupId' => 1 ]);
        $this->assertSame(403, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody());
        $this->assertSame('Group is not linked to this user', $responseBody->errors);

        $body = [
            'resources' => [1]
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);
        $response     = $resListController->setAction($fullRequest, new \Slim\Http\Response(), ['userId' => $GLOBALS['id'], 'basketId' => $myBasket['id'], 'groupId' => 2, 'actionId' => 2]);
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody());
        $this->assertSame('Action is not linked to this group basket', $responseBody->errors);

        $body = [
            'resources' => [1]
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);
        $response     = $resListController->setAction($fullRequest, new \Slim\Http\Response(), ['userId' => $GLOBALS['id'], 'basketId' => $myBasket['id'], 'groupId' => 2, 'actionId' => 400]);
        $this->assertSame(403, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody());
        $this->assertSame('Resources out of perimeter', $responseBody->errors);

        // Success
        \Resource\models\ResModel::update([
            'set'   => ['status' => 'NEW'],
            'where' => ['res_id = ?'],
            'data'  => [$GLOBALS['resources'][2]]
        ]);

        $body = [
            'resources' => [$GLOBALS['resources'][2]]
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);
        $response     = $resListController->setAction($fullRequest, new \Slim\Http\Response(), ['userId' => $GLOBALS['id'], 'basketId' => $myBasket['id'], 'groupId' => 2, 'actionId' => '20']);
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

    public function testLock()
    {
        $GLOBALS['login'] = 'bbain';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];

        $resListController = new \Resource\controllers\ResourceListController();
        $myBasket = \Basket\models\BasketModel::getByBasketId(['basketId' => 'MyBasket', 'select' => ['id']]);

        // GET
        // ERROR
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'PUT']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        $response     = $resListController->lock($request, new \Slim\Http\Response(), []);
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Data resources is empty or not an array', $responseBody['errors']);

        $body = [
            'resources' => [1]
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);
        $response     = $resListController->lock($fullRequest, new \Slim\Http\Response(), ['userId' => $GLOBALS['id'], 'basketId' => $myBasket['id'], 'groupId' => 10000 ]);
        $this->assertSame(403, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Group or basket does not exist', $responseBody['errors']);

        $response     = $resListController->lock($fullRequest, new \Slim\Http\Response(), ['userId' => $GLOBALS['id'], 'basketId' => $myBasket['id'], 'groupId' => 2 ]);
        $this->assertSame(403, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Resources out of perimeter', $responseBody['errors']);

        // Success
        $body = [
            'resources' => [$GLOBALS['resources'][1]]
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);
        $response     = $resListController->lock($fullRequest, new \Slim\Http\Response(), ['userId' => $GLOBALS['id'], 'basketId' => $myBasket['id'], 'groupId' => 2 ]);
        $this->assertSame(204, $response->getStatusCode());

        $GLOBALS['login'] = 'superadmin';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];
    }

    public function testUnLock()
    {
        $GLOBALS['login'] = 'bbain';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];

        $resListController = new \Resource\controllers\ResourceListController();
        $myBasket = \Basket\models\BasketModel::getByBasketId(['basketId' => 'MyBasket', 'select' => ['id']]);

        // GET
        // ERROR
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'PUT']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        $response     = $resListController->unlock($request, new \Slim\Http\Response(), []);
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Data resources is empty or not an array', $responseBody['errors']);

        $body = [
            'resources' => [1]
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);
        $response     = $resListController->unlock($fullRequest, new \Slim\Http\Response(), ['userId' => $GLOBALS['id'], 'basketId' => $myBasket['id'], 'groupId' => 10000 ]);
        $this->assertSame(403, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Group or basket does not exist', $responseBody['errors']);

        $response     = $resListController->unlock($fullRequest, new \Slim\Http\Response(), ['userId' => $GLOBALS['id'], 'basketId' => $myBasket['id'], 'groupId' => 2 ]);
        $this->assertSame(403, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Resources out of perimeter', $responseBody['errors']);

        // Success
        $body = [
            'resources' => [$GLOBALS['resources'][1]]
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);
        $response     = $resListController->unlock($fullRequest, new \Slim\Http\Response(), ['userId' => $GLOBALS['id'], 'basketId' => $myBasket['id'], 'groupId' => 2 ]);
        $this->assertSame(204, $response->getStatusCode());

        $GLOBALS['login'] = 'superadmin';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];
    }
}
