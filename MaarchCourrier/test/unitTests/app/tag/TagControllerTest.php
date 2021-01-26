<?php

/**
 * Copyright Maarch since 2008 under licence GPLv3.
 * See LICENCE.txt file at the root folder for more details.
 * This file is part of Maarch software.
 *
 */

use PHPUnit\Framework\TestCase;

class TagControllerTest extends TestCase
{
    private static $id = null;
    private static $idChild = null;
    private static $idGrandChild = null;
    private static $idToMerge = null;

    public function testCreate()
    {
        $tagController = new \Tag\controllers\TagController();

        //  CREATE
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'POST']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        $body = [
            'label'    => 'TEST_LABEL_PARENT'
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response     = $tagController->create($fullRequest, new \Slim\Http\Response());
        $responseBody = json_decode((string)$response->getBody(), true);

        self::$id = $responseBody['id'];

        $this->assertSame(200, $response->getStatusCode());
        $this->assertIsInt(self::$id);

        //  READ
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);
        $response     = $tagController->getById($request, new \Slim\Http\Response(), ['id' => self::$id]);

        $this->assertSame(200, $response->getStatusCode());

        $responseBody = json_decode((string)$response->getBody(), true);

        $this->assertIsInt($responseBody['id']);
        $this->assertSame(self::$id, $responseBody['id']);
        $this->assertIsString($responseBody['label']);
        $this->assertSame('TEST_LABEL_PARENT', $responseBody['label']);

        //  ERRORS
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'POST']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        $body = [
            'label'    => ''
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response     = $tagController->create($fullRequest, new \Slim\Http\Response());
        $responseBody = json_decode((string)$response->getBody(), true);

        $this->assertSame(400, $response->getStatusCode());
        $this->assertIsString($responseBody['errors']);
        $this->assertSame('Body label is empty or not a string', $responseBody['errors']);

        $body = [
            'label'    => '1234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890'
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response     = $tagController->create($fullRequest, new \Slim\Http\Response());
        $responseBody = json_decode((string)$response->getBody(), true);

        $this->assertSame(400, $response->getStatusCode());
        $this->assertSame('Body label has more than 128 characters', $responseBody['errors']);

        $body = [
            'label'    => 'TEST_LABEL_CHILD',
            'parentId' => 'wrong format'
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response     = $tagController->create($fullRequest, new \Slim\Http\Response());
        $responseBody = json_decode((string)$response->getBody(), true);

        $this->assertSame(400, $response->getStatusCode());
        $this->assertSame('Body parentId is not an integer', $responseBody['errors']);

        $body = [
            'label'    => 'TEST_LABEL_CHILD',
            'parentId' => self::$id * 1000
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response     = $tagController->create($fullRequest, new \Slim\Http\Response());
        $responseBody = json_decode((string)$response->getBody(), true);

        $this->assertSame(400, $response->getStatusCode());
        $this->assertSame('Parent tag does not exist', $responseBody['errors']);

        $body = [
            'label'    => 'TEST_LABEL_CHILD',
            'parentId' => self::$id,
            'links'    => 'wrong format'
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response     = $tagController->create($fullRequest, new \Slim\Http\Response());
        $responseBody = json_decode((string)$response->getBody(), true);

        $this->assertSame(400, $response->getStatusCode());
        $this->assertSame('Body links is not an array', $responseBody['errors']);

        $body = [
            'label'    => 'TEST_LABEL_CHILD',
            'parentId' => self::$id,
            'links'    => ['wrong format']
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response     = $tagController->create($fullRequest, new \Slim\Http\Response());
        $responseBody = json_decode((string)$response->getBody(), true);

        $this->assertSame(400, $response->getStatusCode());
        $this->assertSame('Body links element is not an integer', $responseBody['errors']);

        $body = [
            'label'    => 'TEST_LABEL_CHILD',
            'parentId' => self::$id,
            'links'    => [self::$id * 1000]
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response     = $tagController->create($fullRequest, new \Slim\Http\Response());
        $responseBody = json_decode((string)$response->getBody(), true);

        $this->assertSame(400, $response->getStatusCode());
        $this->assertSame('Tag(s) not found', $responseBody['errors']);

        // Success create child
        $body = [
            'label'    => 'TEST_LABEL_CHILD',
            'parentId' => self::$id,
            'links'    => [self::$id]
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response     = $tagController->create($fullRequest, new \Slim\Http\Response());
        $responseBody = json_decode((string)$response->getBody(), true);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertIsInt(self::$id);
        self::$idChild = $responseBody['id'];

        //  READ
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);
        $response     = $tagController->getById($request, new \Slim\Http\Response(), ['id' => self::$idChild]);

        $this->assertSame(200, $response->getStatusCode());

        $responseBody = json_decode((string)$response->getBody(), true);

        $this->assertIsInt($responseBody['id']);
        $this->assertSame(self::$idChild, $responseBody['id']);
        $this->assertIsString($responseBody['label']);
        $this->assertSame('TEST_LABEL_CHILD', $responseBody['label']);

        $body = [
            'label'    => 'TEST_LABEL_GRAND_CHILD',
            'parentId' => self::$idChild
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response     = $tagController->create($fullRequest, new \Slim\Http\Response());
        $responseBody = json_decode((string)$response->getBody(), true);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertIsInt(self::$id);
        self::$idGrandChild = $responseBody['id'];

        $body = [
            'label'    => 'TEST_LABEL_TO_MERGE',
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response     = $tagController->create($fullRequest, new \Slim\Http\Response());
        $responseBody = json_decode((string)$response->getBody(), true);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertIsInt(self::$id);
        self::$idToMerge = $responseBody['id'];

        $GLOBALS['login'] = 'bbain';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];

        $response         = $tagController->create($fullRequest, new \Slim\Http\Response());
        $this->assertSame(403, $response->getStatusCode());
        $responseBody     = json_decode((string)$response->getBody(), true);
        $this->assertSame('Service forbidden', $responseBody['errors']);

        $GLOBALS['login'] = 'superadmin';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];
    }

    public function testGetById()
    {
        $tagController = new \Tag\controllers\TagController();

        //  READ
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);
        $response     = $tagController->getById($request, new \Slim\Http\Response(), ['id' => self::$id]);

        $this->assertSame(200, $response->getStatusCode());

        $responseBody = json_decode((string)$response->getBody(), true);

        $this->assertIsInt($responseBody['id']);
        $this->assertIsString($responseBody['label']);

        //  READ fail
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);
        $response     = $tagController->getById($request, new \Slim\Http\Response(), ['id' => 'test']);

        $this->assertSame(400, $response->getStatusCode());

        $responseBody = json_decode((string)$response->getBody(), true);

        $this->assertIsString($responseBody['errors']);
        $this->assertSame('Route id must be an integer val', $responseBody['errors']);

        $response     = $tagController->getById($request, new \Slim\Http\Response(), ['id' => self::$id * 1000]);

        $this->assertSame(404, $response->getStatusCode());

        $responseBody = json_decode((string)$response->getBody(), true);

        $this->assertIsString($responseBody['errors']);
        $this->assertSame('id not found', $responseBody['errors']);
    }

    public function testUpdate()
    {
        $tagController = new \Tag\controllers\TagController();

        //  Update working
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'PUT']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        $aArgs = [
            'label'    => 'TEST_LABEL_2'
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($aArgs, $request);

        $response     = $tagController->update($fullRequest, new \Slim\Http\Response(), ['id' => self::$id]);

        $this->assertSame(204, $response->getStatusCode());

        // Update fail
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'PUT']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        $aArgs = [
            'label'    => ''
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($aArgs, $request);

        $response     = $tagController->update($fullRequest, new \Slim\Http\Response(), ['id' => self::$id * 1000]);
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Tag does not exist', $responseBody['errors']);

        $response     = $tagController->update($fullRequest, new \Slim\Http\Response(), ['id' => self::$id]);

        $this->assertSame(400, $response->getStatusCode());

        $responseBody = json_decode((string)$response->getBody(), true);

        $this->assertIsString($responseBody['errors']);
        $this->assertSame('Body label is empty or not a string', $responseBody['errors']);

        //  Update fail
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'PUT']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);
        $response     = $tagController->update($request, new \Slim\Http\Response(), ['id' => 'test']);

        $this->assertSame(400, $response->getStatusCode());

        $responseBody = json_decode((string)$response->getBody(), true);

        $this->assertIsString($responseBody['errors']);
        $this->assertSame('Route id must be an integer val', $responseBody['errors']);

        $body = [
            'label'    => '1234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890'
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response     = $tagController->update($fullRequest, new \Slim\Http\Response(), ['id' => self::$idChild]);
        $responseBody = json_decode((string)$response->getBody(), true);

        $this->assertSame(400, $response->getStatusCode());
        $this->assertSame('Body label has more than 128 characters', $responseBody['errors']);

        $body = [
            'label'    => 'TEST_LABEL_CHILD',
            'parentId' => 'wrong format'
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response     = $tagController->update($fullRequest, new \Slim\Http\Response(), ['id' => self::$idChild]);
        $responseBody = json_decode((string)$response->getBody(), true);

        $this->assertSame(400, $response->getStatusCode());
        $this->assertSame('Body parentId is not an integer', $responseBody['errors']);

        $body = [
            'label'    => 'TEST_LABEL_CHILD',
            'parentId' => self::$id * 1000
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response     = $tagController->update($fullRequest, new \Slim\Http\Response(), ['id' => self::$idChild]);
        $responseBody = json_decode((string)$response->getBody(), true);

        $this->assertSame(400, $response->getStatusCode());
        $this->assertSame('Parent tag does not exist', $responseBody['errors']);

        $body = [
            'label'    => 'TEST_LABEL_CHILD',
            'parentId' => self::$idChild
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response     = $tagController->update($fullRequest, new \Slim\Http\Response(), ['id' => self::$idChild]);
        $responseBody = json_decode((string)$response->getBody(), true);

        $this->assertSame(400, $response->getStatusCode());
        $this->assertSame('Tag cannot be its own parent', $responseBody['errors']);

        $body = [
            'label'    => 'TEST_LABEL_CHILD',
            'parentId' => self::$idGrandChild
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response     = $tagController->update($fullRequest, new \Slim\Http\Response(), ['id' => self::$idChild]);
        $responseBody = json_decode((string)$response->getBody(), true);

        $this->assertSame(400, $response->getStatusCode());
        $this->assertSame('Parent tag cannot also be a children', $responseBody['errors']);

        $body = [
            'label'    => 'TEST_LABEL',
            'parentId' => self::$idGrandChild
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response     = $tagController->update($fullRequest, new \Slim\Http\Response(), ['id' => self::$id]);
        $responseBody = json_decode((string)$response->getBody(), true);

        $this->assertSame(400, $response->getStatusCode());
        $this->assertSame('Parent tag cannot also be a children', $responseBody['errors']);

        $body = [
            'label'    => 'TEST_LABEL_GRAND_CHILD',
            'parentId' => self::$id
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response     = $tagController->update($fullRequest, new \Slim\Http\Response(), ['id' => self::$idGrandChild]);
        $this->assertSame(204, $response->getStatusCode());

        $body = [
            'label'    => 'TEST_LABEL_GRAND_CHILD',
            'parentId' => self::$idChild
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response     = $tagController->update($fullRequest, new \Slim\Http\Response(), ['id' => self::$idGrandChild]);
        $this->assertSame(204, $response->getStatusCode());

        // Link tags
        $body = [
            'label' => 'TEST_LABEL',
            'links' => 'wrong format'
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response         = $tagController->update($fullRequest, new \Slim\Http\Response(), ['id' => self::$id]);
        $responseBody     = json_decode((string)$response->getBody(), true);

        $this->assertSame(400, $response->getStatusCode());
        $this->assertSame('Body links is not an array', $responseBody['errors']);

        $body = [
            'label' => 'TEST_LABEL',
            'links' => [self::$id]
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response         = $tagController->update($fullRequest, new \Slim\Http\Response(), ['id' => self::$id]);
        $responseBody     = json_decode((string)$response->getBody(), true);

        $this->assertSame(400, $response->getStatusCode());
        $this->assertSame('Body links contains tag', $responseBody['errors']);

        // Success
        $body = [
            'label' => 'TEST_LABEL',
            'links' => [self::$idGrandChild]
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response         = $tagController->update($fullRequest, new \Slim\Http\Response(), ['id' => self::$id]);

        $this->assertSame(204, $response->getStatusCode());

        $GLOBALS['login'] = 'bbain';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];

        $response         = $tagController->update($fullRequest, new \Slim\Http\Response(), ['id' => self::$id]);
        $this->assertSame(403, $response->getStatusCode());
        $responseBody     = json_decode((string)$response->getBody(), true);
        $this->assertSame('Service forbidden', $responseBody['errors']);

        $GLOBALS['login'] = 'superadmin';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];
    }

    public function testMerge()
    {
        $environment  = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'DELETE']);
        $request      = \Slim\Http\Request::createFromEnvironment($environment);
        $tagController = new \Tag\controllers\TagController();

        // FAIL
        $body = [
            'idMaster' => 'wrong format'
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response         = $tagController->merge($fullRequest, new \Slim\Http\Response());
        $responseBody     = json_decode((string)$response->getBody(), true);

        $this->assertSame(400, $response->getStatusCode());
        $this->assertSame('Body idMaster must be an integer val', $responseBody['errors']);

        $body = [
            'idMaster' => self::$id,
            'idMerge'  => 'wrong format'
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response         = $tagController->merge($fullRequest, new \Slim\Http\Response());
        $responseBody     = json_decode((string)$response->getBody(), true);

        $this->assertSame(400, $response->getStatusCode());
        $this->assertSame('Body idMerge must be an integer val', $responseBody['errors']);

        $body = [
            'idMaster' => 1000,
            'idMerge'  => 1000
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response         = $tagController->merge($fullRequest, new \Slim\Http\Response());
        $responseBody     = json_decode((string)$response->getBody(), true);

        $this->assertSame(400, $response->getStatusCode());
        $this->assertSame('Cannot merge tag with itself', $responseBody['errors']);

        $body = [
            'idMaster' => self::$id * 1000,
            'idMerge'  => self::$idToMerge * 1000
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response         = $tagController->merge($fullRequest, new \Slim\Http\Response());
        $responseBody     = json_decode((string)$response->getBody(), true);

        $this->assertSame(404, $response->getStatusCode());
        $this->assertSame('Master tag not found', $responseBody['errors']);

        $body = [
            'idMaster' => self::$id,
            'idMerge'  => self::$idToMerge * 1000
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response         = $tagController->merge($fullRequest, new \Slim\Http\Response());
        $responseBody     = json_decode((string)$response->getBody(), true);

        $this->assertSame(404, $response->getStatusCode());
        $this->assertSame('Merge tag not found', $responseBody['errors']);

        $body = [
            'idMaster' => self::$id,
            'idMerge'  => self::$idChild
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response         = $tagController->merge($fullRequest, new \Slim\Http\Response());
        $responseBody     = json_decode((string)$response->getBody(), true);

        $this->assertSame(400, $response->getStatusCode());
        $this->assertSame('Cannot merge tag : tag has a parent', $responseBody['errors']);

        $body = [
            'idMaster' => self::$idToMerge,
            'idMerge'  => self::$id
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response         = $tagController->merge($fullRequest, new \Slim\Http\Response());
        $responseBody     = json_decode((string)$response->getBody(), true);

        $this->assertSame(400, $response->getStatusCode());
        $this->assertSame('Cannot merge tag : tag has a child', $responseBody['errors']);

        $body = [
            'idMaster' => self::$id,
            'idMerge'  => self::$idToMerge
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);
        $response         = $tagController->merge($fullRequest, new \Slim\Http\Response());

        $this->assertSame(204, $response->getStatusCode());

        $GLOBALS['login'] = 'bbain';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];

        $response         = $tagController->merge($fullRequest, new \Slim\Http\Response());
        $this->assertSame(403, $response->getStatusCode());
        $responseBody     = json_decode((string)$response->getBody(), true);
        $this->assertSame('Service forbidden', $responseBody['errors']);

        $GLOBALS['login'] = 'superadmin';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];
    }

    public function testDelete()
    {
        $environment  = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'DELETE']);
        $request      = \Slim\Http\Request::createFromEnvironment($environment);

        // FAIL
        $tagController = new \Tag\controllers\TagController();

        $GLOBALS['login'] = 'bbain';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];

        \Tag\models\ResourceTagModel::create([
            'res_id' => $GLOBALS['resources'][0],
            'tag_id' => self::$idGrandChild
        ]);

        $response = $tagController->delete($request, new \Slim\Http\Response(), ['id' => self::$idGrandChild]);
        $this->assertSame(403, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Service forbidden', $responseBody['errors']);

        $GLOBALS['login'] = 'ddaull';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];

        $response = $tagController->delete($request, new \Slim\Http\Response(), ['id' => self::$idGrandChild]);
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame(403, $response->getStatusCode());
        $this->assertSame('Service forbidden', $responseBody['errors']);

        $GLOBALS['login'] = 'superadmin';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];

        $response         = $tagController->delete($request, new \Slim\Http\Response(), ['id' => self::$id * 1000]);
        $responseBody     = json_decode((string)$response->getBody(), true);

        $this->assertSame(400, $response->getStatusCode());
        $this->assertSame('Tag does not exist', $responseBody['errors']);

        $response     = $tagController->delete($request, new \Slim\Http\Response(), ['id' => 'test']);

        $this->assertSame(400, $response->getStatusCode());

        $responseBody = json_decode((string)$response->getBody(), true);

        $this->assertIsString($responseBody['errors']);
        $this->assertSame('Route id must be an integer val', $responseBody['errors']);

        $response = $tagController->delete($request, new \Slim\Http\Response(), ['id' => self::$id]);
        $this->assertSame(400, $response->getStatusCode());

        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertIsString($responseBody['errors']);
        $this->assertSame('Tag has children', $responseBody['errors']);

        //  Success
        $response = $tagController->delete($request, new \Slim\Http\Response(), ['id' => self::$idGrandChild]);
        $this->assertSame(204, $response->getStatusCode());

        $response = $tagController->delete($request, new \Slim\Http\Response(), ['id' => self::$idChild]);
        $this->assertSame(204, $response->getStatusCode());

        $response = $tagController->delete($request, new \Slim\Http\Response(), ['id' => self::$id]);
        $this->assertSame(204, $response->getStatusCode());

        //  READ
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);
        $response     = $tagController->getById($request, new \Slim\Http\Response(), ['id' => self::$id]);

        $this->assertSame(404, $response->getStatusCode());

        $responseBody = json_decode((string)$response->getBody(), true);

        $this->assertIsString($responseBody['errors']);
        $this->assertSame('id not found', $responseBody['errors']);
    }

    public function testGet()
    {
        $tagController = new \Tag\controllers\TagController();

        //  READ
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);
        $response     = $tagController->get($request, new \Slim\Http\Response());

        $this->assertSame(200, $response->getStatusCode());

        $responseBody = json_decode((string)$response->getBody());

        $this->assertIsArray($responseBody->tags);
        $this->assertNotEmpty($responseBody->tags);

        $tags = $responseBody->tags;

        foreach ($tags as $value) {
            $this->assertIsInt($value->id);
            $this->assertIsString($value->label);
        }
    }
}
