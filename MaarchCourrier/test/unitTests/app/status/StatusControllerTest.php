<?php

/**
* Copyright Maarch since 2008 under licence GPLv3.
* See LICENCE.txt file at the root folder for more details.
* This file is part of Maarch software.
*
*/

use PHPUnit\Framework\TestCase;

class StatusControllerTest extends TestCase
{

    private static $id = null;

    public function testCreate()
    {
        $environment = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'POST']);
        $request     = \Slim\Http\Request::createFromEnvironment($environment);
        $status      = new \Status\controllers\StatusController();

        $aArgs = [
            'id'               => 'TEST',
            'label_status'     => 'TEST',
            'img_filename'     => 'fm-letter-end',
            'can_be_searched'  => 'true',
            'can_be_modified'  => '',
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($aArgs, $request);

        $response     = $status->create($fullRequest, new \Slim\Http\Response());
        $responseBody = json_decode((string)$response->getBody());

        $this->assertIsInt($responseBody->status->identifier);
        self::$id = $responseBody->status->identifier;

        unset($responseBody->status->identifier);

        $compare = [
            'id'               => 'TEST',
            'label_status'     => 'TEST',
            'is_system'        => 'N',
            'img_filename'     => 'fm-letter-end',
            'maarch_module'    => 'apps',
            'can_be_searched'  => 'Y',
            'can_be_modified'  => 'N',
        ];

        $aCompare = json_decode(json_encode($compare), false);
        $this->assertEqualsCanonicalizing($aCompare, $responseBody->status);

        ########## CREATE FAIL ##########
        $request = \Slim\Http\Request::createFromEnvironment($environment);
        $aArgs = [
            'id'               => 'TEST',
            'label_status'     => 'TEST',
            'img_filename'     => 'fm-letter-end',
        ];
        $fullRequest  = \httpRequestCustom::addContentInBody($aArgs, $request);

        $response     = $status->create($fullRequest, new \Slim\Http\Response());
        $responseBody = json_decode((string)$response->getBody());

        $this->assertSame(_ID . ' TEST ' . _ALREADY_EXISTS, $responseBody->errors[0]);

        ########## CREATE FAIL 2 ##########
        $request = \Slim\Http\Request::createFromEnvironment($environment);
        $aArgs = [
            'id'               => 'papa',
            'label_status'     => '',
            'img_filename'     => 'fm-letter-end',
        ];
        $fullRequest  = \httpRequestCustom::addContentInBody($aArgs, $request);

        $response     = $status->create($fullRequest, new \Slim\Http\Response());
        $responseBody = json_decode((string)$response->getBody());

        $this->assertSame('Invalid label_status value', $responseBody->errors[0]);
    }

    public function testGetById()
    {
        $environment = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request     = \Slim\Http\Request::createFromEnvironment($environment);
        $status      = new \Status\controllers\StatusController();

        $response  = $status->getById($request, new \Slim\Http\Response(), ['id' => 'TEST']);
        $responseBody = json_decode((string)$response->getBody());

        $this->assertNotEmpty($responseBody->status);
        $this->assertSame('TEST', $responseBody->status->id);

        // ERROR
        $response  = $status->getById($request, new \Slim\Http\Response(), ['id' => 'NOTFOUNDSTATUS']);
        $responseBody = json_decode((string)$response->getBody());

        $this->assertSame('id not found', $responseBody->errors);
    }

    public function testGetListUpdateDelete()
    {
        ########## GET LIST ##########
        $environment = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request     = \Slim\Http\Request::createFromEnvironment($environment);
        $status      = new \Status\controllers\StatusController();

        $response  = $status->get($request, new \Slim\Http\Response());

        $responseBody = json_decode((string)$response->getBody());
        $this->assertNotNull($responseBody->statuses);

        foreach ($responseBody->statuses as $value) {
            $this->assertIsInt($value->identifier);
        }

        ########## GETBYIDENTIFIER ##########
        $response     = $status->getByIdentifier($request, new \Slim\Http\Response(), ['identifier' => self::$id]);
        $responseBody = json_decode((string)$response->getBody());

        $this->assertNotNull($responseBody->status);
        $this->assertNotNull($responseBody->statusImages);

        $compare = [
            'identifier'       => self::$id,
            'id'               => 'TEST',
            'label_status'     => 'TEST',
            'is_system'        => 'N',
            'img_filename'     => 'fm-letter-end',
            'maarch_module'    => 'apps',
            'can_be_searched'  => 'Y',
            'can_be_modified'  => 'N',
        ];

        $aCompare = json_decode(json_encode($compare), false);
        $this->assertEqualsCanonicalizing($aCompare, $responseBody->status[0]);

        ########## GETBYIDENTIFIER FAIL ##########
        $response     = $status->getByIdentifier($request, new \Slim\Http\Response(), ['identifier' => -1]);
        $responseBody = json_decode((string)$response->getBody());

        $this->assertSame('identifier not found', $responseBody->errors);


        ########## UPDATE ##########
        $environment = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'PUT']);
        $request     = \Slim\Http\Request::createFromEnvironment($environment);

        $aArgs = [
            'id'           => 'TEST',
            'label_status' => 'TEST AFTER UP',
            'img_filename' => 'fm-letter-end',
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($aArgs, $request);

        $response = $status->update($fullRequest, new \Slim\Http\Response(), ['identifier' => self::$id]);

        $responseBody = json_decode((string)$response->getBody());

        $compare = [
            'identifier'       => self::$id,
            'id'               => 'TEST',
            'label_status'     => 'TEST AFTER UP',
            'is_system'        => 'N',
            'img_filename'     => 'fm-letter-end',
            'maarch_module'    => 'apps',
            'can_be_searched'  => 'Y',
            'can_be_modified'  => 'N',
        ];

        $aCompare = json_decode(json_encode($compare), false);

        $this->assertEqualsCanonicalizing($aCompare, $responseBody->status);

        ########## UPDATE FAIL ##########
        $request = \Slim\Http\Request::createFromEnvironment($environment);
        $aArgs = [
            'id'           => 'PZOEIRUTY',
            'label_status' => 'TEST AFTER UP',
            'img_filename' => 'fm-letter-end',
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($aArgs, $request);

        $response = $status->update($fullRequest, new \Slim\Http\Response(), ['identifier' => -1]);

        $responseBody = json_decode((string)$response->getBody());
        $this->assertSame('-1 ' . _NOT_EXISTS, $responseBody->errors[0]);


        ########## DELETE ##########
        $environment = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'DELETE']);
        $request     = \Slim\Http\Request::createFromEnvironment($environment);

        $response = $status->delete($request, new \Slim\Http\Response(), ['identifier'=> self::$id]);

        $this->assertRegexp('/statuses/', (string)$response->getBody());
    }

    public function testGetNewInformations()
    {
        $environment = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request     = \Slim\Http\Request::createFromEnvironment($environment);
        $status      = new \Status\controllers\StatusController();

        $response = $status->getNewInformations($request, new \Slim\Http\Response());

        $responseBody = json_decode((string)$response->getBody());

        $this->assertNotNull($responseBody->statusImages);
    }
}
