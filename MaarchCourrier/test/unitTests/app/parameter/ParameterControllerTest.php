<?php

/**
* Copyright Maarch since 2008 under licence GPLv3.
* See LICENCE.txt file at the root folder for more details.
* This file is part of Maarch software.
*
*/

use PHPUnit\Framework\TestCase;

class ParameterControllerTest extends TestCase
{
    public function testCreate()
    {
        $parameterController = new \Parameter\controllers\ParameterController();

        //  CREATE
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'POST']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        $aArgs = [
            'id'                    => 'TEST-PARAMETER123',
            'description'           => 'TEST PARAMETER123 DESCRIPTION',
            'param_value_string'    => '20.12'
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($aArgs, $request);

        $response     = $parameterController->create($fullRequest, new \Slim\Http\Response());
        $responseBody = json_decode((string)$response->getBody());

        $this->assertSame('success', $responseBody->success);

        //  READ
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);
        $response       = $parameterController->getById($request, new \Slim\Http\Response(), ['id' => 'TEST-PARAMETER123']);
        $responseBody   = json_decode((string)$response->getBody());

        $this->assertSame('TEST-PARAMETER123', $responseBody->parameter->id);
        $this->assertSame('TEST PARAMETER123 DESCRIPTION', $responseBody->parameter->description);
        $this->assertSame('20.12', $responseBody->parameter->param_value_string);
    }

    public function testUpdate()
    {
        $parameterController = new \Parameter\controllers\ParameterController();

        //  UPDATE
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'PUT']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        $aArgs = [
            'description'           => 'TEST PARAMETER123 DESCRIPTION UPDATED',
            'param_value_string'    => '20.12.22'
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($aArgs, $request);

        $response     = $parameterController->update($fullRequest, new \Slim\Http\Response(), ['id' => 'TEST-PARAMETER123']);
        $this->assertSame(204, $response->getStatusCode());

        //  READ
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);
        $response       = $parameterController->getById($request, new \Slim\Http\Response(), ['id' => 'TEST-PARAMETER123']);
        $responseBody   = json_decode((string)$response->getBody());

        $this->assertSame('TEST-PARAMETER123', $responseBody->parameter->id);
        $this->assertSame('TEST PARAMETER123 DESCRIPTION UPDATED', $responseBody->parameter->description);
        $this->assertSame('20.12.22', $responseBody->parameter->param_value_string);
    }

    public function testGet()
    {
        $parameterController = new \Parameter\controllers\ParameterController();

        //  GET
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);
        $response       = $parameterController->get($request, new \Slim\Http\Response());
        $responseBody   = json_decode((string)$response->getBody());

        $this->assertIsArray($responseBody->parameters);
        $this->assertNotNull($responseBody->parameters);
    }

    public function testDelete()
    {
        $parameterController = new \Parameter\controllers\ParameterController();

        //  DELETE
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'DELETE']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);
        $response       = $parameterController->delete($request, new \Slim\Http\Response(), ['id' => 'TEST-PARAMETER123']);
        $responseBody   = json_decode((string)$response->getBody());

        $this->assertIsArray($responseBody->parameters);

        //  READ
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);
        $response       = $parameterController->getById($request, new \Slim\Http\Response(), ['id' => 'TEST-PARAMETER123']);
        $responseBody   = json_decode((string)$response->getBody());

        $this->assertSame('Parameter not found', $responseBody->errors);
    }
}
