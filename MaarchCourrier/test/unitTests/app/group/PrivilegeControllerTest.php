<?php

/**
* Copyright Maarch since 2008 under licence GPLv3.
* See LICENCE.txt file at the root folder for more details.
* This file is part of Maarch software.
*
*/

use PHPUnit\Framework\TestCase;

class PrivilegeControllerTest extends TestCase
{
    private static $id = null;
    private static $resId = null;

    public function testCreate()
    {
        $groupController = new \Group\controllers\GroupController();

        //  CREATE
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'POST']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        $aArgs = [
            'group_id'      => 'TEST-JusticeLeague',
            'group_desc'    => 'Beyond the darkness',
            'security'      => [
                'where_clause'      => '1=2',
                'maarch_comment'    => 'commentateur du dimanche'
            ]
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($aArgs, $request);

        $response     = $groupController->create($fullRequest, new \Slim\Http\Response());
        $responseBody = json_decode((string)$response->getBody());

        self::$id = $responseBody->group;

        $this->assertIsInt($responseBody->group);
    }

    public function testAddPrivilege()
    {
        $privilegeController = new \Group\controllers\PrivilegeController();

        //  Add privilege
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'POST']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        $args = [
            'privilegeId'      => 'entities_print_sep_mlb',
            'id'    => self::$id
        ];

        $response     = $privilegeController->addPrivilege($request, new \Slim\Http\Response(), $args);
        $this->assertSame(204, $response->getStatusCode());

        // Add privilege again

        $response     = $privilegeController->addPrivilege($request, new \Slim\Http\Response(), $args);
        $this->assertSame(204, $response->getStatusCode());

        $args = [
            'privilegeId'      => 'admin_users',
            'id'    => self::$id
        ];

        $response     = $privilegeController->addPrivilege($request, new \Slim\Http\Response(), $args);
        $this->assertSame(204, $response->getStatusCode());

        // Error : group does not exist
        $args = [
            'privilegeId'      => 'entities_print_sep_mlb',
            'id'    => self::$id * 100
        ];

        $response     = $privilegeController->addPrivilege($request, new \Slim\Http\Response(), $args);
        $this->assertSame(400, $response->getStatusCode());

        $responseBody = json_decode((string)$response->getBody());
        $this->assertIsString($responseBody->errors);
        $this->assertSame('Group not found', $responseBody->errors);

        $args = [
            'privilegeId'      => 'entities_print_sep_mlb',
            'id'    => 'wrong format'
        ];

        $response     = $privilegeController->addPrivilege($request, new \Slim\Http\Response(), $args);
        $this->assertSame(400, $response->getStatusCode());

        $responseBody = json_decode((string)$response->getBody());
        $this->assertIsString($responseBody->errors);
        $this->assertSame('Route id is empty or not an integer', $responseBody->errors);

        $args = [
            'privilegeId'      => 1000,
            'id'    => self::$id
        ];

        $response     = $privilegeController->addPrivilege($request, new \Slim\Http\Response(), $args);
        $this->assertSame(400, $response->getStatusCode());

        $responseBody = json_decode((string)$response->getBody());
        $this->assertIsString($responseBody->errors);
        $this->assertSame('Route privilegeId is empty or not an integer', $responseBody->errors);

        $GLOBALS['login'] = 'bbain';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];

        $response     = $privilegeController->addPrivilege($request, new \Slim\Http\Response(), $args);
        $this->assertSame(403, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);

        $this->assertSame('Service forbidden', $responseBody['errors']);

        $GLOBALS['login'] = 'superadmin';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];
    }

    public function testUpdateParameters()
    {
        $privilegeController = new \Group\controllers\PrivilegeController();

        //  Remove privilege
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'POST']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        $args = [
            'privilegeId'      => 'entities_print_sep_mlb',
            'id'    => self::$id
        ];

        $body = [
            'parameters' => [
                'enabled' => true
            ]
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response     = $privilegeController->updateParameters($fullRequest, new \Slim\Http\Response(), $args);
        $this->assertSame(204, $response->getStatusCode());

        // Fails
        $body = [
            'parameters' => 'wrong format'
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response     = $privilegeController->updateParameters($fullRequest, new \Slim\Http\Response(), $args);
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Body parameters is not an array', $responseBody['errors']);

        $args = [
            'privilegeId'      => 'entities_print_sep_mlb',
            'id'    => self::$id * 100
        ];

        $response     = $privilegeController->updateParameters($request, new \Slim\Http\Response(), $args);
        $this->assertSame(400, $response->getStatusCode());

        $responseBody = json_decode((string)$response->getBody());
        $this->assertIsString($responseBody->errors);
        $this->assertSame('Group not found', $responseBody->errors);

        $args = [
            'privilegeId'      => 'entities_print_sep_mlb',
            'id'    => 'wrong format'
        ];

        $response     = $privilegeController->updateParameters($request, new \Slim\Http\Response(), $args);
        $this->assertSame(400, $response->getStatusCode());

        $responseBody = json_decode((string)$response->getBody());
        $this->assertIsString($responseBody->errors);
        $this->assertSame('Route id is empty or not an integer', $responseBody->errors);

        $args = [
            'privilegeId'      => 1000,
            'id'    => self::$id
        ];

        $response     = $privilegeController->updateParameters($request, new \Slim\Http\Response(), $args);
        $this->assertSame(400, $response->getStatusCode());

        $responseBody = json_decode((string)$response->getBody());
        $this->assertIsString($responseBody->errors);
        $this->assertSame('Route privilegeId is empty or not an integer', $responseBody->errors);

        $GLOBALS['login'] = 'bbain';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];

        $response     = $privilegeController->updateParameters($request, new \Slim\Http\Response(), $args);
        $this->assertSame(403, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);

        $this->assertSame('Service forbidden', $responseBody['errors']);

        $GLOBALS['login'] = 'superadmin';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];
    }

    public function testGetParameters()
    {
        $privilegeController = new \Group\controllers\PrivilegeController();

        //  Remove privilege
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'POST']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        $args = [
            'privilegeId'      => 'entities_print_sep_mlb',
            'id'    => self::$id
        ];

        $response     = $privilegeController->getParameters($request, new \Slim\Http\Response(), $args);
        $this->assertSame(200, $response->getStatusCode());

        $responseBody = json_decode((string)$response->getBody(), true);

        $this->assertIsArray($responseBody);
        $this->assertIsBool($responseBody['enabled']);
        $this->assertSame(true, $responseBody['enabled']);


        $queryParams = ['parameter' => 'enabled'];
        $fullRequest = $request->withQueryParams($queryParams);
        $response     = $privilegeController->getParameters($fullRequest, new \Slim\Http\Response(), $args);
        $this->assertSame(200, $response->getStatusCode());

        $responseBody = json_decode((string)$response->getBody(), true);

        $this->assertIsBool($responseBody);
        $this->assertSame(true, $responseBody);

        // Fails
        $queryParams = ['parameter' => 'fake'];
        $fullRequest = $request->withQueryParams($queryParams);
        $response     = $privilegeController->getParameters($fullRequest, new \Slim\Http\Response(), $args);
        $this->assertSame(400, $response->getStatusCode());

        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Parameter not found', $responseBody['errors']);

        $args = [
            'privilegeId'      => 'entities_print_sep_mlb',
            'id'    => self::$id * 100
        ];

        $response     = $privilegeController->getParameters($request, new \Slim\Http\Response(), $args);
        $this->assertSame(400, $response->getStatusCode());

        $responseBody = json_decode((string)$response->getBody());
        $this->assertIsString($responseBody->errors);
        $this->assertSame('Group not found', $responseBody->errors);

        $args = [
            'privilegeId'      => 'entities_print_sep_mlb',
            'id'    => 'wrong format'
        ];

        $response     = $privilegeController->getParameters($request, new \Slim\Http\Response(), $args);
        $this->assertSame(400, $response->getStatusCode());

        $responseBody = json_decode((string)$response->getBody());
        $this->assertIsString($responseBody->errors);
        $this->assertSame('Route id is empty or not an integer', $responseBody->errors);

        $args = [
            'privilegeId'      => 1000,
            'id'    => self::$id
        ];

        $response     = $privilegeController->getParameters($request, new \Slim\Http\Response(), $args);
        $this->assertSame(400, $response->getStatusCode());

        $responseBody = json_decode((string)$response->getBody());
        $this->assertIsString($responseBody->errors);
        $this->assertSame('Route privilegeId is empty or not an integer', $responseBody->errors);
    }

    public function testGetPrivilegesByUser()
    {
        $privilegeController = new \Group\controllers\PrivilegeController();

        $response = $privilegeController::getPrivilegesByUser(['userId' => $GLOBALS['id']]);

        $this->assertIsArray($response);
        $this->assertSame(1, count($response));
        $this->assertSame('ALL_PRIVILEGES', $response[0]);

        $GLOBALS['login'] = 'bbain';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];

        $response = $privilegeController::getPrivilegesByUser(['userId' => $GLOBALS['id']]);

        $this->assertIsArray($response);
        $this->assertNotContains('ALL_PRIVILEGES', $response);

        $GLOBALS['login'] = 'superadmin';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];
    }

    public function testGetAssignableGroups()
    {
        $privilegeController = new \Group\controllers\PrivilegeController();

        $response = $privilegeController::getAssignableGroups(['userId' => $GLOBALS['id']]);

        $this->assertIsArray($response);
        $this->assertEmpty($response);

        $GLOBALS['login'] = 'bblier';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];

        $response = $privilegeController::getAssignableGroups(['userId' => $GLOBALS['id']]);

        $this->assertIsArray($response);
        $this->assertNotEmpty($response);

        $GLOBALS['login'] = 'superadmin';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];
    }

    public function testCanAssignGroup()
    {
        $privilegeController = new \Group\controllers\PrivilegeController();

        $response = $privilegeController::canAssignGroup(['userId' => $GLOBALS['id'], 'groupId' => self::$id]);

        $this->assertIsBool($response);
        $this->assertSame(true, $response);

        $GLOBALS['login'] = 'bblier';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];

        $response = $privilegeController::canAssignGroup(['userId' => $GLOBALS['id'], 'groupId' => self::$id]);

        $this->assertIsBool($response);
        $this->assertSame(false, $response);

        $GLOBALS['login'] = 'superadmin';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];
    }

    public function testIsResourceInProcess()
    {
        $GLOBALS['login'] = 'cchaplin';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];

        $resController = new \Resource\controllers\ResController();

        //  CREATE test resource
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'POST']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        $argsMailNew = [
            'modelId'          => 1,
            'status'           => 'NEW',
            'format'           => 'txt',
            'confidentiality'  => false,
            'documentDate'     => '2019-01-01 17:18:47',
            'arrivalDate'      => '2019-01-01 17:18:47',
            'processLimitDate' => '2029-01-01',
            'doctype'          => 102,
            'destination'      => 15,
            'initiator'        => 15,
            'subject'          => 'Breaking News : Superman is alive - PHP unit',
            'typist'           => 19,
            'priority'         => 'poiuytre1357nbvc',
            'followed'         => true,
            'diffusionList'    => [
                [
                    'id'   => 11,
                    'type' => 'user',
                    'mode' => 'dest'
                ]
            ]
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($argsMailNew, $request);

        $response     = $resController->create($fullRequest, new \Slim\Http\Response());
        $responseBody = json_decode((string)$response->getBody(), true);
        self::$resId = $responseBody['resId'];
        $this->assertIsInt(self::$resId);

        $privilegeController = new \Group\controllers\PrivilegeController();

        $response = $privilegeController::isResourceInProcess(['userId' => $GLOBALS['id'], 'resId' => self::$resId]);

        $this->assertIsBool($response);
        $this->assertSame(false, $response);

        $GLOBALS['login'] = 'aackermann';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];

        $response = $privilegeController::isResourceInProcess(['userId' => $GLOBALS['id'], 'resId' => self::$resId]);

        $this->assertIsBool($response);
        $this->assertSame(true, $response);

        $GLOBALS['login'] = 'superadmin';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];

        $response = $privilegeController::isResourceInProcess(['userId' => $GLOBALS['id'], 'resId' => self::$resId]);

        $this->assertIsBool($response);
        $this->assertSame(false, $response);
    }

    public function testCanUpdateResource()
    {
        $GLOBALS['login'] = 'cchaplin';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];

        $privilegeController = new \Group\controllers\PrivilegeController();

        $response = $privilegeController::canUpdateResource(['userId' => $GLOBALS['id'], 'resId' => self::$resId]);

        $this->assertIsBool($response);
        $this->assertSame(false, $response);

        $GLOBALS['login'] = 'aackermann';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];

        $response = $privilegeController::canUpdateResource(['userId' => $GLOBALS['id'], 'resId' => self::$resId]);

        $this->assertIsBool($response);
        $this->assertSame(false, $response);

        $GLOBALS['login'] = 'superadmin';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];

        $response = $privilegeController::canUpdateResource(['userId' => $GLOBALS['id'], 'resId' => self::$resId]);

        $this->assertIsBool($response);
        $this->assertSame(true, $response);

        Resource\models\ResModel::delete([
            'where' => ['res_id in (?)'],
            'data' => [[self::$resId]]
        ]);

        $res = \Resource\models\ResModel::getById(['resId' => self::$resId, 'select' => ['*']]);
        $this->assertIsArray($res);
        $this->assertEmpty($res);
    }

    public function testRemovePrivilege()
    {
        $privilegeController = new \Group\controllers\PrivilegeController();

        //  Remove privilege
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'POST']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        $args = [
            'privilegeId'      => 'entities_print_sep_mlb',
            'id'    => self::$id
        ];

        $response     = $privilegeController->removePrivilege($request, new \Slim\Http\Response(), $args);
        $this->assertSame(204, $response->getStatusCode());

        // Remove privilege again

        $response     = $privilegeController->removePrivilege($request, new \Slim\Http\Response(), $args);
        $this->assertSame(204, $response->getStatusCode());

        // Error : group does not exist
        $args = [
            'privilegeId'      => 'entities_print_sep_mlb',
            'id'    => self::$id * 100
        ];

        $response     = $privilegeController->removePrivilege($request, new \Slim\Http\Response(), $args);
        $this->assertSame(400, $response->getStatusCode());

        $responseBody = json_decode((string)$response->getBody());
        $this->assertIsString($responseBody->errors);
        $this->assertSame('Group not found', $responseBody->errors);

        $args = [
            'privilegeId'      => 'entities_print_sep_mlb',
            'id'    => 'wrong format'
        ];

        $response     = $privilegeController->removePrivilege($request, new \Slim\Http\Response(), $args);
        $this->assertSame(400, $response->getStatusCode());

        $responseBody = json_decode((string)$response->getBody());
        $this->assertIsString($responseBody->errors);
        $this->assertSame('Route id is empty or not an integer', $responseBody->errors);

        $args = [
            'privilegeId'      => 1000,
            'id'    => self::$id
        ];

        $response     = $privilegeController->removePrivilege($request, new \Slim\Http\Response(), $args);
        $this->assertSame(400, $response->getStatusCode());

        $responseBody = json_decode((string)$response->getBody());
        $this->assertIsString($responseBody->errors);
        $this->assertSame('Route privilegeId is empty or not an integer', $responseBody->errors);

        $GLOBALS['login'] = 'bbain';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];

        $response     = $privilegeController->removePrivilege($request, new \Slim\Http\Response(), $args);
        $this->assertSame(403, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);

        $this->assertSame('Service forbidden', $responseBody['errors']);

        $GLOBALS['login'] = 'superadmin';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];
    }

    public function testDelete()
    {
        $groupController = new \Group\controllers\GroupController();

        //  DELETE
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'DELETE']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);
        $response       = $groupController->delete($request, new \Slim\Http\Response(), ['id' => self::$id]);
        $responseBody   = json_decode((string)$response->getBody());

        $this->assertIsArray($responseBody->groups);
        $this->assertNotEmpty($responseBody->groups);

        //  READ
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);
        $response       = $groupController->getDetailledById($request, new \Slim\Http\Response(), ['id' => self::$id]);
        $responseBody   = json_decode((string)$response->getBody());

        $this->assertSame('Group not found', $responseBody->errors);
    }
}
