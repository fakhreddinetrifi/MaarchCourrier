<?php

/**
* Copyright Maarch since 2008 under licence GPLv3.
* See LICENCE.txt file at the root folder for more details.
* This file is part of Maarch software.
*
*/

use PHPUnit\Framework\TestCase;
use SrcCore\models\DatabaseModel;

class NoteControllerTest extends TestCase
{
    private static $noteId = null;
    private static $noteId2 = null;
    private static $resId = null;

    public function testCreate()
    {
        // GET LAST MAIL
        $getResId = DatabaseModel::select([
            'select'    => ['res_id'],
            'table'     => ['res_letterbox'],
            'order_by'  => ['res_id DESC'],
            'limit'     => 1
        ]);

        self::$resId = $getResId[0]['res_id'];

        $this->assertIsInt(self::$resId);

        $noteController = new \Note\controllers\NoteController();

        // CREATE WITH ALL DATA -> OK
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'POST']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        $aArgs = [
            'value'     => "Test d'ajout d'une note par php unit",
            'entities'  => ['COU', 'CAB'],
            'resId'     => self::$resId
        ];

        $fullRequest = \httpRequestCustom::addContentInBody($aArgs, $request);

        $response     = $noteController->create($fullRequest, new \Slim\Http\Response());
        $responseBody = json_decode((string)$response->getBody());

        self::$noteId = $responseBody->noteId;

        $this->assertIsInt(self::$noteId);

        // CREATE WITHOUT ENTITIES -> OK
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'POST']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        $aArgs = [
            'value'     => "Test d'ajout d'une note par php unit",
            'resId'     => self::$resId
        ];

        $fullRequest = \httpRequestCustom::addContentInBody($aArgs, $request);

        $response     = $noteController->create($fullRequest, new \Slim\Http\Response());
        $responseBody = json_decode((string)$response->getBody());

        self::$noteId2 = $responseBody->noteId;

        $this->assertIsInt(self::$noteId);

        // CREATE WITH NOTE_TEXT MISSING -> NOT OK
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'POST']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        $body = [
            'entities'  => ["COU", "CAB"],
            'resId'     => self::$resId
        ];

        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response     = $noteController->create($fullRequest, new \Slim\Http\Response());
        $responseBody = json_decode((string)$response->getBody());

        $this->assertSame('Body value is empty or not a string', $responseBody->errors);
    }

    public function testUpdate()
    {
        $noteController = new \Note\controllers\NoteController();

        //  Update working
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'PUT']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        $aArgs = [
            'value'      => "Test modification d'une note par php unit",
            'entities'   => ['COU', 'DGS'],
            'resId'     => self::$resId
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($aArgs, $request);

        $response     = $noteController->update($fullRequest, new \Slim\Http\Response(), ['id' => self::$noteId]);

        $this->assertSame(204, $response->getStatusCode());

        // Update fail
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'PUT']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        $aArgs = [
            'value' => '',
            'resId' => self::$resId
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($aArgs, $request);

        $response     = $noteController->update($fullRequest, new \Slim\Http\Response(), ['id' => self::$noteId]);

        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody());

        $this->assertIsString($responseBody->errors);
        $this->assertSame('Body value is empty or not a string', $responseBody->errors);
    }

    public function testGetById()
    {
        $GLOBALS['login'] = 'bblier';
        $userInfo          = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id']     = $userInfo['id'];

        $noteController = new \Note\controllers\NoteController();

        //  READ
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);
        $response     = $noteController->getById($request, new \Slim\Http\Response(), ['id' => self::$noteId]);

        $this->assertSame(200, $response->getStatusCode());

        $responseBody = json_decode((string)$response->getBody());

        $this->assertIsString($responseBody->value);
        $this->assertSame("Test modification d'une note par php unit", $responseBody->value);
        $this->assertIsArray($responseBody->entities);

        $response = $noteController->getById($request, new \Slim\Http\Response(), ['id' => 999999999]);

        $this->assertSame(403, $response->getStatusCode());

        $responseBody = json_decode((string)$response->getBody());

        $this->assertSame('Note out of perimeter', $responseBody->errors);

        $GLOBALS['login'] = 'superadmin';
        $userInfo          = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id']     = $userInfo['id'];
    }

    public function testGetByResId()
    {
        $GLOBALS['login'] = 'bblier';
        $userInfo          = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id']     = $userInfo['id'];

        $noteController = new \Note\controllers\NoteController();

        //  READ
        $environment = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request     = \Slim\Http\Request::createFromEnvironment($environment);
        $response    = $noteController->getByResId($request, new \Slim\Http\Response(), ['resId' => self::$resId]);

        $this->assertSame(200, $response->getStatusCode());

        $responseBody = json_decode((string)$response->getBody());

        $this->assertIsArray($responseBody->notes);
        $this->assertNotEmpty($responseBody->notes);

        foreach ($responseBody->notes as $value) {
            $this->assertIsInt($value->id);
            $this->assertIsInt($value->identifier);
            $this->assertIsString($value->value);
            $this->assertNotEmpty($value->value);
            $this->assertIsInt($value->user_id);
            $this->assertIsString($value->firstname);
            $this->assertNotEmpty($value->firstname);
            $this->assertIsString($value->lastname);
            $this->assertNotEmpty($value->lastname);
        }

        // ERROR
        $response    = $noteController->getByResId($request, new \Slim\Http\Response(), ['resId' => 1234859]);
        $responseBody = json_decode((string)$response->getBody());
        $this->assertSame('Document out of perimeter', $responseBody->errors);

        $GLOBALS['login'] = 'superadmin';
        $userInfo          = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id']     = $userInfo['id'];
    }

    public function testGetTemplates()
    {
        $GLOBALS['login'] = 'bblier';
        $userInfo          = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id']     = $userInfo['id'];

        $noteController = new \Note\controllers\NoteController();

        //  GET
        $environment = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request     = \Slim\Http\Request::createFromEnvironment($environment);

        $aArgs = [
            "resId" => self::$resId
        ];
        $fullRequest = $request->withQueryParams($aArgs);
        $response    = $noteController->getTemplates($fullRequest, new \Slim\Http\Response());
        $this->assertSame(200, $response->getStatusCode());

        $responseBody = json_decode((string)$response->getBody());

        $this->assertIsArray($responseBody->templates);

        foreach ($responseBody->templates as $value) {
            $this->assertNotEmpty($value->template_label);
            $this->assertNotEmpty($value->template_content);
        }

        // GET
        $response = $noteController->getTemplates($request, new \Slim\Http\Response());
        $this->assertSame(200, $response->getStatusCode());

        $responseBody = json_decode((string)$response->getBody());

        $this->assertIsArray($responseBody->templates);

        foreach ($responseBody->templates as $value) {
            $this->assertNotEmpty($value->template_label);
            $this->assertNotEmpty($value->template_content);
        }

        //  ERROR
        $aArgs = [
            "resId" => 19287
        ];
        $fullRequest = $request->withQueryParams($aArgs);
        $response     = $noteController->getTemplates($fullRequest, new \Slim\Http\Response());
        $responseBody = json_decode((string)$response->getBody());

        $this->assertSame('Document out of perimeter', $responseBody->errors);

        $GLOBALS['login'] = 'superadmin';
        $userInfo          = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id']     = $userInfo['id'];
    }

    public function testDelete()
    {
        //  DELETE
        $environment  = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'DELETE']);
        $request      = \Slim\Http\Request::createFromEnvironment($environment);

        $noteController = new \Note\controllers\NoteController();
        $response         = $noteController->delete($request, new \Slim\Http\Response(), ['id' => self::$noteId]);

        $this->assertSame(204, $response->getStatusCode());

        //  READ
        $environment  = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request      = \Slim\Http\Request::createFromEnvironment($environment);
        $response     = $noteController->getById($request, new \Slim\Http\Response(), ['id' => self::$noteId]);

        $this->assertSame(403, $response->getStatusCode());

        $responseBody = json_decode((string)$response->getBody());

        $this->assertIsString($responseBody->errors);
        $this->assertSame('Note out of perimeter', $responseBody->errors);

        // FAIL DELETE
        $noteController = new \Note\controllers\NoteController();
        $response         = $noteController->delete($request, new \Slim\Http\Response(), ['id' => self::$noteId]);
        $responseBody     = json_decode((string)$response->getBody());

        $this->assertSame('Note out of perimeter', $responseBody->errors);
        $this->assertSame(403, $response->getStatusCode());

        $noteController->delete($request, new \Slim\Http\Response(), ['id' => self::$noteId2]);
    }
}
