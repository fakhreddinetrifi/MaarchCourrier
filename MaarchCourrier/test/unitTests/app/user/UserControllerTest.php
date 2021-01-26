<?php

/**
* Copyright Maarch since 2008 under licence GPLv3.
* See LICENCE.txt file at the root folder for more details.
* This file is part of Maarch software.
*
*/

use PHPUnit\Framework\TestCase;

class UserControllerTest extends TestCase
{
    private static $id = null;
    private static $idEmailSignature = null;
    private static $redirectId = null;
    private static $signatureId = null;

    public function testGet()
    {
        $userController = new \User\controllers\UserController();

        //  READ
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        $response     = $userController->get($request, new \Slim\Http\Response());
        $this->assertSame(200, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);

        $this->assertIsArray($responseBody['users']);
        $this->assertNotEmpty($responseBody['users']);

        foreach ($responseBody['users'] as $value) {
            $this->assertNotNull($value['id']);
            $this->assertIsInt($value['id']);
            $this->assertNotNull($value['user_id']);
            $this->assertIsString($value['user_id']);
            $this->assertNotNull($value['firstname']);
            $this->assertIsString($value['firstname']);
            $this->assertNotNull($value['lastname']);
            $this->assertIsString($value['lastname']);
            $this->assertNotNull($value['status']);
            $this->assertIsString($value['status']);
            $this->assertNotNull($value['mail']);
            $this->assertIsString($value['mail']);
            $this->assertNotNull($value['mode']);
            $this->assertIsString($value['mode']);
        }

        $GLOBALS['login'] = 'bblier';
        $userInfo          = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id']     = $userInfo['id'];

        $response     = $userController->get($request, new \Slim\Http\Response());
        $this->assertSame(200, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);

        $this->assertIsArray($responseBody['users']);
        $this->assertNotEmpty($responseBody['users']);

        foreach ($responseBody['users'] as $value) {
            $this->assertNotNull($value['id']);
            $this->assertIsInt($value['id']);
            $this->assertNotNull($value['user_id']);
            $this->assertIsString($value['user_id']);
            $this->assertNotNull($value['firstname']);
            $this->assertIsString($value['firstname']);
            $this->assertNotNull($value['lastname']);
            $this->assertIsString($value['lastname']);
            $this->assertNotNull($value['status']);
            $this->assertIsString($value['status']);
            $this->assertNotNull($value['mail']);
            $this->assertIsString($value['mail']);
            $this->assertNotNull($value['mode']);
            $this->assertIsString($value['mode']);
        }

        // Fail
        $GLOBALS['login'] = 'bbain';
        $userInfo          = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id']     = $userInfo['id'];

        $response     = $userController->get($request, new \Slim\Http\Response());
        $this->assertSame(403, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);

        $this->assertSame('Service forbidden', $responseBody['errors']);

        $GLOBALS['login'] = 'superadmin';
        $userInfo          = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id']     = $userInfo['id'];
    }

    public function testCreate()
    {
        $userController = new \User\controllers\UserController();

        //  CREATE
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'POST']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        $aArgs = [
            'userId'    => 'test-ckent',
            'firstname' => 'TEST-CLARK',
            'lastname'  => 'TEST-KENT',
            'mail'      => 'clark@test.zh'
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($aArgs, $request);

        $response     = $userController->create($fullRequest, new \Slim\Http\Response());
        $responseBody = json_decode((string)$response->getBody());

        self::$id = $responseBody->id;

        $this->assertIsInt(self::$id);

        //  READ
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);
        $response     = $userController->getDetailledById($request, new \Slim\Http\Response(), ['id' => self::$id]);
        $responseBody = json_decode((string)$response->getBody());

        $this->assertSame(self::$id, $responseBody->id);
        $this->assertSame('test-ckent', $responseBody->user_id);
        $this->assertSame('TEST-CLARK', $responseBody->firstname);
        $this->assertSame('TEST-KENT', $responseBody->lastname);
        $this->assertSame('OK', $responseBody->status);
        $this->assertSame(null, $responseBody->phone);
        $this->assertSame('clark@test.zh', $responseBody->mail);
        $this->assertSame(null, $responseBody->initials);

        // Delete user then reactivate it
        \User\models\UserModel::update([
            'set'   => ['status' => 'DEL'],
            'where' => ['id = ?'],
            'data'  => [self::$id]
        ]);

        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'POST']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        $aArgs = [
            'userId'    => 'test-ckent',
            'firstname' => 'TEST-CLARK',
            'lastname'  => 'TEST-KENT',
            'mail'      => 'clark@test.zh'
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($aArgs, $request);

        $response     = $userController->create($fullRequest, new \Slim\Http\Response());
        $responseBody = json_decode((string)$response->getBody(), true);

        $this->assertSame(self::$id, $responseBody['id']);

        // Fail
        $body = [
            'userId'    => 'test-ckent',
            'firstname' => 'TEST-CLARK',
            'lastname'  => 'TEST-KENT',
            'mail'      => 'clark@test.zh'
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response     = $userController->create($fullRequest, new \Slim\Http\Response());
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);

        $this->assertSame(_USER_ID_ALREADY_EXISTS, $responseBody['errors']);

        $body = [
            'userId'    => 'test-ckent',
            'firstname' => 12, // wrong format
            'lastname'  => 'TEST-KENT',
            'mail'      => 'clark@test.zh'
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response     = $userController->create($fullRequest, new \Slim\Http\Response());
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);

        $this->assertSame('Bad Request', $responseBody['errors']);


        $GLOBALS['login'] = 'bbain';
        $userInfo          = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id']     = $userInfo['id'];

        $response     = $userController->create($fullRequest, new \Slim\Http\Response());
        $this->assertSame(403, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);

        $this->assertSame('Service forbidden', $responseBody['errors']);

        $GLOBALS['login'] = 'superadmin';
        $userInfo          = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id']     = $userInfo['id'];
    }

    public function testGetById()
    {
        $userController = new \User\controllers\UserController();

        //  READ
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        $response     = $userController->getById($request, new \Slim\Http\Response(), ['id' => self::$id]);
        $this->assertSame(200, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);

        $this->assertIsArray($responseBody);
        $this->assertNotEmpty($responseBody);

        $this->assertSame(self::$id, $responseBody['id']);
        $this->assertSame('TEST-CLARK', $responseBody['firstname']);
        $this->assertSame('TEST-KENT', $responseBody['lastname']);

        // Fail
        $response     = $userController->getById($request, new \Slim\Http\Response(), ['id' => self::$id * 1000]);
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);

        $this->assertSame('User does not exist', $responseBody['errors']);
    }

    public function testUpdate()
    {
        $userController = new \User\controllers\UserController();

        //  UPDATE
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'PUT']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);
        $aArgs = [
            'user_id'    => 'test-ckent',
            'firstname' => 'TEST-CLARK2',
            'lastname'  => 'TEST-KENT2',
            'mail'      => 'ck@dailyP.com',
            'phone'     => '0122334455',
            'initials'  => 'CK',
            'status'    => 'OK'
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($aArgs, $request);

        $response     = $userController->update($fullRequest, new \Slim\Http\Response(), ['id' => self::$id]);
        $this->assertSame(204, $response->getStatusCode());


        //  READ
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);
        $response     = $userController->getDetailledById($request, new \Slim\Http\Response(), ['id' => self::$id]);
        $responseBody = json_decode((string)$response->getBody());

        $this->assertSame(self::$id, $responseBody->id);
        $this->assertSame('test-ckent', $responseBody->user_id);
        $this->assertSame('TEST-CLARK2', $responseBody->firstname);
        $this->assertSame('TEST-KENT2', $responseBody->lastname);
        $this->assertSame('OK', $responseBody->status);
        $this->assertSame('0122334455', $responseBody->phone);
        $this->assertSame('ck@dailyP.com', $responseBody->mail);
        $this->assertSame('CK', $responseBody->initials);

        // Fail
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'PUT']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);
        $body = [
            'user_id'    => 'test-ckent',
            'firstname' => 'TEST-CLARK2',
            'lastname'  => 'TEST-KENT2',
            'mail'      => 'ck@dailyP.com',
            'phone'     => '0122334455',
            'initials'  => 'CK'
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response     = $userController->update($fullRequest, new \Slim\Http\Response(), ['id' => 'wrong format']);
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('id must be an integer', $responseBody['errors']);

        $body = [
            'user_id'    => 'test-ckent',
            'firstname' => 'TEST-CLARK2',
            'lastname'  => 'TEST-KENT2',
            'mail'      => 'ck@dailyP.com',
            'phone'     => 'wrong format',
            'initials'  => 'CK'
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response     = $userController->update($fullRequest, new \Slim\Http\Response(), ['id' => self::$id]);
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Body phone is not correct', $responseBody['errors']);
    }

    public function testAddGroup()
    {
        $userController = new \User\controllers\UserController();

        //  CREATE
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'POST']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);
        $body = [
            'groupId'   => 'AGENT',
            'role'      => 'Douche'
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response     = $userController->addGroup($fullRequest, new \Slim\Http\Response(), ['id' => self::$id]);
        $responseBody = json_decode((string)$response->getBody());

        $this->assertIsArray($responseBody->groups);
        $this->assertIsArray($responseBody->baskets);

        //  READ
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);
        $response     = $userController->getDetailledById($request, new \Slim\Http\Response(), ['id' => self::$id]);
        $responseBody = json_decode((string)$response->getBody());

        $this->assertSame(self::$id, $responseBody->id);
        $this->assertIsArray($responseBody->groups);
        $this->assertSame('AGENT', $responseBody->groups[0]->group_id);
        $this->assertSame('Douche', $responseBody->groups[0]->role);

        // Fail
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'POST']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);
        $body = [
            'role'      => 'Douche'
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response     = $userController->addGroup($fullRequest, new \Slim\Http\Response(), ['id' => self::$id * 1000]);
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('User not found', $responseBody['errors']);

        $response     = $userController->addGroup($fullRequest, new \Slim\Http\Response(), ['id' => self::$id]);
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Bad Request', $responseBody['errors']);

        $body = [
            'groupId'   => 'SECRET_AGENT',
            'role'      => 'Douche'
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response     = $userController->addGroup($fullRequest, new \Slim\Http\Response(), ['id' => self::$id]);
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Group not found', $responseBody['errors']);

        $body = [
            'groupId'   => 'AGENT',
            'role'      => 'Douche'
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response     = $userController->addGroup($fullRequest, new \Slim\Http\Response(), ['id' => self::$id]);
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame(_USER_ALREADY_LINK_GROUP, $responseBody['errors']);

        $GLOBALS['login'] = 'bbain';
        $userInfo          = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id']     = $userInfo['id'];

        $body = [
            'groupId'   => 'COURRIER',
            'role'      => 'Douche'
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response     = $userController->addGroup($fullRequest, new \Slim\Http\Response(), ['id' => self::$id]);
        $this->assertSame(403, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Service forbidden', $responseBody['errors']);

        $GLOBALS['login'] = 'superadmin';
        $userInfo          = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id']     = $userInfo['id'];
    }

    public function testUpdateGroup()
    {
        $userController = new \User\controllers\UserController();

        //  UPDATE
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'PUT']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);
        $aArgs = [
            'role'      => 'role updated'
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($aArgs, $request);

        $response     = $userController->updateGroup($fullRequest, new \Slim\Http\Response(), ['id' => self::$id, 'groupId' => 'AGENT']);
        $responseBody = json_decode((string)$response->getBody());

        $this->assertSame('success', $responseBody->success);

        //  READ
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);
        $response     = $userController->getDetailledById($request, new \Slim\Http\Response(), ['id' => self::$id]);
        $responseBody = json_decode((string)$response->getBody());

        $this->assertSame(self::$id, $responseBody->id);
        $this->assertIsArray($responseBody->groups);
        $this->assertSame('AGENT', $responseBody->groups[0]->group_id);
        $this->assertSame('role updated', $responseBody->groups[0]->role);

        // Fail
        $response     = $userController->updateGroup($fullRequest, new \Slim\Http\Response(), ['id' => self::$id * 1000]);
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('User not found', $responseBody['errors']);

        $response     = $userController->updateGroup($fullRequest, new \Slim\Http\Response(), ['id' => self::$id, 'groupId' => 'SECRET_AGENT']);
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Group not found', $responseBody['errors']);
    }

    public function testDeleteGroup()
    {
        $userController = new \User\controllers\UserController();

        //  DELETE
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'DELETE']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);
        $response     = $userController->deleteGroup($request, new \Slim\Http\Response(), ['id' => self::$id, 'groupId' => 'AGENT']);
        $responseBody = json_decode((string)$response->getBody());

        $this->assertIsArray($responseBody->groups);
        $this->assertEmpty($responseBody->groups);
        $this->assertIsArray($responseBody->baskets);

        //  READ
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);
        $response     = $userController->getDetailledById($request, new \Slim\Http\Response(), ['id' => self::$id]);
        $responseBody = json_decode((string)$response->getBody());

        $this->assertSame(self::$id, $responseBody->id);
        $this->assertIsArray($responseBody->groups);
        $this->assertEmpty($responseBody->groups);

        // Fail
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'DELETE']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        $response     = $userController->deleteGroup($request, new \Slim\Http\Response(), ['id' => self::$id * 1000]);
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('User not found', $responseBody['errors']);

        $response     = $userController->deleteGroup($request, new \Slim\Http\Response(), ['id' => self::$id, 'groupId' => 'SECRET_AGENT']);
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Group not found', $responseBody['errors']);
    }

    public function testAddEntity()
    {
        $userController = new \User\controllers\UserController();

        //  CREATE
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'POST']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);
        $body = [
            'entityId'  => 'DGS',
            'role'      => 'Warrior'
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response     = $userController->addEntity($fullRequest, new \Slim\Http\Response(), ['id' => self::$id]);
        $responseBody = json_decode((string)$response->getBody());

        $this->assertIsArray($responseBody->entities);
        $this->assertIsArray($responseBody->allEntities);

        //  CREATE
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'POST']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);
        $body = [
            'entityId'  => 'FIN',
            'role'      => 'Hunter'
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response     = $userController->addEntity($fullRequest, new \Slim\Http\Response(), ['id' => self::$id]);
        $responseBody = json_decode((string)$response->getBody());

        $this->assertIsArray($responseBody->entities);
        $this->assertIsArray($responseBody->allEntities);

        //  READ
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);
        $response     = $userController->getDetailledById($request, new \Slim\Http\Response(), ['id' => self::$id]);
        $responseBody = json_decode((string)$response->getBody());

        $this->assertSame(self::$id, $responseBody->id);
        $this->assertIsArray($responseBody->entities);
        $this->assertSame('DGS', $responseBody->entities[0]->entity_id);
        $this->assertSame('Warrior', $responseBody->entities[0]->user_role);
        $this->assertSame('Y', $responseBody->entities[0]->primary_entity);
        $this->assertSame('FIN', $responseBody->entities[1]->entity_id);
        $this->assertSame('Hunter', $responseBody->entities[1]->user_role);
        $this->assertSame('N', $responseBody->entities[1]->primary_entity);

        // Fail
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'POST']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        $body = [
            'entityId'  => 'SECRET_SERVICE',
            'role'      => 'Hunter'
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response     = $userController->addEntity($fullRequest, new \Slim\Http\Response(), ['id' => self::$id * 1000]);
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('User not found', $responseBody['errors']);

        $response     = $userController->addEntity($fullRequest, new \Slim\Http\Response(), ['id' => self::$id]);
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Entity not found', $responseBody['errors']);

        $body = [
            'entityId'  => 'FIN',
            'role'      => 'Hunter'
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response     = $userController->addEntity($fullRequest, new \Slim\Http\Response(), ['id' => self::$id]);
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame(_USER_ALREADY_LINK_ENTITY, $responseBody['errors']);

        $body = [
            'role'      => 'Hunter'
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response     = $userController->addEntity($fullRequest, new \Slim\Http\Response(), ['id' => self::$id]);
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Bad Request', $responseBody['errors']);
    }

    public function testGetEntities()
    {
        $userController = new \User\controllers\UserController();

        //  READ
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);
        $response     = $userController->getEntities($request, new \Slim\Http\Response(), ['id' => self::$id]);
        $responseBody = json_decode((string)$response->getBody(), true);

        $this->assertIsArray($responseBody['entities']);
        $this->assertSame('DGS', $responseBody['entities'][0]['entity_id']);
        $this->assertSame('Warrior', $responseBody['entities'][0]['user_role']);
        $this->assertSame('Y', $responseBody['entities'][0]['primary_entity']);
        $this->assertSame('FIN', $responseBody['entities'][1]['entity_id']);
        $this->assertSame('Hunter', $responseBody['entities'][1]['user_role']);
        $this->assertSame('N', $responseBody['entities'][1]['primary_entity']);

        // Fail

        $response     = $userController->getEntities($request, new \Slim\Http\Response(), ['id' => self::$id * 1000]);
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('User does not exist', $responseBody['errors']);
    }

    public function testUpdateEntity()
    {
        $userController = new \User\controllers\UserController();

        //  UPDATE
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'PUT']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);
        $aArgs = [

        ];
        $fullRequest = \httpRequestCustom::addContentInBody($aArgs, $request);

        $response     = $userController->updateEntity($fullRequest, new \Slim\Http\Response(), ['id' => self::$id, 'entityId' => 'DGS']);
        $responseBody = json_decode((string)$response->getBody());

        $this->assertSame('success', $responseBody->success);

        $aArgs = [
            'user_role'      => 'Rogue'
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($aArgs, $request);

        $response     = $userController->updateEntity($fullRequest, new \Slim\Http\Response(), ['id' => self::$id, 'entityId' => 'DGS']);
        $responseBody = json_decode((string)$response->getBody());

        $this->assertSame('success', $responseBody->success);

        //  READ
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);
        $response     = $userController->getDetailledById($request, new \Slim\Http\Response(), ['id' => self::$id]);
        $responseBody = json_decode((string)$response->getBody());

        $this->assertSame(self::$id, $responseBody->id);
        $this->assertIsArray($responseBody->entities);
        $this->assertSame('DGS', $responseBody->entities[0]->entity_id);
        $this->assertSame('Rogue', $responseBody->entities[0]->user_role);
        $this->assertSame('Y', $responseBody->entities[0]->primary_entity);

        // Fail
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'POST']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        $body = [
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response     = $userController->updateEntity($fullRequest, new \Slim\Http\Response(), ['id' => self::$id * 1000, 'entityId' => 'DGS']);
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('User not found', $responseBody['errors']);

        $response     = $userController->updateEntity($fullRequest, new \Slim\Http\Response(), ['id' => self::$id, 'entityId' => 'SECRET_SERVICE']);
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Entity not found', $responseBody['errors']);
    }

    public function testGetUsersById()
    {
        $entityController = new \Entity\controllers\EntityController();

        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        $entityInfo     = \Entity\models\EntityModel::getByEntityId(['entityId' => 'DGS', 'select' => ['id']]);
        $response       = $entityController->getById($request, new \Slim\Http\Response(), ['id' => $entityInfo['id']]);
        $responseBody   = json_decode((string)$response->getBody());
        $entitySerialId = $responseBody->id;

        $response     = $entityController->getUsersById($request, new \Slim\Http\Response(), ['id' => $entitySerialId]);
        $responseBody = json_decode((string)$response->getBody());

        $this->assertNotNull($responseBody->users);

        $found = false;
        foreach ($responseBody->users as $value) {
            $this->assertNotNull($value->id);
            $this->assertIsInt($value->id);
            $this->assertNotNull($value->user_id);
            $this->assertNotNull($value->firstname);
            $this->assertNotNull($value->lastname);
            $this->assertNotNull($value->labelToDisplay);
            $this->assertNotNull($value->descriptionToDisplay);

            if ($value->id == self::$id) {
                $this->assertSame('test-ckent', $value->user_id);
                $this->assertSame('TEST-CLARK2', $value->firstname);
                $this->assertSame('TEST-KENT2', $value->lastname);
                $this->assertSame($value->firstname . ' ' . $value->lastname, $value->labelToDisplay);
                $found = true;
            }
        }

        $this->assertSame(true, $found);

        //ERROR
        $response     = $entityController->getUsersById($request, new \Slim\Http\Response(), ['id' => 99989]);
        $responseBody = json_decode((string)$response->getBody());
        $this->assertSame('Entity not found', $responseBody->errors);
    }

    public function testIsDeletable()
    {
        $userController = new \User\controllers\UserController();

        //  GET
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        $response     = $userController->isDeletable($request, new \Slim\Http\Response(), ['id' => self::$id]);
        $responseBody = json_decode((string)$response->getBody());

        $this->assertSame(true, $responseBody->isDeletable);
        $this->assertIsArray($responseBody->listTemplates);
        $this->assertEmpty($responseBody->listTemplates);
        $this->assertIsArray($responseBody->listInstances);
        $this->assertEmpty($responseBody->listInstances);

        $user = \User\models\UserModel::getByLogin(['login' => 'ggrand', 'select' => ['id']]);

        $response     = $userController->isDeletable($request, new \Slim\Http\Response(), ['id' => $user['id']]);
        $this->assertSame(200, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame(true, $responseBody['isDeletable']);
        $this->assertIsArray($responseBody['listTemplates']);
        $this->assertNotEmpty($responseBody['listTemplates']);
        $this->assertIsArray($responseBody['listInstances']);
        $this->assertEmpty($responseBody['listInstances']);

        // Fail
        $response     = $userController->isDeletable($request, new \Slim\Http\Response(), ['id' => self::$id * 1000]);
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('User not found', $responseBody['errors']);
    }

    public function testIsEntityDeletable()
    {
        $userController = new \User\controllers\UserController();

        //  GET
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        $response     = $userController->isEntityDeletable($request, new \Slim\Http\Response(), ['id' => self::$id, 'entityId' => 'DGS']);
        $responseBody = json_decode((string)$response->getBody());

        $this->assertSame(false, $responseBody->hasConfidentialityInstances);
        $this->assertSame(false, $responseBody->hasListTemplates);

        // Fail
        $response     = $userController->isEntityDeletable($request, new \Slim\Http\Response(), ['id' => self::$id * 1000, 'entityId' => 'DGS']);
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('User not found', $responseBody['errors']);

        $response     = $userController->isEntityDeletable($request, new \Slim\Http\Response(), ['id' => self::$id, 'entityId' => 'SECRET_SERVICE']);
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Entity does not exist', $responseBody['errors']);
    }

    public function testUpdatePrimaryEntity()
    {
        $userController = new \User\controllers\UserController();

        //  UPDATE
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'PUT']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        $response     = $userController->updatePrimaryEntity($request, new \Slim\Http\Response(), ['id' => self::$id, 'entityId' => 'FIN']);
        $responseBody = json_decode((string)$response->getBody());

        $this->assertIsArray($responseBody->entities);

        //  READ
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);
        $response     = $userController->getDetailledById($request, new \Slim\Http\Response(), ['id' => self::$id]);
        $responseBody = json_decode((string)$response->getBody());

        $this->assertSame(self::$id, $responseBody->id);
        $this->assertIsArray($responseBody->entities);
        $this->assertSame('FIN', $responseBody->entities[0]->entity_id);
        $this->assertSame('Hunter', $responseBody->entities[0]->user_role);
        $this->assertSame('Y', $responseBody->entities[0]->primary_entity);
        $this->assertSame('DGS', $responseBody->entities[1]->entity_id);
        $this->assertSame('Rogue', $responseBody->entities[1]->user_role);
        $this->assertSame('N', $responseBody->entities[1]->primary_entity);

        // Fail
        $response     = $userController->updatePrimaryEntity($request, new \Slim\Http\Response(), ['id' => self::$id * 1000, 'entityId' => 'DGS']);
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('User not found', $responseBody['errors']);

        $response     = $userController->updatePrimaryEntity($request, new \Slim\Http\Response(), ['id' => self::$id, 'entityId' => 'SECRET_SERVICE']);
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Entity not found', $responseBody['errors']);
    }

    public function testDeleteEntity()
    {
        $userController = new \User\controllers\UserController();

        //  DELETE
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'DELETE']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        $body = [
            'mode' => 'anything_but_reaffect'
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response     = $userController->deleteEntity($fullRequest, new \Slim\Http\Response(), ['id' => self::$id, 'entityId' => 'FIN']);
        $responseBody = json_decode((string)$response->getBody());

        $this->assertIsArray($responseBody->entities);
        $this->assertIsArray($responseBody->allEntities);

        //  DELETE
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'DELETE']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        $body = [
            'mode' => 'reaffect'
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response     = $userController->deleteEntity($fullRequest, new \Slim\Http\Response(), ['id' => self::$id, 'entityId' => 'DGS']);
        $responseBody = json_decode((string)$response->getBody());

        $this->assertIsArray($responseBody->entities);
        $this->assertEmpty($responseBody->entities);
        $this->assertIsArray($responseBody->allEntities);

        //  READ
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);
        $response     = $userController->getDetailledById($request, new \Slim\Http\Response(), ['id' => self::$id]);
        $responseBody = json_decode((string)$response->getBody());

        $this->assertSame(self::$id, $responseBody->id);
        $this->assertIsArray($responseBody->entities);
        $this->assertEmpty($responseBody->entities);

        // Fail
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'DELETE']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);
        $response     = $userController->deleteEntity($request, new \Slim\Http\Response(), ['id' => self::$id * 1000, 'entityId' => 'DGS']);
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('User not found', $responseBody['errors']);

        $response     = $userController->deleteEntity($request, new \Slim\Http\Response(), ['id' => self::$id, 'entityId' => 'SECRET_ENTITY']);
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Entity not found', $responseBody['errors']);
    }

    public function testGetStatusByUserId()
    {
        $userController = new \User\controllers\UserController();

        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);
        $response     = $userController->getStatusByUserId($request, new \Slim\Http\Response(), ['userId' => 'test-ckent']);
        $this->assertSame(200, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);

        $this->assertSame('OK', $responseBody['status']);

        // Fail
        $response     = $userController->getStatusByUserId($request, new \Slim\Http\Response(), ['userId' => 'test-ckent1234']);
        $this->assertSame(200, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertNull($responseBody['status']);

        $GLOBALS['login'] = 'bbain';
        $userInfo          = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id']     = $userInfo['id'];

        $response     = $userController->getStatusByUserId($request, new \Slim\Http\Response(), ['userId' => 'test-ckent']);
        $this->assertSame(403, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Service forbidden', $responseBody['errors']);

        $GLOBALS['login'] = 'superadmin';
        $userInfo          = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id']     = $userInfo['id'];
    }

    public function testUpdateStatus()
    {
        $userController = new \User\controllers\UserController();

        //  UPDATE
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'PUT']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);
        $aArgs = [
            'status'    => 'ABS'
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($aArgs, $request);

        $response     = $userController->updateStatus($fullRequest, new \Slim\Http\Response(), ['id' => self::$id]);
        $responseBody = json_decode((string)$response->getBody());

        $this->assertSame('ABS', $responseBody->user->status);

        //  READ
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);
        $response     = $userController->getDetailledById($request, new \Slim\Http\Response(), ['id' => self::$id]);
        $responseBody = json_decode((string)$response->getBody());

        $this->assertSame(self::$id, $responseBody->id);
        $this->assertSame('ABS', $responseBody->status);

        // Fail
        $aArgs = [
            'status'    => 42 // Wrong format
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($aArgs, $request);

        $response     = $userController->updateStatus($fullRequest, new \Slim\Http\Response(), ['id' => self::$id]);
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Bad Request', $responseBody['errors']);

        $GLOBALS['login'] = 'bbain';
        $userInfo          = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id']     = $userInfo['id'];

        $aArgs = [
            'status'    => 'ABS'
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($aArgs, $request);

        $response     = $userController->updateStatus($fullRequest, new \Slim\Http\Response(), ['id' => self::$id]);
        $this->assertSame(403, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Service forbidden', $responseBody['errors']);

        $GLOBALS['login'] = 'superadmin';
        $userInfo          = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id']     = $userInfo['id'];
    }

    public function testGetStatusByUserIdAfterUpdate()
    {
        $userController = new \User\controllers\UserController();

        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);
        $response     = $userController->getStatusByUserId($request, new \Slim\Http\Response(), ['userId' => 'test-ckent']);
        $responseBody = json_decode((string)$response->getBody());

        $this->assertSame('ABS', $responseBody->status);
    }

    public function testRead()
    {
        $userController = new \User\controllers\UserController();
        $parameterController = new \Parameter\controllers\ParameterController();
        //  UPDATE
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'PUT']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        $aArgs = [
            'description'           => 'User quota',
            'param_value_int'       => 0
        ];
        $fullRequest    = \httpRequestCustom::addContentInBody($aArgs, $request);
        $parameterController->update($fullRequest, new \Slim\Http\Response(), ['id' => 'user_quota']);

        // READ in case of deactivated user_quota
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);
        $response       = $userController->get($request, new \Slim\Http\Response());
        $responseBody   = json_decode((string)$response->getBody());

        $this->assertNotNull($responseBody->users);
        $this->assertNull($responseBody->quota->userQuota);

        //  UPDATE
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'PUT']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        $aArgs = [
            'description'           => 'User quota',
            'param_value_int'       => 20
        ];
        $fullRequest    = \httpRequestCustom::addContentInBody($aArgs, $request);
        $parameterController->update($fullRequest, new \Slim\Http\Response(), ['id' => 'user_quota']);

        // READ in case of enabled user_quotat
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);
        $response       = $userController->get($request, new \Slim\Http\Response());
        $responseBody   = json_decode((string)$response->getBody());

        $this->assertNotNull($responseBody->users);
        $this->assertNotNull($responseBody->quota);
        $this->assertSame(20, $responseBody->quota->userQuota);
        $this->assertNotNull($responseBody->quota->actives);
        $this->assertIsInt($responseBody->quota->inactives);

        $aArgs = [
            'description'           => 'User quota',
            'param_value_int'       => 0
        ];
        $fullRequest    = \httpRequestCustom::addContentInBody($aArgs, $request);
        $parameterController->update($fullRequest, new \Slim\Http\Response(), ['id' => 'user_quota']);
    }

    public function testCreateEmailSignature()
    {
        $userController = new \User\controllers\UserController();

        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'POST']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        $aArgs = [
            'title'    => 'Titre email signature TU 12345',
            'htmlBody' => '<p>Body Email Signature</p>'
        ];
        $fullRequest    = \httpRequestCustom::addContentInBody($aArgs, $request);
        $response = $userController->createCurrentUserEmailSignature($fullRequest, new \Slim\Http\Response());
        $responseBody = json_decode((string)$response->getBody());
        $this->assertNotEmpty($responseBody->emailSignatures);

        $titleEmailSignature = '';
        $htmlBodyEmailSignature = '';
        foreach ($responseBody->emailSignatures as $value) {
            if ($value->title == 'Titre email signature TU 12345') {
                self::$idEmailSignature = $value->id;
                $titleEmailSignature    = $value->title;
                $htmlBodyEmailSignature = $value->html_body;
            }
        }
        $this->assertNotEmpty(self::$idEmailSignature);
        $this->assertIsInt(self::$idEmailSignature);
        $this->assertSame('Titre email signature TU 12345', $titleEmailSignature);
        $this->assertSame('<p>Body Email Signature</p>', $htmlBodyEmailSignature);

        // ERROR
        $aArgs = [
            'title'    => '',
            'htmlBody' => ''
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($aArgs, $request);

        $response     = $userController->createCurrentUserEmailSignature($fullRequest, new \Slim\Http\Response());
        $responseBody = json_decode((string)$response->getBody());

        $this->assertSame('Bad Request', $responseBody->errors);
    }

    public function testUpdateEmailSignature()
    {
        $userController = new \User\controllers\UserController();

        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'PUT']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);
        $aArgs = [
            'title'    => 'Titre email signature TU 12345 UPDATE',
            'htmlBody' => '<p>Body Email Signature UPDATE</p>'
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($aArgs, $request);

        $response     = $userController->updateCurrentUserEmailSignature($fullRequest, new \Slim\Http\Response(), ['id' => self::$idEmailSignature]);
        $responseBody = json_decode((string)$response->getBody());

        $this->assertNotEmpty($responseBody->emailSignature);
        $this->assertNotEmpty($responseBody->emailSignature->id);
        $this->assertIsInt($responseBody->emailSignature->id);
        $this->assertSame('Titre email signature TU 12345 UPDATE', $responseBody->emailSignature->title);
        $this->assertSame('<p>Body Email Signature UPDATE</p>', $responseBody->emailSignature->html_body);

        // ERROR
        $aArgs = [
            'title'    => '',
            'htmlBody' => ''
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($aArgs, $request);

        $response     = $userController->updateCurrentUserEmailSignature($fullRequest, new \Slim\Http\Response(), ['id' => self::$idEmailSignature]);
        $responseBody = json_decode((string)$response->getBody());

        $this->assertSame('Bad Request', $responseBody->errors);
    }

    public function testGetCurrentUserEmailSignatures()
    {
        $userController = new \User\controllers\UserController();

        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        //  Success
        $response     = $userController->getCurrentUserEmailSignatures($request, new \Slim\Http\Response());
        $this->assertSame(200, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);

        $this->assertIsArray($responseBody['emailSignatures']);
        $this->assertSame(self::$idEmailSignature, $responseBody['emailSignatures'][0]['id']);
        $this->assertSame('Titre email signature TU 12345 UPDATE', $responseBody['emailSignatures'][0]['label']);
    }

    public function testGetCurrentUserEmailSignatureById()
    {
        $userController = new \User\controllers\UserController();

        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        //  Success
        $response     = $userController->getCurrentUserEmailSignatureById($request, new \Slim\Http\Response(), ['id' => self::$idEmailSignature]);
        $this->assertSame(200, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);

        $this->assertIsArray($responseBody['emailSignature']);
        $this->assertSame(self::$idEmailSignature, $responseBody['emailSignature']['id']);
        $this->assertSame('Titre email signature TU 12345 UPDATE', $responseBody['emailSignature']['label']);

        // Fail
        $response     = $userController->getCurrentUserEmailSignatureById($request, new \Slim\Http\Response(), ['id' => 'wrong format']);
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Body param id is empty or not an integer', $responseBody['errors']);

        $response     = $userController->getCurrentUserEmailSignatureById($request, new \Slim\Http\Response(), ['id' => self::$idEmailSignature * 1000]);
        $this->assertSame(404, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Signature not found', $responseBody['errors']);
    }

    public function testDeleteEmailSignature()
    {
        $userController = new \User\controllers\UserController();

        //  DELETE
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'DELETE']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);
        $response       = $userController->deleteCurrentUserEmailSignature($request, new \Slim\Http\Response(), ['id' => self::$idEmailSignature]);
        $responseBody   = json_decode((string)$response->getBody());

        $this->assertNotNull($responseBody->emailSignatures);

        $titleEmailSignature = '';
        $htmlBodyEmailSignature = '';
        foreach ($responseBody->emailSignatures as $value) {
            if ($value->title == 'Titre email signature TU 12345 UPDATE') {
                // Check If Signature Really Deleted
                $titleEmailSignature    = $value->title;
                $htmlBodyEmailSignature = $value->html_body;
            }
        }
        $this->assertSame('', $titleEmailSignature);
        $this->assertSame('', $htmlBodyEmailSignature);
    }

    public function testSuspend()
    {
        $userController = new \User\controllers\UserController();

        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'PUT']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        //  Success
        $response     = $userController->suspend($request, new \Slim\Http\Response(), ['id' => self::$id]);
        $this->assertSame(204, $response->getStatusCode());

        // set status OK
        $body = [
            'status' => 'OK'
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response     = $userController->updateStatus($fullRequest, new \Slim\Http\Response(), ['id' => self::$id]);
        $responseBody = json_decode((string)$response->getBody(), true);

        $this->assertSame('OK', $responseBody['user']['status']);

        // Fail
        $response     = $userController->suspend($request, new \Slim\Http\Response(), ['id' => self::$id * 1000]);
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('User not found', $responseBody['errors']);

        $user = \User\models\UserModel::getByLogin(['login' => 'bbain', 'select' => ['id']]);

        $response     = $userController->suspend($request, new \Slim\Http\Response(), ['id' => $user['id']]);
        $this->assertSame(403, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('User is still present in listInstances', $responseBody['errors']);

        $response     = $userController->suspend($request, new \Slim\Http\Response(), ['id' => 15]);
        $this->assertSame(403, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('User is still present in listTemplates', $responseBody['errors']);
    }

    public function testUpdateCurrentUserPreferences()
    {
        $userController = new \User\controllers\UserController();

        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'PUT']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        //  Success
        $body = [
            'documentEdition' => 'onlyoffice',
            'homeGroups'      => [2, 1]
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response     = $userController->updateCurrentUserPreferences($fullRequest, new \Slim\Http\Response());
        $this->assertSame(204, $response->getStatusCode());

        // Fail
        $body = [
            'documentEdition' => 'GoogleDocs'
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response     = $userController->updateCurrentUserPreferences($fullRequest, new \Slim\Http\Response());
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Body preferences[documentEdition] is not allowed', $responseBody['errors']);
    }

    public function testAddSignature()
    {
        $userController = new \User\controllers\UserController();

        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'POST']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        //  Success
        $fileContent = file_get_contents('src/frontend/assets/noThumbnail.png');
        $encodedFile = base64_encode($fileContent);

        $body = [
            'name'   => 'signature1.png',
            'label'  => 'Signature1',
            'base64' => $encodedFile
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response     = $userController->addSignature($fullRequest, new \Slim\Http\Response(), ['id' => self::$id]);
        $this->assertSame(200, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);

        $this->assertIsArray($responseBody['signatures']);
        $this->assertNotEmpty($responseBody['signatures']);
        $this->assertSame(1, count($responseBody['signatures']));
        $this->assertIsInt($responseBody['signatures'][0]['id']);

        self::$signatureId = $responseBody['signatures'][0]['id'];

        // Fail
        $body = [

        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response     = $userController->addSignature($fullRequest, new \Slim\Http\Response(), ['id' => self::$id * 1000]);
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('User not found', $responseBody['errors']);


        $response     = $userController->addSignature($fullRequest, new \Slim\Http\Response(), ['id' => self::$id]);
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Bad Request', $responseBody['errors']);

        $fileContent = file_get_contents('test/unitTests/samples/test.txt');
        $encodedFile = base64_encode($fileContent);

        $body = [
            'name'   => 'signature1.png',
            'label'  => 'Signature1',
            'base64' => $encodedFile
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response     = $userController->addSignature($fullRequest, new \Slim\Http\Response(), ['id' => self::$id]);
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame(_WRONG_FILE_TYPE, $responseBody['errors']);

        $GLOBALS['login'] = 'bbain';
        $userInfo          = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id']     = $userInfo['id'];

        $response     = $userController->addSignature($fullRequest, new \Slim\Http\Response(), ['id' => self::$id]);
        $this->assertSame(403, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Service forbidden', $responseBody['errors']);

        $GLOBALS['login'] = 'superadmin';
        $userInfo          = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id']     = $userInfo['id'];
    }

    public function testGetImageContent()
    {
        $userController = new \User\controllers\UserController();

        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        //  Success
        $response     = $userController->getImageContent($request, new \Slim\Http\Response(), ['id' => self::$id, 'signatureId' => self::$signatureId]);
        $this->assertSame(200, $response->getStatusCode());
        $headers = $response->getHeaders();

        $this->assertSame('image/png', $headers['Content-Type'][0]);

        // Fail
        $response     = $userController->getImageContent($request, new \Slim\Http\Response(), ['id' => 'wrong format', 'signatureId' => 'wrong format']);
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Bad Request', $responseBody['errors']);

        $response     = $userController->getImageContent($request, new \Slim\Http\Response(), ['id' => self::$id * 1000, 'signatureId' => self::$signatureId]);
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('User not found', $responseBody['errors']);


        $response     = $userController->getImageContent($request, new \Slim\Http\Response(), ['id' => self::$id, 'signatureId' => self::$signatureId * 1000]);
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Signature does not exist', $responseBody['errors']);

        $GLOBALS['login'] = 'bbain';
        $userInfo          = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id']     = $userInfo['id'];

        $response     = $userController->getImageContent($request, new \Slim\Http\Response(), ['id' => self::$id, 'signatureId' => self::$signatureId]);
        $this->assertSame(403, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Service forbidden', $responseBody['errors']);

        $GLOBALS['login'] = 'superadmin';
        $userInfo          = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id']     = $userInfo['id'];
    }

    public function testUpdateSignature()
    {
        $userController = new \User\controllers\UserController();

        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'PUT']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        //  Success
        $body = [
            'label'  => 'Signature1 - UPDATED'
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response     = $userController->updateSignature($fullRequest, new \Slim\Http\Response(), ['id' => self::$id, 'signatureId' => self::$signatureId]);
        $this->assertSame(200, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);

        $this->assertIsArray($responseBody['signature']);
        $this->assertNotEmpty($responseBody['signature']);

        // Fail
        $body = [
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response     = $userController->updateSignature($fullRequest, new \Slim\Http\Response(), ['id' => self::$id * 1000, 'signatureId' => self::$signatureId]);
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('User not found', $responseBody['errors']);


        $response     = $userController->updateSignature($fullRequest, new \Slim\Http\Response(), ['id' => self::$id, 'signatureId' => self::$signatureId * 1000]);
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Bad Request', $responseBody['errors']);

        $GLOBALS['login'] = 'bbain';
        $userInfo          = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id']     = $userInfo['id'];

        $response     = $userController->updateSignature($fullRequest, new \Slim\Http\Response(), ['id' => self::$id, 'signatureId' => self::$signatureId]);
        $this->assertSame(403, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Service forbidden', $responseBody['errors']);

        $GLOBALS['login'] = 'superadmin';
        $userInfo          = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id']     = $userInfo['id'];
    }

    public function testDeleteSignature()
    {
        $userController = new \User\controllers\UserController();

        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'DELETE']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        //  Success
        $response     = $userController->deleteSignature($request, new \Slim\Http\Response(), ['id' => self::$id, 'signatureId' => self::$signatureId]);
        $this->assertSame(200, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);

        $this->assertIsArray($responseBody['signatures']);
        $this->assertEmpty($responseBody['signatures']);

        // Fail
        $response     = $userController->deleteSignature($request, new \Slim\Http\Response(), ['id' => self::$id * 1000, 'signatureId' => self::$signatureId]);
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('User not found', $responseBody['errors']);

        $GLOBALS['login'] = 'bbain';
        $userInfo          = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id']     = $userInfo['id'];

        $response     = $userController->deleteSignature($request, new \Slim\Http\Response(), ['id' => self::$id, 'signatureId' => self::$signatureId]);
        $this->assertSame(403, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Service forbidden', $responseBody['errors']);

        $GLOBALS['login'] = 'superadmin';
        $userInfo          = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id']     = $userInfo['id'];
    }

    public function testSendAccountActivationNotification()
    {
        $userController = new \User\controllers\UserController();

        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'PUT']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        //  Success
        $response     = $userController->sendAccountActivationNotification($request, new \Slim\Http\Response(), ['id' => self::$id, 'signatureId' => self::$signatureId]);
        $this->assertSame(204, $response->getStatusCode());

        // Fail
        $response     = $userController->sendAccountActivationNotification($request, new \Slim\Http\Response(), ['id' => self::$id * 1000, 'signatureId' => self::$signatureId]);
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('User not found', $responseBody['errors']);
    }

    public function testForgotPassword()
    {
        $userController = new \User\controllers\UserController();

        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'PUT']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        //  Success
        // User does not exist
        $body = [
            'login' => 'mscott'
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response     = $userController->forgotPassword($fullRequest, new \Slim\Http\Response());
        $this->assertSame(204, $response->getStatusCode());

        // User exist
        $body = [
            'login' => 'bbain'
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response     = $userController->forgotPassword($fullRequest, new \Slim\Http\Response());
        $this->assertSame(204, $response->getStatusCode());

        // Fail
        $body = [

        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response     = $userController->forgotPassword($fullRequest, new \Slim\Http\Response());
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Body login is empty', $responseBody['errors']);

        $GLOBALS['login'] = 'superadmin';
        $userInfo          = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id']     = $userInfo['id'];
    }

    public function testPasswordInitialization()
    {
        $userController = new \User\controllers\UserController();

        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'PUT']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        //  Success
        $token = \SrcCore\controllers\AuthenticationController::getJWT();
        \User\models\UserModel::update([
            'set'   => ['reset_token' => $token],
            'where' => ['id = ?'],
            'data'  => [$GLOBALS['id']]
        ]);

        $body = [
            'token'    => $token,
            'password' => 'superadmin'
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response     = $userController->passwordInitialization($fullRequest, new \Slim\Http\Response());
        $this->assertSame(204, $response->getStatusCode());

        // Fail
        $body = [

        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response     = $userController->passwordInitialization($fullRequest, new \Slim\Http\Response());
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Body token or body password is empty', $responseBody['errors']);

        $body = [
            'token'    => 'wrong token format',
            'password' => 'maarch'
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response     = $userController->passwordInitialization($fullRequest, new \Slim\Http\Response());
        $this->assertSame(403, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Invalid token', $responseBody['errors']);

        $token = [
            'exp'  => time() + 60 * \SrcCore\controllers\AuthenticationController::MAX_DURATION_TOKEN,
            'user' => ['id' => self::$id * 1000]
        ];
        $token = \Firebase\JWT\JWT::encode($token, \SrcCore\models\CoreConfigModel::getEncryptKey());

        $body = [
            'token'    => $token,
            'password' => 'maarch'
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response     = $userController->passwordInitialization($fullRequest, new \Slim\Http\Response());
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('User does not exist', $responseBody['errors']);

        $token = \SrcCore\controllers\AuthenticationController::getJWT();
        $body = [
            'token'    => $token,
            'password' => 'maarch'
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response     = $userController->passwordInitialization($fullRequest, new \Slim\Http\Response());
        $this->assertSame(403, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Invalid token', $responseBody['errors']);

        $GLOBALS['login'] = 'superadmin';
        $userInfo          = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id']     = $userInfo['id'];
    }

    public function testUpdateBasketsDisplay()
    {
        $userController = new \User\controllers\UserController();

        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'PUT']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        //  Success
        $user = \User\models\UserModel::getByLogin(['login' => 'bbain', 'select' => ['id']]);
        $body = [
            'baskets' => [
                [
                    'basketId'      => 'MyBasket',
                    'groupSerialId' => 2,
                    'allowed'       => false
                ]
            ]
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response     = $userController->updateBasketsDisplay($fullRequest, new \Slim\Http\Response(), ['id' => $user['id']]);
        $this->assertSame(200, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('success', $responseBody['success']);

        $body = [
            'baskets' => [
                [
                    'basketId'      => 'MyBasket',
                    'groupSerialId' => 2,
                    'allowed'       => true
                ]
            ]
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response     = $userController->updateBasketsDisplay($fullRequest, new \Slim\Http\Response(), ['id' => $user['id']]);
        $this->assertSame(200, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('success', $responseBody['success']);

        $response     = $userController->updateBasketsDisplay($fullRequest, new \Slim\Http\Response(), ['id' => $user['id']]);
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Preference already exists', $responseBody['errors']);

        // Fail
        $body = [

        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response     = $userController->updateBasketsDisplay($fullRequest, new \Slim\Http\Response(), ['id' => self::$id * 1000]);
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('User not found', $responseBody['errors']);

        $response     = $userController->updateBasketsDisplay($fullRequest, new \Slim\Http\Response(), ['id' => self::$id]);
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Bad Request', $responseBody['errors']);

        $body = [
            'baskets' => [
                [
                    'basketId'      => 'MyBasket',
                    'groupSerialId' => 1,
                ]
            ]
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response     = $userController->updateBasketsDisplay($fullRequest, new \Slim\Http\Response(), ['id' => self::$id]);
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Element is missing', $responseBody['errors']);

        $body = [
            'baskets' => [
                [
                    'basketId'      => 'MyBasket',
                    'groupSerialId' => 100000,
                    'allowed'       => true
                ]
            ]
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response     = $userController->updateBasketsDisplay($fullRequest, new \Slim\Http\Response(), ['id' => self::$id]);
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Group or basket does not exist', $responseBody['errors']);

        $body = [
            'baskets' => [
                [
                    'basketId'      => 'MyBasket',
                    'groupSerialId' => 1,
                    'allowed'       => true
                ]
            ]
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response     = $userController->updateBasketsDisplay($fullRequest, new \Slim\Http\Response(), ['id' => self::$id]);
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Group is not linked to this user', $responseBody['errors']);

        $body = [
            'baskets' => [
                [
                    'basketId'      => 'QualificationBasket',
                    'groupSerialId' => 2,
                    'allowed'       => true
                ]
            ]
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response     = $userController->updateBasketsDisplay($fullRequest, new \Slim\Http\Response(), ['id' => $user['id']]);
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Group is not linked to this basket', $responseBody['errors']);
    }

    public function testGetTemplates()
    {
        $userController = new \User\controllers\UserController();

        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        //  Success
        $query = [
            'target' => 'sendmail',
            'type'   => 'HTML'
        ];
        $fullRequest = $request->withQueryParams($query);

        $response     = $userController->getTemplates($fullRequest, new \Slim\Http\Response());
        $this->assertSame(200, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertIsArray($responseBody['templates']);
        $this->assertNotEmpty($responseBody['templates']);

        foreach ($responseBody['templates'] as $template) {
            $this->assertIsInt($template['id']);
            $this->assertIsString($template['label']);
            $this->assertEmpty($template['extension']);
            $this->assertEmpty($template['exists']);
            $this->assertIsString($template['target']);
            $this->assertIsString($template['attachmentType']);
        }
    }

    public function testUpdateCurrentUserBasketPreferences()
    {
        $userController = new \User\controllers\UserController();

        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'PUT']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        //  Success
        $body = [
            'color' => 'red'
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response     = $userController->updateCurrentUserBasketPreferences($fullRequest, new \Slim\Http\Response(), ['basketId' => 'MyBasket', 'groupSerialId' => 1]);
        $this->assertSame(200, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertIsArray($responseBody['userBaskets']);
        $this->assertEmpty($responseBody['userBaskets']);

        $body = [
            'color' => ''
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response     = $userController->updateCurrentUserBasketPreferences($fullRequest, new \Slim\Http\Response(), ['basketId' => 'MyBasket', 'groupSerialId' => 1]);
        $this->assertSame(200, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertIsArray($responseBody['userBaskets']);
        $this->assertEmpty($responseBody['userBaskets']);
    }

    public function testGetDetailledById()
    {
        $userController = new \User\controllers\UserController();

        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        $GLOBALS['login'] = 'bbain';
        $userInfo          = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id']     = $userInfo['id'];

        $response       = $userController->getDetailledById($request, new \Slim\Http\Response(), ['id' => self::$id]);
        $this->assertSame(403, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Service forbidden', $responseBody['errors']);

        $GLOBALS['login'] = 'bblier';
        $userInfo          = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id']     = $userInfo['id'];

        $response       = $userController->getDetailledById($request, new \Slim\Http\Response(), ['id' => self::$id]);
        $this->assertSame(200, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);

        $this->assertSame(self::$id, $responseBody['id']);
        $this->assertSame('test-ckent', $responseBody['user_id']);
        $this->assertSame('TEST-CLARK2', $responseBody['firstname']);
        $this->assertSame('TEST-KENT2', $responseBody['lastname']);
        $this->assertSame('OK', $responseBody['status']);
        $this->assertSame(null, $responseBody['phone']);
        $this->assertSame('ck@dailyP.com', $responseBody['mail']);
        $this->assertSame('CK', $responseBody['initials']);

        $GLOBALS['login'] = 'superadmin';
        $userInfo          = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id']     = $userInfo['id'];
    }

    public function testDelete()
    {
        $userController = new \User\controllers\UserController();

        //  DELETE
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'DELETE']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);
        $response       = $userController->delete($request, new \Slim\Http\Response(), ['id' => self::$id]);
        $this->assertSame(204, $response->getStatusCode());

        //  READ
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);
        $response       = $userController->getDetailledById($request, new \Slim\Http\Response(), ['id' => self::$id]);
        $responseBody   = json_decode((string)$response->getBody());

        $this->assertSame(self::$id, $responseBody->id);
        $this->assertSame('test-ckent', $responseBody->user_id);
        $this->assertSame('TEST-CLARK2', $responseBody->firstname);
        $this->assertSame('TEST-KENT2', $responseBody->lastname);
        $this->assertSame('DEL', $responseBody->status);
        $this->assertSame('0122334455', $responseBody->phone);
        $this->assertSame('ck@dailyP.com', $responseBody->mail);
        $this->assertSame('CK', $responseBody->initials);

        // Fail
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'DELETE']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);
        $response       = $userController->delete($request, new \Slim\Http\Response(), ['id' => $GLOBALS['id']]);
        $this->assertSame(403, $response->getStatusCode());
        $responseBody   = json_decode((string)$response->getBody(), true);
        $this->assertSame('Can not delete yourself', $responseBody['errors']);

        //  REAL DELETE
        \SrcCore\models\DatabaseModel::delete([
            'table' => 'users',
            'where' => ['id = ?'],
            'data'  => [self::$id]
        ]);
    }

    public function testPasswordManagement()
    {
        $userController = new \User\controllers\UserController();

        $user = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);

        //  UPDATE PASSWORD
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'PUT']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);
        $aArgs = [
            'currentPassword'   => 'superadmin',
            'newPassword'       => 'hcraam',
            'reNewPassword'     => 'hcraam'
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($aArgs, $request);

        $response     = $userController->updatePassword($fullRequest, new \Slim\Http\Response(), ['id' => $user['id']]);
        $responseBody = json_decode((string)$response->getBody());

        $this->assertSame('success', $responseBody->success);

        $checkPassword = \SrcCore\models\AuthenticationModel::authentication(['login' => $GLOBALS['login'], 'password' => 'hcraam']);

        $this->assertSame(true, $checkPassword);

        // Fail
        $aArgs = [
            'currentPassword'   => 'superadmin',
            'newPassword'       => 42, // wrong format
            'reNewPassword'     => 'hcraam'
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($aArgs, $request);

        $response     = $userController->updatePassword($fullRequest, new \Slim\Http\Response(), ['id' => $user['id']]);
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Bad Request', $responseBody['errors']);

        $GLOBALS['login'] = 'bbain';
        $userInfo          = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id']     = $userInfo['id'];

        $response     = $userController->updatePassword($fullRequest, new \Slim\Http\Response(), ['id' => $user['id']]);
        $this->assertSame(403, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Service forbidden', $responseBody['errors']);

        $GLOBALS['login'] = 'bblier';
        $userInfo          = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id']     = $userInfo['id'];

        $user = \User\models\UserModel::getByLogin(['login' => 'ggrand', 'select' => ['id']]);

        $aArgs = [
            'currentPassword'   => 'superadmin',
            'newPassword'       => 'hcraam',
            'reNewPassword'     => 'hcraam2'
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($aArgs, $request);

        $response     = $userController->updatePassword($fullRequest, new \Slim\Http\Response(), ['id' => $user['id']]);
        $this->assertSame(403, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Not allowed', $responseBody['errors']);

        // Passwords not matching
        $aArgs = [
            'currentPassword'   => 'superadmin',
            'newPassword'       => 'hcraam',
            'reNewPassword'     => 'hcraam2'
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($aArgs, $request);

        $response     = $userController->updatePassword($fullRequest, new \Slim\Http\Response(), ['id' => $GLOBALS['id']]);
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Bad Request', $responseBody['errors']);

        // wrong current password
        $aArgs = [
            'currentPassword'   => 'superadmin',
            'newPassword'       => 'hcraam',
            'reNewPassword'     => 'hcraam'
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($aArgs, $request);

        $response     = $userController->updatePassword($fullRequest, new \Slim\Http\Response(), ['id' => $GLOBALS['id']]);
        $this->assertSame(401, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame(_WRONG_PSW, $responseBody['errors']);

        $GLOBALS['login'] = 'superadmin';
        $userInfo          = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id']     = $userInfo['id'];

        //  UPDATE RESET PASSWORD
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'PUT']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);
        $aArgs = [
            'currentPassword'   => 'hcraam',
            'newPassword'       => 'superadmin',
            'reNewPassword'     => 'superadmin'
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($aArgs, $request);

        $response     = $userController->updatePassword($fullRequest, new \Slim\Http\Response(), ['id' => $GLOBALS['id']]);
        $responseBody = json_decode((string)$response->getBody());

        $this->assertSame('success', $responseBody->success);

        $checkPassword = \SrcCore\models\AuthenticationModel::authentication(['login' => $GLOBALS['login'], 'password' => 'superadmin']);

        $this->assertSame(true, $checkPassword);
    }

    public function testUpdateProfile()
    {
        $userController = new \User\controllers\UserController();

        //  UPDATE
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'PUT']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        $aArgs = [
            'firstname'     => 'Wonder',
            'lastname'      => 'User',
            'mail'          => 'dev@maarch.org',
            'initials'      => 'SU'
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($aArgs, $request);

        $response     = $userController->updateProfile($fullRequest, new \Slim\Http\Response());
        $this->assertSame(204, $response->getStatusCode());


        //  READ
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);
        $response     = $userController->getProfile($request, new \Slim\Http\Response());
        $responseBody = json_decode((string)$response->getBody(), true);

        $this->assertSame('superadmin', $responseBody['user_id']);
        $this->assertSame('Wonder', $responseBody['firstname']);
        $this->assertSame('User', $responseBody['lastname']);
        $this->assertSame('dev@maarch.org', $responseBody['mail']);
        $this->assertSame('SU', $responseBody['initials']);
        $this->assertSame('onlyoffice', $responseBody['preferences']['documentEdition']);


        //  UPDATE
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'PUT']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);
        $aArgs = [
            'firstname'     => 'Super',
            'lastname'      => 'ADMIN',
            'mail'          => 'dev@maarch.org',
            'initials'      => 'SU'
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($aArgs, $request);

        $response     = $userController->updateProfile($fullRequest, new \Slim\Http\Response());
        $this->assertSame(204, $response->getStatusCode());


        //  READ
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);
        $response     = $userController->getProfile($request, new \Slim\Http\Response());
        $responseBody = json_decode((string)$response->getBody());

        $this->assertSame('superadmin', $responseBody->user_id);
        $this->assertSame('Super', $responseBody->firstname);
        $this->assertSame('ADMIN', $responseBody->lastname);
        $this->assertSame('dev@maarch.org', $responseBody->mail);
        $this->assertSame('SU', $responseBody->initials);

        //  ERRORS
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'PUT']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);
        $aArgs = [
            'firstname'     => 'Super',
            'lastname'      => 'ADMIN',
            'initials'      => 'SU'
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($aArgs, $request);

        $response     = $userController->updateProfile($fullRequest, new \Slim\Http\Response());
        $this->assertSame(400, $response->getStatusCode());

        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Body mail is empty or not a valid email', $responseBody['errors']);

        $aArgs = [
            'firstname' => '',
            'lastname'  => 'ADMIN',
            'initials'  => 'SU'
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($aArgs, $request);

        $response     = $userController->updateProfile($fullRequest, new \Slim\Http\Response());
        $this->assertSame(400, $response->getStatusCode());

        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Body firstname is empty or not a string', $responseBody['errors']);

        $aArgs = [
            'firstname' => 'Super',
            'lastname'  => '',
            'initials'  => 'SU'
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($aArgs, $request);

        $response     = $userController->updateProfile($fullRequest, new \Slim\Http\Response());
        $this->assertSame(400, $response->getStatusCode());

        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Body lastname is empty or not a string', $responseBody['errors']);

        $aArgs = [
            'firstname' => 'Super',
            'lastname'  => 'ADMIN',
            'initials'  => 'SU',
            'mail'      => 'dev@maarch.org',
            'phone'     => 'wrong format'
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($aArgs, $request);

        $response     = $userController->updateProfile($fullRequest, new \Slim\Http\Response());
        $this->assertSame(400, $response->getStatusCode());

        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Body phone is not a valid phone number', $responseBody['errors']);
    }

    public function testSetRedirectedBasket()
    {
        $userController = new \User\controllers\UserController();

        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'POST']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);
        $body = [
            [
                'actual_user_id'    =>  21,
                'basket_id'         =>  'MyBasket',
                'group_id'          =>  2
            ]
        ];

        $user_id = \User\models\UserModel::getByLogin(['login' => 'bbain', 'select' => ['id']]);
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);
        $response     = $userController->setRedirectedBaskets($fullRequest, new \Slim\Http\Response(), ['id' => $user_id['id']]);
        $responseBody = json_decode((string)$response->getBody());
        
        $this->assertNotNull($responseBody->baskets);
        $this->assertNotNull($responseBody->redirectedBaskets);
        foreach ($responseBody->redirectedBaskets as $redirectedBasket) {
            if ($redirectedBasket->actual_user_id == 21 && $redirectedBasket->basket_id == 'MyBasket' && $redirectedBasket->group_id == 2) {
                self::$redirectId = $redirectedBasket->id;
            }
        }
        $this->assertNotNull(self::$redirectId);
        $this->assertIsInt(self::$redirectId);

        $body = [
            [
                'newUser'       =>  null,
                'basketId'      =>  'MyBasket',
                'basketOwner'   =>  'bbain',
                'virtual'       =>  'Y'
            ],
            [
                'newUser'       =>  'bblier',
                'basketId'      =>  'EenvBasket',
                'basketOwner'   =>  'bbain',
                'virtual'       =>  'Y'
            ]
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);
        $response     = $userController->setRedirectedBaskets($fullRequest, new \Slim\Http\Response(), ['id' => $user_id['id']]);
        $responseBody = json_decode((string)$response->getBody());

        $this->assertSame('Some data are empty', $responseBody->errors);

        $body = [
            [
                'actual_user_id'    =>  -1,
                'basket_id'         =>  'MyBasket',
                'group_id'          =>  2
            ]
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);
        $response     = $userController->setRedirectedBaskets($fullRequest, new \Slim\Http\Response(), ['id' => $user_id['id']]);
        $responseBody = json_decode((string)$response->getBody());

        $this->assertSame('User not found', $responseBody->errors);

        $body = [
            [
                'actual_user_id'    =>  -1,
                'basket_id'         =>  'MyBasket',
                'group_id'          =>  2
            ]
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);
        $response     = $userController->setRedirectedBaskets($fullRequest, new \Slim\Http\Response(), ['id' => $user_id['id'] * 1000]);
        $responseBody = json_decode((string)$response->getBody());

        $this->assertSame('User not found', $responseBody->errors);
    }

    public function testDeleteRedirectedBaskets()
    {
        $userController = new \User\controllers\UserController();

        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'DELETE']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        $user_id = \User\models\UserModel::getByLogin(['login' => 'bbain', 'select' => ['id']]);
       
        //DELETE MANY WITH ONE ON ERROR
        $body = [
            'redirectedBasketIds' => [ self::$redirectId, -1 ]
        ];

        $fullRequest = $request->withQueryParams($body);

        $response     = $userController->deleteRedirectedBasket($fullRequest, new \Slim\Http\Response(), ['id' => $user_id['id']]);
        $responseBody = json_decode((string)$response->getBody());

        $this->assertSame('Redirected basket out of perimeter', $responseBody->errors);

        //DELETE OK
        $GLOBALS['login'] = 'bbain';
        $userInfo          = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id']     = $userInfo['id'];

        $body = [
            'redirectedBasketIds' => [ self::$redirectId ]
        ];

        $fullRequest = $request->withQueryParams($body);

        $response  = $userController->deleteRedirectedBasket($fullRequest, new \Slim\Http\Response(), ['id' => $user_id['id']]);
        $responseBody = json_decode((string)$response->getBody());

        $this->assertNotNull($responseBody->baskets);

        $GLOBALS['login'] = 'superadmin';
        $userInfo          = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id']     = $userInfo['id'];

        //DELETE NOT OK
        $body = [
            'redirectedBasketIds' => [ -1 ]
        ];

        $fullRequest = $request->withQueryParams($body);

        $response     = $userController->deleteRedirectedBasket($fullRequest, new \Slim\Http\Response(), ['id' => $user_id['id']]);
        $responseBody = json_decode((string)$response->getBody());

        $this->assertSame('Redirected basket out of perimeter', $responseBody->errors);

        $body = [
            'redirectedBasketIds' => [ -1 ]
        ];

        $fullRequest = $request->withQueryParams($body);

        $response     = $userController->deleteRedirectedBasket($fullRequest, new \Slim\Http\Response(), ['id' => $user_id['id'] * 1000]);
        $responseBody = json_decode((string)$response->getBody());

        $this->assertSame('User not found', $responseBody->errors);

        $body = [
            'redirectedBasketIds' => 'wrong format'
        ];

        $fullRequest = $request->withQueryParams($body);

        $response     = $userController->deleteRedirectedBasket($fullRequest, new \Slim\Http\Response(), ['id' => $user_id['id']]);
        $responseBody = json_decode((string)$response->getBody());

        $this->assertSame('RedirectedBasketIds is empty or not an array', $responseBody->errors);
    }
}
