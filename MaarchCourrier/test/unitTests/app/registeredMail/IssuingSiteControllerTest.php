<?php

/**
 * Copyright Maarch since 2008 under licence GPLv3.
 * See LICENCE.txt file at the root folder for more details.
 * This file is part of Maarch software.
 *
 */

use PHPUnit\Framework\TestCase;

class IssuingSiteControllerTest extends TestCase
{
    private static $id = null;

    public function testCreate()
    {
        $issuingSiteController = new \RegisteredMail\controllers\IssuingSiteController();

        //  CREATE
        $environment = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'POST']);
        $request = \Slim\Http\Request::createFromEnvironment($environment);

        $body = [
            'label'              => 'Scranton',
            'postOfficeLabel'    => 'Scranton Post Office',
            'accountNumber'      => 42,
            'addressNumber'      => '1725',
            'addressStreet'      => 'Slough Avenue',
            'addressAdditional1' => null,
            'addressAdditional2' => null,
            'addressPostcode'    => '18505',
            'addressTown'        => 'Scranton',
            'addressCountry'     => 'USA',
            'entities'           => [6]
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response = $issuingSiteController->create($fullRequest, new \Slim\Http\Response());
        $this->assertSame(200, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertIsInt($responseBody['id']);

        self::$id = $responseBody['id'];

        //  READ
        $environment = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request = \Slim\Http\Request::createFromEnvironment($environment);
        $response = $issuingSiteController->getById($request, new \Slim\Http\Response(), ['id' => self::$id]);
        $this->assertSame(200, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);

        $this->assertNotEmpty($responseBody['site']);
        $this->assertSame(self::$id, $responseBody['site']['id']);
        $this->assertSame('Scranton', $responseBody['site']['label']);
        $this->assertSame('Scranton Post Office', $responseBody['site']['postOfficeLabel']);
        $this->assertSame(42, $responseBody['site']['accountNumber']);
        $this->assertSame('1725', $responseBody['site']['addressNumber']);
        $this->assertSame('Slough Avenue', $responseBody['site']['addressStreet']);
        $this->assertEmpty($responseBody['site']['addressAdditional1']);
        $this->assertEmpty($responseBody['site']['addressAdditional2']);
        $this->assertSame('18505', $responseBody['site']['addressPostcode']);
        $this->assertSame('Scranton', $responseBody['site']['addressTown']);
        $this->assertSame('USA', $responseBody['site']['addressCountry']);
        $this->assertNotEmpty($responseBody['site']['entities']);
        $this->assertSame(1, count($responseBody['site']['entities']));
        $this->assertSame(6, $responseBody['site']['entities'][0]);

        // fail
        $environment = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'POST']);
        $request = \Slim\Http\Request::createFromEnvironment($environment);

        $body = [
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response = $issuingSiteController->create($fullRequest, new \Slim\Http\Response());
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Body label is empty or not a string', $responseBody['errors']);

        $body = [
            'label'              => 'Scranton',
            'postOfficeLabel'    => 'Scranton Post Office',
            'accountNumber'      => 43,
            'addressNumber'      => '1725',
            'addressStreet'      => 'Slough Avenue',
            'addressAdditional1' => null,
            'addressAdditional2' => null,
            'addressPostcode'    => '18505',
            'addressTown'        => 'Scranton',
            'addressCountry'     => 'USA',
            'entities'           => 'toto'
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response = $issuingSiteController->create($fullRequest, new \Slim\Http\Response());
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Body entities is not an array', $responseBody['errors']);

        $body = [
            'label'              => 'Scranton',
            'postOfficeLabel'    => 'Scranton Post Office',
            'accountNumber'      => 43,
            'addressNumber'      => '1725',
            'addressStreet'      => 'Slough Avenue',
            'addressAdditional1' => null,
            'addressAdditional2' => null,
            'addressPostcode'    => '18505',
            'addressTown'        => 'Scranton',
            'addressCountry'     => 'USA',
            'entities'           => ['toto']
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response = $issuingSiteController->create($fullRequest, new \Slim\Http\Response());
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Body entities[0] is not an integer', $responseBody['errors']);

        $GLOBALS['login'] = 'bbain';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];

        $body = [
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response = $issuingSiteController->create($fullRequest, new \Slim\Http\Response());
        $this->assertSame(403, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);

        $this->assertSame('Service forbidden', $responseBody['errors']);

        $GLOBALS['login'] = 'superadmin';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];
    }

    public function testGet()
    {
        $issuingSiteController = new \RegisteredMail\controllers\IssuingSiteController();

        //  GET
        $environment = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request = \Slim\Http\Request::createFromEnvironment($environment);
        $response = $issuingSiteController->get($request, new \Slim\Http\Response());
        $responseBody = json_decode((string)$response->getBody(), true);

        $this->assertIsArray($responseBody['sites']);
        $this->assertNotEmpty($responseBody['sites']);

        $this->assertIsArray($responseBody['sites'][1]);
        $this->assertNotEmpty($responseBody['sites'][1]);

        $this->assertSame(self::$id, $responseBody['sites'][1]['id']);
        $this->assertSame('Scranton', $responseBody['sites'][1]['label']);
        $this->assertSame('Scranton Post Office', $responseBody['sites'][1]['postOfficeLabel']);
        $this->assertSame(42, $responseBody['sites'][1]['accountNumber']);
        $this->assertSame('1725', $responseBody['sites'][1]['addressNumber']);
        $this->assertSame('Slough Avenue', $responseBody['sites'][1]['addressStreet']);
        $this->assertEmpty($responseBody['sites'][1]['addressAdditional1']);
        $this->assertEmpty($responseBody['sites'][1]['addressAdditional2']);
        $this->assertSame('18505', $responseBody['sites'][1]['addressPostcode']);
        $this->assertSame('Scranton', $responseBody['sites'][1]['addressTown']);
        $this->assertSame('USA', $responseBody['sites'][1]['addressCountry']);
    }

    public function testGetById()
    {
        $issuingSiteController = new \RegisteredMail\controllers\IssuingSiteController();

        //  GET
        $environment = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request = \Slim\Http\Request::createFromEnvironment($environment);
        $response = $issuingSiteController->getById($request, new \Slim\Http\Response(), ['id' => self::$id]);
        $responseBody = json_decode((string)$response->getBody(), true);

        $this->assertIsArray($responseBody['site']);
        $this->assertNotEmpty($responseBody['site']);

        $this->assertSame(self::$id, $responseBody['site']['id']);
        $this->assertSame('Scranton', $responseBody['site']['label']);
        $this->assertSame('Scranton Post Office', $responseBody['site']['postOfficeLabel']);
        $this->assertSame(42, $responseBody['site']['accountNumber']);
        $this->assertSame('1725', $responseBody['site']['addressNumber']);
        $this->assertSame('Slough Avenue', $responseBody['site']['addressStreet']);
        $this->assertEmpty($responseBody['site']['addressAdditional1']);
        $this->assertEmpty($responseBody['site']['addressAdditional2']);
        $this->assertSame('18505', $responseBody['site']['addressPostcode']);
        $this->assertSame('Scranton', $responseBody['site']['addressTown']);
        $this->assertSame('USA', $responseBody['site']['addressCountry']);
        $this->assertNotEmpty($responseBody['site']['entities']);
        $this->assertSame(1, count($responseBody['site']['entities']));
        $this->assertSame(6, $responseBody['site']['entities'][0]);
    }

    public function testUpdate()
    {
        $issuingSiteController = new \RegisteredMail\controllers\IssuingSiteController();

        //  UPDATE
        $environment = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'PUT']);
        $request = \Slim\Http\Request::createFromEnvironment($environment);

        $body = [
            'label'              => 'Scranton - UP',
            'postOfficeLabel'    => 'Scranton Post Office',
            'accountNumber'      => 42,
            'addressNumber'      => '1725',
            'addressStreet'      => 'Slough Avenue',
            'addressAdditional1' => null,
            'addressAdditional2' => null,
            'addressPostcode'    => '18505',
            'addressTown'        => 'Scranton',
            'addressCountry'     => 'USA',
            'entities'           => [6, 7]
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response = $issuingSiteController->update($fullRequest, new \Slim\Http\Response(), ['id' => self::$id]);
        $this->assertSame(204, $response->getStatusCode());

        //  READ
        $environment = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request = \Slim\Http\Request::createFromEnvironment($environment);
        $response = $issuingSiteController->getById($request, new \Slim\Http\Response(), ['id' => self::$id]);
        $responseBody = json_decode((string)$response->getBody(), true);

        $this->assertNotEmpty($responseBody['site']);
        $this->assertSame(self::$id, $responseBody['site']['id']);
        $this->assertSame('Scranton - UP', $responseBody['site']['label']);
        $this->assertSame('Scranton Post Office', $responseBody['site']['postOfficeLabel']);
        $this->assertSame(42, $responseBody['site']['accountNumber']);
        $this->assertSame('1725', $responseBody['site']['addressNumber']);
        $this->assertSame('Slough Avenue', $responseBody['site']['addressStreet']);
        $this->assertEmpty($responseBody['site']['addressAdditional1']);
        $this->assertEmpty($responseBody['site']['addressAdditional2']);
        $this->assertSame('18505', $responseBody['site']['addressPostcode']);
        $this->assertSame('Scranton', $responseBody['site']['addressTown']);
        $this->assertSame('USA', $responseBody['site']['addressCountry']);
        $this->assertNotEmpty($responseBody['site']['entities']);
        $this->assertSame(2, count($responseBody['site']['entities']));
        $this->assertSame(6, $responseBody['site']['entities'][0]);
        $this->assertSame(7, $responseBody['site']['entities'][1]);

        // fail
        $environment = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'PUT']);
        $request = \Slim\Http\Request::createFromEnvironment($environment);

        $body = [
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response = $issuingSiteController->update($fullRequest, new \Slim\Http\Response(), ['id' => self::$id]);
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Body label is empty or not a string', $responseBody['errors']);

        $body = [
            'label'              => 'Scranton',
            'postOfficeLabel'    => 'Scranton Post Office',
            'accountNumber'      => 42,
            'addressNumber'      => '1725',
            'addressStreet'      => 'Slough Avenue',
            'addressAdditional1' => null,
            'addressAdditional2' => null,
            'addressPostcode'    => '18505',
            'addressTown'        => 'Scranton',
            'addressCountry'     => 'USA',
            'entities'           => 'toto'
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response = $issuingSiteController->update($fullRequest, new \Slim\Http\Response(), ['id' => self::$id]);
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Body entities is not an array', $responseBody['errors']);

        $body = [
            'label'              => 'Scranton',
            'postOfficeLabel'    => 'Scranton Post Office',
            'accountNumber'      => 42,
            'addressNumber'      => '1725',
            'addressStreet'      => 'Slough Avenue',
            'addressAdditional1' => null,
            'addressAdditional2' => null,
            'addressPostcode'    => '18505',
            'addressTown'        => 'Scranton',
            'addressCountry'     => 'USA',
            'entities'           => ['toto']
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response = $issuingSiteController->update($fullRequest, new \Slim\Http\Response(), ['id' => self::$id]);
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Body entities[0] is not an integer', $responseBody['errors']);

        $response = $issuingSiteController->update($fullRequest, new \Slim\Http\Response(), ['id' => self::$id * 1000]);
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Issuing site not found', $responseBody['errors']);

        $GLOBALS['login'] = 'bbain';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];

        $body = [
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response = $issuingSiteController->update($fullRequest, new \Slim\Http\Response(), ['id' => self::$id]);
        $this->assertSame(403, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);

        $this->assertSame('Service forbidden', $responseBody['errors']);

        $GLOBALS['login'] = 'superadmin';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];
    }

    public function testDelete()
    {
        $issuingSiteController = new \RegisteredMail\controllers\IssuingSiteController();

        //  DELETE
        $environment = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'DELETE']);
        $request = \Slim\Http\Request::createFromEnvironment($environment);

        $response = $issuingSiteController->delete($request, new \Slim\Http\Response(), ['id' => self::$id]);
        $this->assertSame(204, $response->getStatusCode());

        $response = $issuingSiteController->delete($request, new \Slim\Http\Response(), ['id' => self::$id]);
        $this->assertSame(204, $response->getStatusCode());

        //  READ
        $environment = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request = \Slim\Http\Request::createFromEnvironment($environment);
        $response = $issuingSiteController->getById($request, new \Slim\Http\Response(), ['id' => self::$id]);
        $responseBody = json_decode((string)$response->getBody(), true);

        $this->assertSame('Issuing site not found', $responseBody['errors']);

        // Fail
        $GLOBALS['login'] = 'bbain';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];

        $body = [
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response = $issuingSiteController->delete($fullRequest, new \Slim\Http\Response(), ['id' => self::$id]);
        $this->assertSame(403, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);

        $this->assertSame('Service forbidden', $responseBody['errors']);

        $GLOBALS['login'] = 'superadmin';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];
    }
}
