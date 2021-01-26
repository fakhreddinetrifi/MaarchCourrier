<?php

/**
 * Copyright Maarch since 2008 under licence GPLv3.
 * See LICENCE.txt file at the root folder for more details.
 * This file is part of Maarch software.
 *
 */

use PHPUnit\Framework\TestCase;

class ContactGroupControllerTest extends TestCase
{
    private static $id = null;
    private static $id2 = null;


    public function testCreate()
    {
        $contactGroupController = new \Contact\controllers\ContactGroupController();

        //  CREATE
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'POST']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        $body = [
            'label'             => 'Groupe petition',
            'description'       => 'Groupe de petition',
            'public'            => false
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response     = $contactGroupController->create($fullRequest, new \Slim\Http\Response());
        $this->assertSame(200, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);

        $this->assertIsInt($responseBody['contactsGroup']);
        self::$id = $responseBody['contactsGroup'];

        $GLOBALS['login'] = 'bbain';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];

        $body = [
            'label'             => 'Groupe petition 2',
            'description'       => 'Groupe de petition',
            'public'            => false
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response     = $contactGroupController->create($fullRequest, new \Slim\Http\Response());
        $this->assertSame(200, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);

        $this->assertIsInt($responseBody['contactsGroup']);
        self::$id2 = $responseBody['contactsGroup'];


        //  READ
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);
        $response       = $contactGroupController->getById($request, new \Slim\Http\Response(), ['id' => self::$id]);
        $this->assertSame(403, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Contacts group out of perimeter', $responseBody['errors']);

        $GLOBALS['login'] = 'superadmin';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];

        $response       = $contactGroupController->getById($request, new \Slim\Http\Response(), ['id' => self::$id]);
        $responseBody   = json_decode((string)$response->getBody());

        $user = \User\models\UserModel::getByLogin(['select' => ['id'], 'login' => 'superadmin']);
        $this->assertSame(self::$id, $responseBody->contactsGroup->id);
        $this->assertSame('Groupe petition', $responseBody->contactsGroup->label);
        $this->assertSame('Groupe de petition', $responseBody->contactsGroup->description);
        $this->assertSame(false, $responseBody->contactsGroup->public);
        $this->assertSame($user['id'], $responseBody->contactsGroup->owner);
        $this->assertIsString($responseBody->contactsGroup->labelledOwner);
        $this->assertIsArray($responseBody->contactsGroup->contacts);

        // Fail
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'POST']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        $body = [
            'label'             => 'Groupe petition',
            'public'            => true
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response     = $contactGroupController->create($fullRequest, new \Slim\Http\Response());
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Bad Request', $responseBody['errors']);

        $body = [
            'label'             => 'Groupe petition',
            'description'       => 'Groupe de petition',
            'public'            => true
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response     = $contactGroupController->create($fullRequest, new \Slim\Http\Response());
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame(_CONTACTS_GROUP_LABEL_ALREADY_EXISTS, $responseBody['errors']);
    }

    public function testGet()
    {
        $GLOBALS['login'] = 'bbain';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];

        $contactGroupController = new \Contact\controllers\ContactGroupController();

        //  GET
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);
        $response       = $contactGroupController->get($request, new \Slim\Http\Response());
        $responseBody   = json_decode((string)$response->getBody());

        $this->assertIsArray($responseBody->contactsGroups);
        $this->assertNotNull($responseBody->contactsGroups);

        $GLOBALS['login'] = 'superadmin';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];
    }

    public function testUpdate()
    {
        $contactGroupController = new \Contact\controllers\ContactGroupController();

        //  UPDATE
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'PUT']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        $body = [
            'label'             => 'Groupe petition updated',
            'description'       => 'Groupe de petition updated',
            'public'            => true
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response     = $contactGroupController->update($fullRequest, new \Slim\Http\Response(), ['id' => self::$id]);
        $responseBody = json_decode((string)$response->getBody());

        $this->assertSame('success', $responseBody->success);

        //  READ
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);
        $response       = $contactGroupController->getById($request, new \Slim\Http\Response(), ['id' => self::$id]);
        $responseBody   = json_decode((string)$response->getBody());

        $this->assertSame(self::$id, $responseBody->contactsGroup->id);
        $this->assertSame('Groupe petition updated', $responseBody->contactsGroup->label);
        $this->assertSame('Groupe de petition updated', $responseBody->contactsGroup->description);
        $this->assertSame(true, $responseBody->contactsGroup->public);
        $this->assertIsString($responseBody->contactsGroup->labelledOwner);
        $this->assertIsArray($responseBody->contactsGroup->contacts);

        // Fail
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'PUT']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        $body = [
            'label'             => 'Groupe petition updated',
            'description'       => 'Groupe de petition updated',
            'public'            => true
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response     = $contactGroupController->update($fullRequest, new \Slim\Http\Response(), ['id' => self::$id * 1000]);
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Contacts Group does not exist', $responseBody['errors']);

        $GLOBALS['login'] = 'bbain';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];

        $response     = $contactGroupController->update($fullRequest, new \Slim\Http\Response(), ['id' => self::$id]);
        $this->assertSame(403, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Service forbidden', $responseBody['errors']);

        $GLOBALS['login'] = 'superadmin';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];

        $body = [
            'label'             => 'Groupe petition updated',
            'public'            => true
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response     = $contactGroupController->update($fullRequest, new \Slim\Http\Response(), ['id' => self::$id]);
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Bad Request', $responseBody['errors']);

        $body = [
            'label'             => 'Groupe petition updated',
            'description'       => 'Groupe de petition 2 updated',
            'public'            => true
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response     = $contactGroupController->update($fullRequest, new \Slim\Http\Response(), ['id' => self::$id2]);
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame(_CONTACTS_GROUP_LABEL_ALREADY_EXISTS, $responseBody['errors']);
    }

    public function testAddContacts()
    {
        $contactGroupController = new \Contact\controllers\ContactGroupController();

        $contacts = \Contact\models\ContactModel::get([
            'select'    => ['id'],
            'limit'     => 1
        ]);

        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'POST']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        if (!empty($contacts[0])) {
            //  UPDATE

            $aArgs = [
                'contacts'  => [$contacts[0]['id']]
            ];
            $fullRequest = \httpRequestCustom::addContentInBody($aArgs, $request);

            $response     = $contactGroupController->addContacts($fullRequest, new \Slim\Http\Response(), ['id' => self::$id]);
            $responseBody = json_decode((string)$response->getBody());

            $this->assertSame(self::$id, $responseBody->contactsGroup->id);
            $this->assertNotEmpty($responseBody->contactsGroup);
            $this->assertNotEmpty($responseBody->contactsGroup->contacts);
            $this->assertSame($contacts[0]['id'], $responseBody->contactsGroup->contacts[0]->id);
            $this->assertSame(0, $responseBody->contactsGroup->contacts[0]->position);
            $this->assertIsString($responseBody->contactsGroup->contacts[0]->contact);
            $this->assertIsString($responseBody->contactsGroup->contacts[0]->address);
        }

        $body = [

        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response     = $contactGroupController->addContacts($fullRequest, new \Slim\Http\Response(), ['id' => self::$id * 1000]);
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Contacts Group does not exist', $responseBody['errors']);

        $GLOBALS['login'] = 'bbain';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];

        $response     = $contactGroupController->addContacts($fullRequest, new \Slim\Http\Response(), ['id' => self::$id]);
        $this->assertSame(403, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Service forbidden', $responseBody['errors']);

        $GLOBALS['login'] = 'superadmin';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];

        $response     = $contactGroupController->addContacts($fullRequest, new \Slim\Http\Response(), ['id' => self::$id]);
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Bad Request', $responseBody['errors']);
    }

    public function testDeleteContacts()
    {
        $contactGroupController = new \Contact\controllers\ContactGroupController();

        $contacts = \Contact\models\ContactModel::get([
            'select'    => ['id'],
            'limit'     => 1
        ]);

        if (!empty($contacts[0])) {
            //  UPDATE
            $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'DELETE']);
            $request        = \Slim\Http\Request::createFromEnvironment($environment);

            $response     = $contactGroupController->deleteContact($request, new \Slim\Http\Response(), ['id' => self::$id, 'contactId' => $contacts[0]['id']]);
            $responseBody = json_decode((string)$response->getBody());

            $this->assertSame('success', $responseBody->success);
        }

        //  READ
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);
        $response       = $contactGroupController->getById($request, new \Slim\Http\Response(), ['id' => self::$id]);
        $responseBody   = json_decode((string)$response->getBody());

        $user = \User\models\UserModel::getByLogin(['select' => ['id'], 'login' => 'superadmin']);
        $this->assertSame(self::$id, $responseBody->contactsGroup->id);
        $this->assertSame($user['id'], $responseBody->contactsGroup->owner);
        $this->assertIsString($responseBody->contactsGroup->labelledOwner);
        $this->assertIsArray($responseBody->contactsGroup->contacts);
        $this->assertEmpty($responseBody->contactsGroup->contacts);

        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'DELETE']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        $response     = $contactGroupController->deleteContact($request, new \Slim\Http\Response(), ['id' => self::$id * 1000]);
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Contacts Group does not exist', $responseBody['errors']);

        $GLOBALS['login'] = 'bbain';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];

        $response     = $contactGroupController->deleteContact($request, new \Slim\Http\Response(), ['id' => self::$id]);
        $this->assertSame(403, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Service forbidden', $responseBody['errors']);

        $GLOBALS['login'] = 'superadmin';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];
    }

    public function testDelete()
    {
        $contactGroupController = new \Contact\controllers\ContactGroupController();

        //  DELETE
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'DELETE']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        // Fail
        $response     = $contactGroupController->delete($request, new \Slim\Http\Response(), ['id' => self::$id * 1000]);
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Contacts Group does not exist', $responseBody['errors']);

        $GLOBALS['login'] = 'bbain';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];

        $response     = $contactGroupController->delete($request, new \Slim\Http\Response(), ['id' => self::$id]);
        $this->assertSame(403, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Service forbidden', $responseBody['errors']);

        $GLOBALS['login'] = 'superadmin';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];

        // Sucess
        $response       = $contactGroupController->delete($request, new \Slim\Http\Response(), ['id' => self::$id]);
        $responseBody   = json_decode((string)$response->getBody());

        $this->assertSame('success', $responseBody->success);

        $response       = $contactGroupController->delete($request, new \Slim\Http\Response(), ['id' => self::$id2]);
        $responseBody   = json_decode((string)$response->getBody());

        $this->assertSame('success', $responseBody->success);

        //  READ
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);
        $response       = $contactGroupController->getById($request, new \Slim\Http\Response(), ['id' => self::$id]);
        $responseBody   = json_decode((string)$response->getBody());

        $this->assertSame('Contacts group not found', $responseBody->errors);
    }
}
