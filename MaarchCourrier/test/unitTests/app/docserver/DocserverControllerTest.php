<?php

/**
* Copyright Maarch since 2008 under licence GPLv3.
* See LICENCE.txt file at the root folder for more details.
* This file is part of Maarch software.
*
*/

use PHPUnit\Framework\TestCase;

class DocserverControllerTest extends TestCase
{
    private static $id = null;
    private static $pathTemplate = '/tmp/unitTestMaarchCourrier/';

    public function testGet()
    {
        $docserverController = new \Docserver\controllers\DocserverController();

        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        $response     = $docserverController->get($request, new \Slim\Http\Response());
        $responseBody = json_decode((string)$response->getBody());

        $this->assertNotEmpty($responseBody->docservers);
        $this->assertNotEmpty($responseBody->types);
    }

    public function testCreate()
    {
        $docserverController = new \Docserver\controllers\DocserverController();

        //  CREATE
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'POST']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        if (!is_dir(self::$pathTemplate)) {
            mkdir(self::$pathTemplate);
        }

        $aArgs = [
            'docserver_id'           =>  'NEW_DOCSERVER',
            'docserver_type_id'      =>  'DOC',
            'device_label'           =>  'new docserver',
            'size_limit_number'      =>  50000000000,
            'path_template'          =>  self::$pathTemplate,
            'coll_id'                =>  'letterbox_coll'
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($aArgs, $request);

        $response     = $docserverController->create($fullRequest, new \Slim\Http\Response());
        $responseBody = json_decode((string)$response->getBody());

        self::$id = $responseBody->docserver;
        $this->assertIsInt(self::$id);

        //  READ
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);
        $response       = $docserverController->getById($request, new \Slim\Http\Response(), ['id' =>  self::$id]);
        $responseBody   = json_decode((string)$response->getBody());

        $this->assertSame('NEW_DOCSERVER', $responseBody->docserver_id);

        //  CREATE
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'POST']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        $aArgs = [
            'docserver_id'           =>  'WRONG_PATH',
            'docserver_type_id'      =>  'DOC',
            'device_label'           =>  'new docserver',
            'size_limit_number'      =>  50000000000,
            'path_template'          =>  '/wrong/path/',
            'coll_id'                =>  'letterbox_coll'
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($aArgs, $request);
        $response     = $docserverController->create($fullRequest, new \Slim\Http\Response());
        $responseBody = json_decode((string)$response->getBody());
        
        $this->assertSame(_PATH_OF_DOCSERVER_UNAPPROACHABLE, $responseBody->errors);

        //  CREATE
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'POST']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        $aArgs = [
            'docserver_id'           =>  'BAD_REQUEST',
            'docserver_type_id'      =>  'DOC',
            'device_label'           =>  'new docserver',
            'size_limit_number'      =>  50000000000,
            'path_template'          =>  null,
            'coll_id'                =>  'letterbox_coll'
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($aArgs, $request);
        $response     = $docserverController->create($fullRequest, new \Slim\Http\Response());
        $responseBody = json_decode((string)$response->getBody());
        
        $this->assertSame('Bad Request', $responseBody->errors);

        //  CREATE
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'POST']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        $aArgs = [
            'docserver_id'           =>  'NEW_DOCSERVER',
            'docserver_type_id'      =>  'DOC',
            'device_label'           =>  'new docserver',
            'size_limit_number'      =>  50000000000,
            'path_template'          =>  '/var/docserversDEV/dev1804/archive_transfer/',
            'coll_id'                =>  'letterbox_coll'
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($aArgs, $request);
        $response     = $docserverController->create($fullRequest, new \Slim\Http\Response());
        $responseBody = json_decode((string)$response->getBody());
        
        $this->assertSame(_ID. ' ' . _ALREADY_EXISTS, $responseBody->errors);
    }

    public function testUpdate()
    {
        $docserverController = new \Docserver\controllers\DocserverController();

        //  UPDATE
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'PUT']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);
        $aArgs = [
            'docserver_type_id' =>  'DOC',
            'device_label'      =>  'updated docserver',
            'size_limit_number' =>  50000000000,
            'path_template'     =>  self::$pathTemplate,
            'is_readonly'       =>  true
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($aArgs, $request);
        $response     = $docserverController->update($fullRequest, new \Slim\Http\Response(), ['id' => self::$id]);
        $responseBody = json_decode((string)$response->getBody());

        $this->assertNotEmpty($responseBody->docserver);
        $this->assertSame('updated docserver', $responseBody->docserver->device_label);

        //  READ
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);
        $response       = $docserverController->getById($request, new \Slim\Http\Response(), ['id' =>  self::$id]);
        $responseBody   = json_decode((string)$response->getBody());

        $this->assertSame('updated docserver', $responseBody->device_label);

        //  UPDATE
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'PUT']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        $aArgs = [
            'docserver_type_id'      =>  'DOC',
            'device_label'           =>  'updated docserver',
            'size_limit_number'      =>  50000000000,
            'path_template'          =>  '/wrong/path/',
            'is_readonly'       =>  true
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($aArgs, $request);
        $response     = $docserverController->update($fullRequest, new \Slim\Http\Response(), ['id' => self::$id]);
        $responseBody = json_decode((string)$response->getBody());

        $this->assertSame(_PATH_OF_DOCSERVER_UNAPPROACHABLE, $responseBody->errors);

        //  UPDATE
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'PUT']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        $aArgs = [
            'docserver_type_id'      =>  'DOC',
            'device_label'           =>  'updated docserver',
            'size_limit_number'      =>  50000000000,
            'path_template'          =>  self::$pathTemplate,
            'is_readonly'       =>  true
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($aArgs, $request);
        $response     = $docserverController->update($fullRequest, new \Slim\Http\Response(), ['id' => 12345]);
        $responseBody = json_decode((string)$response->getBody());

        $this->assertSame('Docserver not found', $responseBody->errors);
    }

    public function testDelete()
    {
        $docserverController = new \Docserver\controllers\DocserverController();

        //  DELETE
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'DELETE']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);
        $response       = $docserverController->delete($request, new \Slim\Http\Response(), ['id' => self::$id]);
        $responseBody   = json_decode((string)$response->getBody());

        $this->assertsame('success', $responseBody->success);

        //  READ
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);
        $response       = $docserverController->getById($request, new \Slim\Http\Response(), ['id' =>  self::$id]);
        $responseBody   = json_decode((string)$response->getBody());

        $this->assertSame('Docserver not found', $responseBody->errors);

        //  DELETE
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'DELETE']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);
        $response       = $docserverController->delete($request, new \Slim\Http\Response(), ['id' => self::$id]);
        $responseBody   = json_decode((string)$response->getBody());

        $this->assertSame('Docserver does not exist', $responseBody->errors);

        rmdir(self::$pathTemplate);
    }

    public function testGetDocserverTypes()
    {
        $docserverTypeController = new \Docserver\controllers\DocserverTypeController();

        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        $response     = $docserverTypeController->get($request, new \Slim\Http\Response());
        $responseBody = json_decode((string)$response->getBody());

        $this->assertNotEmpty($responseBody->docserverTypes);
        foreach ($responseBody->docserverTypes as $docserverType) {
            $this->assertNotEmpty($docserverType->docserver_type_id);
            $this->assertNotEmpty($docserverType->docserver_type_label);
            $this->assertNotEmpty($docserverType->enabled);
        }
    }
}
