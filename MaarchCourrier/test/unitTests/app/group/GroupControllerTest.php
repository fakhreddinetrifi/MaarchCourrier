<?php

/**
* Copyright Maarch since 2008 under licence GPLv3.
* See LICENCE.txt file at the root folder for more details.
* This file is part of Maarch software.
*
*/

use PHPUnit\Framework\TestCase;

class GroupControllerTest extends TestCase
{
    private static $id = null;

    public function testCreate()
    {
        $groupController = new \Group\controllers\GroupController();

        //  CREATE
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'POST']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        $body = [
            'group_id'      => 'TEST-JusticeLeague',
            'group_desc'    => 'Beyond the darkness',
            'security'      => [
                'where_clause'      => '1=2',
                'maarch_comment'    => 'commentateur du dimanche'
            ]
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response     = $groupController->create($fullRequest, new \Slim\Http\Response());
        $responseBody = json_decode((string)$response->getBody());

        self::$id = $responseBody->group;

        $this->assertIsInt($responseBody->group);

        //  READ
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);
        $response     = $groupController->getDetailledById($request, new \Slim\Http\Response(), ['id' => self::$id]);
        $responseBody = json_decode((string)$response->getBody());

        $this->assertSame('TEST-JusticeLeague', $responseBody->group->group_id);
        $this->assertSame('Beyond the darkness', $responseBody->group->group_desc);
        $this->assertSame('1=2', $responseBody->group->security->where_clause);
        $this->assertSame('commentateur du dimanche', $responseBody->group->security->maarch_comment);
        $this->assertIsArray($responseBody->group->users);
        $this->assertIsArray($responseBody->group->baskets);
        $this->assertEmpty($responseBody->group->users);
        $this->assertEmpty($responseBody->group->baskets);
        $this->assertSame(true, $responseBody->group->canAdminUsers);
        $this->assertSame(true, $responseBody->group->canAdminBaskets);

        // Fail
        $body = [
            'group_id'      => 'TEST-JusticeLeague',
            'security'      => [
                'where_clause'      => '1=2',
                'maarch_comment'    => 'commentateur du dimanche'
            ]
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response     = $groupController->create($fullRequest, new \Slim\Http\Response());
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Bad Request', $responseBody['errors']);

        $body = [
            'group_id'      => 'TEST-JusticeLeague',
            'group_desc'    => 'Beyond the darkness',
            'security'      => [
                'where_clause'      => 'wrong clause format',
                'maarch_comment'    => 'commentateur du dimanche'
            ]
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response     = $groupController->create($fullRequest, new \Slim\Http\Response());
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame(_ID. ' ' . _ALREADY_EXISTS, $responseBody['errors']);

        $body = [
            'group_id'      => 'TEST-JusticeLeague2',
            'group_desc'    => 'Beyond the darkness',
            'security'      => [
                'where_clause'      => 'wrong clause format',
                'maarch_comment'    => 'commentateur du dimanche'
            ]
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response     = $groupController->create($fullRequest, new \Slim\Http\Response());
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame(_INVALID_CLAUSE, $responseBody['errors']);

        $GLOBALS['login'] = 'bbain';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];

        $response     = $groupController->create($fullRequest, new \Slim\Http\Response());
        $this->assertSame(403, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);

        $this->assertSame('Service forbidden', $responseBody['errors']);

        $GLOBALS['login'] = 'superadmin';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];
    }

    public function testUpdate()
    {
        $groupController = new \Group\controllers\GroupController();

        //  UPDATE
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'PUT']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);
        $aArgs = [
            'description' => 'Beyond the darkness #2',
            'security'  => [
                'where_clause'   => '1=3',
                'maarch_comment' => 'commentateur du dimanche #2'
            ]
        ];

        $fullRequest = \httpRequestCustom::addContentInBody($aArgs, $request);

        $response     = $groupController->update($fullRequest, new \Slim\Http\Response(), ['id' => self::$id]);
        $responseBody = json_decode((string)$response->getBody());

        $this->assertSame('success', $responseBody->success);

        //  READ
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);
        $response     = $groupController->getDetailledById($request, new \Slim\Http\Response(), ['id' => self::$id]);
        $responseBody = json_decode((string)$response->getBody());

        $this->assertSame('TEST-JusticeLeague', $responseBody->group->group_id);
        $this->assertSame('Beyond the darkness #2', $responseBody->group->group_desc);
        $this->assertSame('1=3', $responseBody->group->security->where_clause);
        $this->assertSame('commentateur du dimanche #2', $responseBody->group->security->maarch_comment);
        $this->assertIsArray($responseBody->group->users);
        $this->assertIsArray($responseBody->group->baskets);
        $this->assertEmpty($responseBody->group->users);
        $this->assertEmpty($responseBody->group->baskets);
        $this->assertSame(true, $responseBody->group->canAdminUsers);
        $this->assertSame(true, $responseBody->group->canAdminBaskets);

        // Fail
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'PUT']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        $body = [
            'security'      => [
                'where_clause'      => '1=2',
                'maarch_comment'    => 'commentateur du dimanche'
            ]
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response     = $groupController->update($fullRequest, new \Slim\Http\Response(), ['id' => self::$id * 1000]);
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Group not found', $responseBody['errors']);

        $body = [
            'security'      => [
                'where_clause'      => '1=2',
                'maarch_comment'    => 'commentateur du dimanche'
            ]
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response     = $groupController->update($fullRequest, new \Slim\Http\Response(), ['id' => self::$id]);
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Bad Request', $responseBody['errors']);

        $body = [
            'description'    => 'Beyond the darkness',
            'security'      => [
                'where_clause'      => 'wrong clause format',
                'maarch_comment'    => 'commentateur du dimanche'
            ]
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response     = $groupController->update($fullRequest, new \Slim\Http\Response(), ['id' => self::$id]);
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame(_INVALID_CLAUSE, $responseBody['errors']);

        $GLOBALS['login'] = 'bbain';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];

        $response     = $groupController->update($fullRequest, new \Slim\Http\Response(), ['id' => self::$id]);
        $this->assertSame(403, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);

        $this->assertSame('Service forbidden', $responseBody['errors']);

        $GLOBALS['login'] = 'superadmin';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];
    }

    public function testGetById()
    {
        $groupController = new \Group\controllers\GroupController();

        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        $response     = $groupController->getById($request, new \Slim\Http\Response(), ['id' => self::$id]);
        $responseBody = json_decode((string)$response->getBody());

        $this->assertNotEmpty($responseBody->group);

        $this->assertSame(self::$id, $responseBody->group->id);
        $this->assertSame('TEST-JusticeLeague', $responseBody->group->group_id);
        $this->assertSame('Beyond the darkness #2', $responseBody->group->group_desc);

        // ERROR
        $response     = $groupController->getById($request, new \Slim\Http\Response(), ['id' => '123456789']);
        $responseBody = json_decode((string)$response->getBody());

        $this->assertSame('Group not found', $responseBody->errors);

        $GLOBALS['login'] = 'bbain';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];

        $response     = $groupController->getById($request, new \Slim\Http\Response(), ['id' => self::$id]);
        $this->assertSame(403, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);

        $this->assertSame('Service forbidden', $responseBody['errors']);

        $GLOBALS['login'] = 'superadmin';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];
    }

    public function testGet()
    {
        $groupController = new \Group\controllers\GroupController();

        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        $response     = $groupController->get($request, new \Slim\Http\Response());
        $responseBody = json_decode((string)$response->getBody());

        $this->assertNotEmpty($responseBody->groups);

        foreach ($responseBody->groups as $value) {
            $this->assertNotEmpty($value->group_id);
            $this->assertNotEmpty($value->group_desc);
            $this->assertNotNull($value->users);
            $this->assertIsInt($value->id);
        }

        $GLOBALS['login'] = 'bbain';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];

        $response     = $groupController->get($request, new \Slim\Http\Response());
        $this->assertSame(200, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody());

        $this->assertNotEmpty($responseBody->groups);

        foreach ($responseBody->groups as $value) {
            $this->assertNotEmpty($value->group_desc);
            $this->assertEmpty($value->users);
            $this->assertIsInt($value->id);
        }

        $GLOBALS['login'] = 'superadmin';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];
    }

    public function testGetDetailedById()
    {
        $groupController = new \Group\controllers\GroupController();

        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        $response     = $groupController->getDetailledById($request, new \Slim\Http\Response(), ['id' => self::$id]);
        $responseBody = json_decode((string)$response->getBody(), true);

        $this->assertSame('TEST-JusticeLeague', $responseBody['group']['group_id']);
        $this->assertSame('Beyond the darkness #2', $responseBody['group']['group_desc']);
        $this->assertSame('1=3', $responseBody['group']['security']['where_clause']);
        $this->assertSame('commentateur du dimanche #2', $responseBody['group']['security']['maarch_comment']);
        $this->assertIsArray($responseBody['group']['users']);
        $this->assertIsArray($responseBody['group']['baskets']);
        $this->assertEmpty($responseBody['group']['users']);
        $this->assertEmpty($responseBody['group']['baskets']);
        $this->assertSame(true, $responseBody['group']['canAdminUsers']);
        $this->assertSame(true, $responseBody['group']['canAdminBaskets']);

        $GLOBALS['login'] = 'bblier';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];

        $response     = $groupController->getDetailledById($request, new \Slim\Http\Response(), ['id' => self::$id]);
        $this->assertSame(200, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);

        $this->assertSame('TEST-JusticeLeague', $responseBody['group']['group_id']);
        $this->assertSame('Beyond the darkness #2', $responseBody['group']['group_desc']);
        $this->assertSame('1=3', $responseBody['group']['security']['where_clause']);
        $this->assertSame('commentateur du dimanche #2', $responseBody['group']['security']['maarch_comment']);
        $this->assertIsArray($responseBody['group']['users']);
        $this->assertIsArray($responseBody['group']['baskets']);
        $this->assertEmpty($responseBody['group']['baskets']);
        $this->assertSame(true, $responseBody['group']['canAdminUsers']);
        $this->assertSame(true, $responseBody['group']['canAdminBaskets']);
        $this->assertEmpty($responseBody['group']['users']);
        $this->assertIsArray($responseBody['group']['privileges']);
        $this->assertEmpty($responseBody['group']['privileges']);

        // Fail
        $GLOBALS['login'] = 'bbain';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];

        $response     = $groupController->getDetailledById($request, new \Slim\Http\Response(), ['id' => self::$id]);
        $this->assertSame(403, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);

        $this->assertSame('Service forbidden', $responseBody['errors']);

        $GLOBALS['login'] = 'superadmin';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];
    }

    public function testGetIndexingInformationById()
    {
        $groupController = new \Group\controllers\GroupController();

        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        $response     = $groupController->getIndexingInformationsById($request, new \Slim\Http\Response(), ['id' => self::$id]);
        $this->assertSame(200, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);

        $this->assertSame(false, $responseBody['group']['canIndex']);
        $this->assertEmpty($responseBody['group']['indexationParameters']['actions']);
        $this->assertEmpty($responseBody['group']['indexationParameters']['entities']);
        $this->assertEmpty($responseBody['group']['indexationParameters']['keywords']);
        $this->assertIsArray($responseBody['actions']);
        $this->assertNotEmpty($responseBody['actions']);
        $this->assertIsArray($responseBody['entities']);
        $this->assertNotEmpty($responseBody['entities']);

        // Fail
        $response     = $groupController->getIndexingInformationsById($request, new \Slim\Http\Response(), ['id' => self::$id * 1000]);
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);

        $this->assertSame('Group not found', $responseBody['errors']);

        $GLOBALS['login'] = 'bbain';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];

        $response     = $groupController->getIndexingInformationsById($request, new \Slim\Http\Response(), ['id' => self::$id]);
        $this->assertSame(403, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);

        $this->assertSame('Service forbidden', $responseBody['errors']);

        $GLOBALS['login'] = 'superadmin';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];
    }

    public function testUpdateIndexingInformationById()
    {
        $groupController = new \Group\controllers\GroupController();

        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        $body = [
            'actions' => [1],
            'entities' => [13]
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response     = $groupController->updateIndexingInformations($fullRequest, new \Slim\Http\Response(), ['id' => self::$id]);
        $this->assertSame(204, $response->getStatusCode());

        // Fail
        $body = [

        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response     = $groupController->updateIndexingInformations($fullRequest, new \Slim\Http\Response(), ['id' => self::$id * 1000]);
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);

        $this->assertSame('Body is empty or not an array', $responseBody['errors']);

        $body = [
            'actions' => [1, 100000]
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response     = $groupController->updateIndexingInformations($fullRequest, new \Slim\Http\Response(), ['id' => self::$id * 1000]);
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);

        $this->assertSame('Group not found', $responseBody['errors']);

        $response     = $groupController->updateIndexingInformations($fullRequest, new \Slim\Http\Response(), ['id' => self::$id]);
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);

        $this->assertSame('Body actions contains invalid actions', $responseBody['errors']);

        $body = [
            'actions' => [1],
            'entities' => [13, 100000]
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response     = $groupController->updateIndexingInformations($fullRequest, new \Slim\Http\Response(), ['id' => self::$id]);
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);

        $this->assertSame('Body entities contains invalid entities', $responseBody['errors']);

        $GLOBALS['login'] = 'bbain';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];

        $response     = $groupController->updateIndexingInformations($fullRequest, new \Slim\Http\Response(), ['id' => self::$id]);
        $this->assertSame(403, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);

        $this->assertSame('Service forbidden', $responseBody['errors']);

        $GLOBALS['login'] = 'superadmin';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];
    }

    public function testReassignUsers()
    {
        $groupController = new \Group\controllers\GroupController();

        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        $response     = $groupController->reassignUsers($request, new \Slim\Http\Response(), ['id' => 1, 'newGroupId' => self::$id]);
        $this->assertSame(200, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('success', $responseBody['success']);

        $response     = $groupController->reassignUsers($request, new \Slim\Http\Response(), ['id' => self::$id, 'newGroupId' => 1]);
        $this->assertSame(200, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('success', $responseBody['success']);

        // Fail
        $response     = $groupController->reassignUsers($request, new \Slim\Http\Response(), ['id' => self::$id * 1000]);
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);

        $this->assertSame('Group not found', $responseBody['errors']);

        $response     = $groupController->reassignUsers($request, new \Slim\Http\Response(), ['id' => self::$id, 'newGroupId' => self::$id * 1000]);
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);

        $this->assertSame('Group not found', $responseBody['errors']);

        $GLOBALS['login'] = 'bbain';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];

        $response     = $groupController->reassignUsers($request, new \Slim\Http\Response(), ['id' => self::$id]);
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

        // Fail
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'DELETE']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        $response     = $groupController->delete($request, new \Slim\Http\Response(), ['id' => self::$id * 1000]);
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);

        $this->assertSame('Group not found', $responseBody['errors']);

        $GLOBALS['login'] = 'bbain';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];

        $response     = $groupController->delete($request, new \Slim\Http\Response(), ['id' => self::$id]);
        $this->assertSame(403, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);

        $this->assertSame('Service forbidden', $responseBody['errors']);

        $GLOBALS['login'] = 'superadmin';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];
    }
}
