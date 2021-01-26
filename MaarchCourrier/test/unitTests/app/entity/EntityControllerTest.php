<?php

/**
* Copyright Maarch since 2008 under licence GPLv3.
* See LICENCE.txt file at the root folder for more details.
* This file is part of Maarch software.
*
*/

use PHPUnit\Framework\TestCase;

class EntityControllerTest extends TestCase
{
    private static $id = null;

    public function testCreate()
    {
        $GLOBALS['login'] = 'bblier';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];

        $entityController = new \Entity\controllers\EntityController();

        //  CREATE
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'POST']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        $aArgs = [
            'entity_id'         => 'TEST-ENTITY123',
            'entity_label'      => 'TEST-ENTITY123-LABEL',
            'short_label'       => 'TEST-ENTITY123-SHORTLABEL',
            'entity_type'       => 'Service',
            'email'             => 'paris@isMagic.fr',
            'adrs_1'            => '1 rue du parc des princes',
            'zipcode'           => '75016',
            'city'              => 'PARIS',
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($aArgs, $request);

        $response     = $entityController->create($fullRequest, new \Slim\Http\Response());
        $this->assertSame(200, $response->getStatusCode());

        $responseBody = json_decode((string)$response->getBody());

        $this->assertIsArray($responseBody->entities);

        $entityInfo = \Entity\models\EntityModel::getByEntityId(['entityId' => 'TEST-ENTITY123', 'select' => ['id']]);
        self::$id = $entityInfo['id'];

        //  READ
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);
        $response       = $entityController->getById($request, new \Slim\Http\Response(), ['id' => self::$id]);
        $responseBody   = json_decode((string)$response->getBody());

        $this->assertSame('TEST-ENTITY123', $responseBody->entity_id);
        $this->assertSame('TEST-ENTITY123-LABEL', $responseBody->entity_label);
        $this->assertSame('TEST-ENTITY123-SHORTLABEL', $responseBody->short_label);
        $this->assertSame('Service', $responseBody->entity_type);
        $this->assertSame('Y', $responseBody->enabled);
        $this->assertSame(null, $responseBody->parent_entity_id);

        // ERRORS

        $response     = $entityController->create($fullRequest, new \Slim\Http\Response());
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);

        $this->assertSame(_ENTITY_ID_ALREADY_EXISTS, $responseBody['errors']);

        unset($aArgs['entity_label']);
        $fullRequest = \httpRequestCustom::addContentInBody($aArgs, $request);
        $response     = $entityController->create($fullRequest, new \Slim\Http\Response());
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);

        $this->assertSame('Bad Request', $responseBody['errors']);

        unset($aArgs['entity_id']);
        $fullRequest = \httpRequestCustom::addContentInBody($aArgs, $request);
        $response     = $entityController->create($fullRequest, new \Slim\Http\Response());
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);

        $this->assertSame('Bad Request', $responseBody['errors']);

        $GLOBALS['login'] = 'ddur';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];

        $response = $entityController->create($fullRequest, new \Slim\Http\Response());
        $this->assertSame(403, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody());
        $this->assertSame('Service forbidden', $responseBody->errors);

        $GLOBALS['login'] = 'superadmin';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];
    }

    public function testGetUsersById()
    {
        $entityController = new \Entity\controllers\EntityController();

        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);
        $response     = $entityController->getUsersById($request, new \Slim\Http\Response(), ['id' => self::$id]);
        $this->assertSame(200, $response->getStatusCode());

        $responseBody = json_decode((string)$response->getBody(), true);

        $this->assertIsArray($responseBody['users']);
        $this->assertNotEmpty($responseBody['users']);
        $this->assertSame('bblier', $responseBody['users'][0]['user_id']);

        $request        = \Slim\Http\Request::createFromEnvironment($environment);
        $response     = $entityController->getUsersById($request, new \Slim\Http\Response(), ['id' => 99999999]);
        $this->assertSame(400, $response->getStatusCode());

        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Entity not found', $responseBody['errors']);
    }

    public function testUpdate()
    {
        $GLOBALS['login'] = 'bblier';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];

        $entityController = new \Entity\controllers\EntityController();

        //  UPDATE
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'PUT']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);
        $aArgs = [
            'entity_label'      => 'TEST-ENTITY123-LABEL',
            'short_label'       => 'TEST-ENTITY123-SHORTLABEL-UP',
            'entity_type'       => 'Direction',
            'email'             => 'paris@isMagic2.fr',
            'adrs_2'            => '2 rue des princes',
            'toto'              => 'toto',
            'parent_entity_id' => 'COU'
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($aArgs, $request);

        $response     = $entityController->update($fullRequest, new \Slim\Http\Response(), ['id' => 'TEST-ENTITY123']);
        $this->assertSame(200, $response->getStatusCode());

        $responseBody = json_decode((string)$response->getBody());

        $this->assertIsArray($responseBody->entities);

        //  READ
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);
        $response       = $entityController->getById($request, new \Slim\Http\Response(), ['id' => self::$id]);
        $responseBody   = json_decode((string)$response->getBody());

        $this->assertSame('TEST-ENTITY123', $responseBody->entity_id);
        $this->assertSame('TEST-ENTITY123-LABEL', $responseBody->entity_label);
        $this->assertSame('TEST-ENTITY123-SHORTLABEL-UP', $responseBody->short_label);
        $this->assertSame('Direction', $responseBody->entity_type);
        $this->assertSame('Y', $responseBody->enabled);
        $this->assertSame('COU', $responseBody->parent_entity_id);

        // test setting entity as user's primary entity when user does not have any
        \User\models\UserEntityModel::deleteUserEntity(['id' => $GLOBALS['id'], 'entityId' => 'TEST-ENTITY123']);
        \User\models\UserEntityModel::update([
            'set'   => ['primary_entity' => 'N'],
            'where' => ['user_id = ?'],
            'data'  => [$GLOBALS['id']]
        ]);
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'PUT']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);
        $aArgs = [
            'entity_label'      => 'TEST-ENTITY123-LABEL',
            'short_label'       => 'TEST-ENTITY123-SHORTLABEL-UP',
            'entity_type'       => 'Direction',
            'email'             => 'paris@isMagic2.fr',
            'adrs_2'            => '2 rue des princes',
            'toto'              => 'toto',
            'parent_entity_id'  => null
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($aArgs, $request);

        $response     = $entityController->update($fullRequest, new \Slim\Http\Response(), ['id' => 'TEST-ENTITY123']);
        $this->assertSame(200, $response->getStatusCode());

        // Errors
        $response     = $entityController->update($fullRequest, new \Slim\Http\Response(), ['id' => '12345678923456789']);
        $this->assertSame(400, $response->getStatusCode());

        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Entity not found', $responseBody['errors']);

        unset($aArgs['entity_label']);
        $fullRequest = \httpRequestCustom::addContentInBody($aArgs, $request);
        $response     = $entityController->update($fullRequest, new \Slim\Http\Response(), ['id' => 'TEST-ENTITY123']);
        $this->assertSame(400, $response->getStatusCode());

        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Bad Request', $responseBody['errors']);

        $fullRequest = \httpRequestCustom::addContentInBody($aArgs, $request);
        $response     = $entityController->update($fullRequest, new \Slim\Http\Response(), ['id' => 'CAB']);
        $this->assertSame(403, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Entity out of perimeter', $responseBody['errors']);

        \User\models\UserEntityModel::deleteUserEntity(['id' => $GLOBALS['id'], 'entityId' => 'TEST-ENTITY123']);

        \User\models\UserEntityModel::update([
            'set'   => ['primary_entity' => 'Y'],
            'where' => ['user_id = ?', 'entity_id = ?'],
            'data'  => [$GLOBALS['id'], 'COU']
        ]);

        $GLOBALS['login'] = 'superadmin';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];

        $aArgs = [
            'entity_label'     => 'TEST-ENTITY123-LABEL',
            'short_label'      => 'TEST-ENTITY123-SHORTLABEL-UP',
            'entity_type'      => 'Direction',
            'email'            => 'paris@isMagic2.fr',
            'adrs_2'           => '2 rue des princes',
            'toto'             => 'toto',
            'parent_entity_id' => 'SP'
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($aArgs, $request);
        $response     = $entityController->update($fullRequest, new \Slim\Http\Response(), ['id' => 'PJS']);
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame(_CAN_NOT_MOVE_IN_CHILD_ENTITY, $responseBody['errors']);

        $GLOBALS['login'] = 'ddur';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];

        $response = $entityController->update($fullRequest, new \Slim\Http\Response(), ['id' => 'TEST-ENTITY123']);
        $this->assertSame(403, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody());
        $this->assertSame('Service forbidden', $responseBody->errors);

        $GLOBALS['login'] = 'superadmin';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];
    }

    public function testUpdateStatus()
    {
        $entityController = new \Entity\controllers\EntityController();

        //  UPDATE
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'PUT']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);
        $aArgs = [
            'method'            => 'disable'
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($aArgs, $request);

        $response     = $entityController->updateStatus($fullRequest, new \Slim\Http\Response(), ['id' => 'TEST-ENTITY123']);
        $this->assertSame(200, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody());

        $this->assertSame('success', $responseBody->success);

        //  READ
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);
        $response       = $entityController->getById($request, new \Slim\Http\Response(), ['id' => self::$id]);
        $this->assertSame(200, $response->getStatusCode());
        $responseBody   = json_decode((string)$response->getBody());

        $this->assertSame('TEST-ENTITY123', $responseBody->entity_id);
        $this->assertSame('N', $responseBody->enabled);

        //  UPDATE
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'PUT']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);
        $aArgs = [
            'method'            => 'enable'
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($aArgs, $request);

        $response     = $entityController->updateStatus($fullRequest, new \Slim\Http\Response(), ['id' => 'TEST-ENTITY123']);
        $responseBody = json_decode((string)$response->getBody());

        $this->assertSame('success', $responseBody->success);

        // Errors
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'PUT']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);
        $fullRequest = \httpRequestCustom::addContentInBody($aArgs, $request);

        $response     = $entityController->updateStatus($fullRequest, new \Slim\Http\Response(), ['id' => 'TEST-9999999']);
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody());

        $this->assertSame('Entity not found', $responseBody->errors);


        $fullRequest = \httpRequestCustom::addContentInBody([], $request);

        $response     = $entityController->updateStatus($fullRequest, new \Slim\Http\Response(), ['id' => 'TEST-ENTITY123']);
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Bad Request', $responseBody['errors']);

        $GLOBALS['login'] = 'bblier';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];

        $response     = $entityController->updateStatus($fullRequest, new \Slim\Http\Response(), ['id' => 'PJS']);
        $this->assertSame(403, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Entity out of perimeter', $responseBody['errors']);

        $GLOBALS['login'] = 'ddur';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];

        $response = $entityController->updateStatus($fullRequest, new \Slim\Http\Response(), ['id' => 'TEST-ENTITY123']);
        $this->assertSame(403, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody());
        $this->assertSame('Service forbidden', $responseBody->errors);

        $GLOBALS['login'] = 'superadmin';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];
    }

    public function testGet()
    {
        $entityController = new \Entity\controllers\EntityController();

        //  READ
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);
        $response       = $entityController->get($request, new \Slim\Http\Response());
        $this->assertSame(200, $response->getStatusCode());
        $responseBody   = json_decode((string)$response->getBody());

        $this->assertIsArray($responseBody->entities);
        $this->assertNotNull($responseBody->entities);
    }

    public function testGetDetailledById()
    {
        $entityController = new \Entity\controllers\EntityController();

        $visaTemplateId = \Entity\models\ListTemplateModel::create([
            'title'       => 'TEMPLATE TEST',
            'description' => 'TEMPLATE TEST will be deleted when entity is deleted',
            'type'        => 'visaCircuit',
            'entity_id'   => self::$id,
            'owner'       => $GLOBALS['id']
        ]);
        \Entity\models\ListTemplateItemModel::create([
            'list_template_id' => $visaTemplateId,
            'item_id'          => $GLOBALS['id'],
            'item_type'        => 'user',
            'item_mode'        => 'sign',
            'sequence'         => 0,
        ]);
        $templateId = \Entity\models\ListTemplateModel::create([
            'title'       => 'TEMPLATE TEST',
            'description' => 'TEMPLATE TEST will be deleted when entity is deleted',
            'type'        => 'diffusionList',
            'entity_id'   => self::$id,
            'owner'       => $GLOBALS['id']
        ]);
        \Entity\models\ListTemplateItemModel::create([
            'list_template_id' => $templateId,
            'item_id'          => $GLOBALS['id'],
            'item_type'        => 'user',
            'item_mode'        => 'dest',
            'sequence'         => 0,
        ]);
        \Entity\models\ListTemplateItemModel::create([
            'list_template_id' => $templateId,
            'item_id'          => 13,
            'item_type'        => 'entity',
            'item_mode'        => 'cc',
            'sequence'         => 1,
        ]);

        //  READ
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);
        $response       = $entityController->getDetailledById($request, new \Slim\Http\Response(), ['id' => 'TEST-ENTITY123']);
        $this->assertSame(200, $response->getStatusCode());
        $responseBody   = json_decode((string)$response->getBody(), true);

        $this->assertSame('TEST-ENTITY123', $responseBody['entity']['entity_id']);
        $this->assertSame('TEST-ENTITY123-LABEL', $responseBody['entity']['entity_label']);
        $this->assertSame('TEST-ENTITY123-SHORTLABEL-UP', $responseBody['entity']['short_label']);
        $this->assertSame('Direction', $responseBody['entity']['entity_type']);
        $this->assertSame('Y', $responseBody['entity']['enabled']);
        $this->assertSame('paris@isMagic2.fr', $responseBody['entity']['email']);
        $this->assertSame('1 rue du parc des princes', $responseBody['entity']['adrs_1']);
        $this->assertSame('2 rue des princes', $responseBody['entity']['adrs_2']);
        $this->assertSame(null, $responseBody['entity']['adrs_3']);
        $this->assertSame('75016', $responseBody['entity']['zipcode']);
        $this->assertSame('PARIS', $responseBody['entity']['city']);
        $this->assertSame(null, $responseBody['entity']['parent_entity_id']);
        $this->assertIsArray($responseBody['entity']['listTemplate']);
        $this->assertNotEmpty($responseBody['entity']['listTemplate']);

        $this->assertSame($templateId, $responseBody['entity']['listTemplate']['id']);
        $this->assertSame('TEMPLATE TEST', $responseBody['entity']['listTemplate']['title']);
        $this->assertSame('TEMPLATE TEST will be deleted when entity is deleted', $responseBody['entity']['listTemplate']['description']);
        $this->assertSame('diffusionList', $responseBody['entity']['listTemplate']['type']);
        $this->assertIsArray($responseBody['entity']['listTemplate']['items']);

        $this->assertIsArray($responseBody['entity']['listTemplate']['items']['dest'][0]);
        $this->assertSame($GLOBALS['id'], $responseBody['entity']['listTemplate']['items']['dest'][0]['id']);
        $this->assertSame('user', $responseBody['entity']['listTemplate']['items']['dest'][0]['type']);
        $this->assertSame(0, $responseBody['entity']['listTemplate']['items']['dest'][0]['sequence']);
        $this->assertIsString($responseBody['entity']['listTemplate']['items']['dest'][0]['labelToDisplay']);
        $this->assertEmpty($responseBody['entity']['listTemplate']['items']['dest'][0]['descriptionToDisplay']);

        $this->assertIsArray($responseBody['entity']['listTemplate']['items']['cc'][0]);
        $this->assertSame(13, $responseBody['entity']['listTemplate']['items']['cc'][0]['id']);
        $this->assertSame('entity', $responseBody['entity']['listTemplate']['items']['cc'][0]['type']);
        $this->assertSame(1, $responseBody['entity']['listTemplate']['items']['cc'][0]['sequence']);
        $this->assertIsString($responseBody['entity']['listTemplate']['items']['cc'][0]['labelToDisplay']);
        $this->assertEmpty($responseBody['entity']['listTemplate']['items']['cc'][0]['descriptionToDisplay']);

        $this->assertIsArray($responseBody['entity']['visaCircuit']);
        $this->assertNotEmpty($responseBody['entity']['visaCircuit']);

        $this->assertSame($visaTemplateId, $responseBody['entity']['visaCircuit']['id']);
        $this->assertSame('TEMPLATE TEST', $responseBody['entity']['visaCircuit']['title']);
        $this->assertSame('TEMPLATE TEST will be deleted when entity is deleted', $responseBody['entity']['visaCircuit']['description']);
        $this->assertSame('visaCircuit', $responseBody['entity']['visaCircuit']['type']);
        $this->assertIsArray($responseBody['entity']['visaCircuit']['items']);

        $this->assertIsArray($responseBody['entity']['visaCircuit']['items'][0]);
        $this->assertSame($GLOBALS['id'], $responseBody['entity']['visaCircuit']['items'][0]['id']);
        $this->assertSame('user', $responseBody['entity']['visaCircuit']['items'][0]['type']);
        $this->assertSame('sign', $responseBody['entity']['visaCircuit']['items'][0]['mode']);
        $this->assertSame(0, $responseBody['entity']['visaCircuit']['items'][0]['sequence']);
        $this->assertIsString($responseBody['entity']['visaCircuit']['items'][0]['idToDisplay']);
        $this->assertEmpty($responseBody['entity']['visaCircuit']['items'][0]['descriptionToDisplay']);

        $this->assertSame(false, $responseBody['entity']['hasChildren']);
        $this->assertSame(0, $responseBody['entity']['documents']);
        $this->assertIsArray($responseBody['entity']['users']);
        $this->assertIsArray($responseBody['entity']['templates']);
        $this->assertSame(0, $responseBody['entity']['instances']);
        $this->assertSame(0, $responseBody['entity']['redirects']);

        // Errors
        $response     = $entityController->getDetailledById($request, new \Slim\Http\Response(), ['id' => 'SECRET-SERVICE']);
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Entity not found', $responseBody['errors']);


        $GLOBALS['login'] = 'bblier';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];

        $response     = $entityController->getDetailledById($request, new \Slim\Http\Response(), ['id' => 'PJS']);
        $this->assertSame(403, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Entity out of perimeter', $responseBody['errors']);

        $GLOBALS['login'] = 'ddur';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];

        $response = $entityController->getDetailledById($request, new \Slim\Http\Response(), ['id' => 'TEST-ENTITY123']);
        $this->assertSame(403, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody());
        $this->assertSame('Service forbidden', $responseBody->errors);

        $GLOBALS['login'] = 'superadmin';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];
    }

    public function testReassignEntity()
    {
        $entityController = new \Entity\controllers\EntityController();

        //  CREATE
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'POST']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        $aArgs = [
            'entity_id'         => 'R2-D2',
            'entity_label'      => 'TEST-ENTITY123-LABEL',
            'short_label'       => 'TEST-ENTITY123-SHORTLABEL',
            'entity_type'       => 'Service',
            'email'             => 'paris@isMagic.fr',
            'adrs_1'            => '1 rue du parc des princes',
            'zipcode'           => '75016',
            'city'              => 'PARIS',
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($aArgs, $request);

        $response     = $entityController->create($fullRequest, new \Slim\Http\Response());
        $this->assertSame(200, $response->getStatusCode());

        \User\models\UserEntityModel::deleteUserEntity(['id' => $GLOBALS['id'], 'entityId' => 'R2-D2']);

        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'PUT']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);
        $response       = $entityController->reassignEntity($request, new \Slim\Http\Response(), ['id' => 'R2-D2', 'newEntityId' => 'TEST-ENTITY123']);
        $this->assertSame(200, $response->getStatusCode());
        $responseBody   = json_decode((string)$response->getBody(), true);

        $this->assertIsArray($responseBody['entities']);

        // Errors
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'PUT']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);
        $response       = $entityController->reassignEntity($request, new \Slim\Http\Response(), ['id' => 'R2-D29999999', 'newEntityId' => 'TEST-ENTITY123']);
        $this->assertSame(400, $response->getStatusCode());
        $responseBody   = json_decode((string)$response->getBody(), true);

        $this->assertSame('Entity does not exist', $responseBody['errors']);

        $GLOBALS['login'] = 'bblier';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];

        $response     = $entityController->reassignEntity($request, new \Slim\Http\Response(), ['id' => 'PJS', 'newEntityId' => 'TEST-ENTITY123']);
        $this->assertSame(403, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Entity out of perimeter', $responseBody['errors']);

        $GLOBALS['login'] = 'ddur';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];

        $response = $entityController->reassignEntity($request, new \Slim\Http\Response(), ['id' => 'TEST-ENTITY123', 'newEntityId' => 'TEST-ENTITY123']);
        $this->assertSame(403, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody());
        $this->assertSame('Service forbidden', $responseBody->errors);

        $GLOBALS['login'] = 'superadmin';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];
    }

    public function testDelete()
    {
        $entityController = new \Entity\controllers\EntityController();

        //  DELETE
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'DELETE']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);
        $response       = $entityController->delete($request, new \Slim\Http\Response(), ['id' => 'TEST-ENTITY123']);
        $this->assertSame(200, $response->getStatusCode());
        $responseBody   = json_decode((string)$response->getBody());

        $this->assertIsArray($responseBody->entities);

        //  READ
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);
        $response       = $entityController->getById($request, new \Slim\Http\Response(), ['id' => self::$id]);
        $this->assertSame(400, $response->getStatusCode());
        $responseBody   = json_decode((string)$response->getBody());

        $this->assertSame('Entity not found', $responseBody->errors);

        // Errors
        $response     = $entityController->delete($request, new \Slim\Http\Response(), ['id' => 'TEST-ENTITY123']);
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Entity not found', $responseBody['errors']);

        $response     = $entityController->delete($request, new \Slim\Http\Response(), ['id' => 'PJS']);
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Entity is still used', $responseBody['errors']);


        $GLOBALS['login'] = 'bblier';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];

        $response     = $entityController->delete($request, new \Slim\Http\Response(), ['id' => 'PJS']);
        $this->assertSame(403, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Entity out of perimeter', $responseBody['errors']);

        $GLOBALS['login'] = 'ddur';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];

        $response = $entityController->delete($request, new \Slim\Http\Response(), ['id' => 'TEST-ENTITY123']);
        $this->assertSame(403, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody());
        $this->assertSame('Service forbidden', $responseBody->errors);

        $GLOBALS['login'] = 'superadmin';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];
    }

    public function testGetTypes()
    {
        $entityController = new \Entity\controllers\EntityController();

        //  DELETE
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);
        $response       = $entityController->getTypes($request, new \Slim\Http\Response());
        $this->assertSame(200, $response->getStatusCode());

        $responseBody   = json_decode((string)$response->getBody(), true);

        $this->assertIsArray($responseBody['types']);
        $this->assertNotEmpty($responseBody['types']);
    }
}
