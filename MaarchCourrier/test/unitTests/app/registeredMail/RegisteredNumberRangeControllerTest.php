<?php

/**
 * Copyright Maarch since 2008 under licence GPLv3.
 * See LICENCE.txt file at the root folder for more details.
 * This file is part of Maarch software.
 *
 */

use PHPUnit\Framework\TestCase;

class RegisteredNumberRangeControllerTest extends TestCase
{
    private static $id = null;
    private static $id2 = null;

    public function testCreate()
    {
        $registeredNumberRangeController = new \RegisteredMail\controllers\RegisteredNumberRangeController();

        //  CREATE
        $environment = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'POST']);
        $request = \Slim\Http\Request::createFromEnvironment($environment);

        $body = [
            'registeredMailType' => '2D',
            'trackerNumber'      => 'AZPOKF30KDZP',
            'rangeStart'         => 11,
            'rangeEnd'           => 1000,
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response = $registeredNumberRangeController->create($fullRequest, new \Slim\Http\Response());
        $this->assertSame(200, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertIsInt($responseBody['id']);

        self::$id = $responseBody['id'];

        $body = [
            'registeredMailType' => '2D',
            'trackerNumber'      => 'AZPOKF30KDZP2',
            'rangeStart'         => 1001,
            'rangeEnd'           => 2000,
            'status'             => 'OK'
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response = $registeredNumberRangeController->create($fullRequest, new \Slim\Http\Response());
        $this->assertSame(200, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertIsInt($responseBody['id']);

        self::$id2 = $responseBody['id'];

        //  READ
        $environment = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request = \Slim\Http\Request::createFromEnvironment($environment);
        $response = $registeredNumberRangeController->getById($request, new \Slim\Http\Response(), ['id' => self::$id]);
        $this->assertSame(200, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);

        $this->assertNotEmpty($responseBody['range']);
        $this->assertSame(self::$id, $responseBody['range']['id']);
        $this->assertSame('2D', $responseBody['range']['registeredMailType']);
        $this->assertSame('AZPOKF30KDZP', $responseBody['range']['trackerNumber']);
        $this->assertSame(11, $responseBody['range']['rangeStart']);
        $this->assertSame(1000, $responseBody['range']['rangeEnd']);
        $this->assertSame($GLOBALS['id'], $responseBody['range']['creator']);
        $this->assertNull($responseBody['range']['currentNumber']);
        $this->assertSame(0, $responseBody['range']['fullness']);

        // fail
        $environment = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'POST']);
        $request = \Slim\Http\Request::createFromEnvironment($environment);

        $body = [
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response = $registeredNumberRangeController->create($fullRequest, new \Slim\Http\Response());
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Body registeredMailType is empty or not a string', $responseBody['errors']);

        $body = [
            'registeredMailType' => '2D'
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response = $registeredNumberRangeController->create($fullRequest, new \Slim\Http\Response());
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Body trackerNumber is empty or not a string', $responseBody['errors']);

        $body = [
            'registeredMailType' => '2D',
            'trackerNumber'      => 'AZPOKF30KDZP',
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response = $registeredNumberRangeController->create($fullRequest, new \Slim\Http\Response());
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Body rangeStart is empty or not an integer', $responseBody['errors']);

        $body = [
            'registeredMailType' => '2D',
            'trackerNumber'      => 'AZPOKF30KDZP',
            'rangeStart'         => 1,
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response = $registeredNumberRangeController->create($fullRequest, new \Slim\Http\Response());
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Body rangeEnd is empty or not an integer', $responseBody['errors']);

        $body = [
            'registeredMailType' => '2D',
            'trackerNumber'      => 'AZPOKF30KDZP3',
            'rangeStart'         => 500,
            'rangeEnd'           => 1500
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response = $registeredNumberRangeController->create($fullRequest, new \Slim\Http\Response());
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Range overlaps another range', $responseBody['errors']);

        $body = [
            'registeredMailType' => '2D',
            'trackerNumber'      => 'AZPOKF30KDZP',
            'rangeStart'         => 500,
            'rangeEnd'           => 1500
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response = $registeredNumberRangeController->create($fullRequest, new \Slim\Http\Response());
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Body trackerNumber is already used by another range', $responseBody['errors']);

        $GLOBALS['login'] = 'bbain';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];

        $response = $registeredNumberRangeController->create($fullRequest, new \Slim\Http\Response());
        $this->assertSame(403, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);

        $this->assertSame('Service forbidden', $responseBody['errors']);

        $GLOBALS['login'] = 'superadmin';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];
    }

    public function testGet()
    {
        $registeredNumberRangeController = new \RegisteredMail\controllers\RegisteredNumberRangeController();

        //  GET
        $environment = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request = \Slim\Http\Request::createFromEnvironment($environment);
        $response = $registeredNumberRangeController->get($request, new \Slim\Http\Response());
        $this->assertSame(200, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);

        $this->assertIsArray($responseBody['ranges']);
        $this->assertNotEmpty($responseBody['ranges']);

        $found = false;
        foreach ($responseBody['ranges'] as $range) {
            $this->assertIsArray($range);
            $this->assertNotEmpty($range);
            if ($range['id'] == self::$id) {
                $this->assertNotEmpty($range);
                $this->assertSame(self::$id, $range['id']);
                $this->assertSame('2D', $range['registeredMailType']);
                $this->assertSame('AZPOKF30KDZP', $range['trackerNumber']);
                $this->assertSame(11, $range['rangeStart']);
                $this->assertSame(1000, $range['rangeEnd']);
                $this->assertSame($GLOBALS['id'], $range['creator']);
                $this->assertNull($range['currentNumber']);
                $this->assertSame(0, $range['fullness']);
                $found = true;
            }
        }

        $this->assertSame(true, $found);

        $GLOBALS['login'] = 'superadmin';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];
    }

    public function testGetById()
    {
        $registeredNumberRangeController = new \RegisteredMail\controllers\RegisteredNumberRangeController();

        //  GET
        $environment = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request = \Slim\Http\Request::createFromEnvironment($environment);
        $response = $registeredNumberRangeController->getById($request, new \Slim\Http\Response(), ['id' => self::$id]);
        $this->assertSame(200, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);

        $this->assertNotEmpty($responseBody['range']);
        $this->assertSame(self::$id, $responseBody['range']['id']);
        $this->assertSame('2D', $responseBody['range']['registeredMailType']);
        $this->assertSame('AZPOKF30KDZP', $responseBody['range']['trackerNumber']);
        $this->assertSame(11, $responseBody['range']['rangeStart']);
        $this->assertSame(1000, $responseBody['range']['rangeEnd']);
        $this->assertSame($GLOBALS['id'], $responseBody['range']['creator']);
        $this->assertNull($responseBody['range']['currentNumber']);
        $this->assertSame(0, $responseBody['range']['fullness']);

        $GLOBALS['login'] = 'bbain';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];

        $response = $registeredNumberRangeController->getById($request, new \Slim\Http\Response(), ['id' => self::$id]);
        $this->assertSame(403, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Service forbidden', $responseBody['errors']);

        $GLOBALS['login'] = 'superadmin';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];
    }

    public function testUpdate()
    {
        $registeredNumberRangeController = new \RegisteredMail\controllers\RegisteredNumberRangeController();

        //  UPDATE
        $environment = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'PUT']);
        $request = \Slim\Http\Request::createFromEnvironment($environment);

        $body = [
            'registeredMailType' => '2D',
            'trackerNumber'      => 'AZPOKF30KDZP',
            'rangeStart'         => 11,
            'rangeEnd'           => 900,
            'status'             => 'OK'
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response = $registeredNumberRangeController->update($fullRequest, new \Slim\Http\Response(), ['id' => self::$id]);
        $this->assertSame(204, $response->getStatusCode());

        //  READ
        $environment = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request = \Slim\Http\Request::createFromEnvironment($environment);
        $response = $registeredNumberRangeController->getById($request, new \Slim\Http\Response(), ['id' => self::$id]);
        $responseBody = json_decode((string)$response->getBody(), true);

        $this->assertNotEmpty($responseBody['range']);
        $this->assertSame(self::$id, $responseBody['range']['id']);
        $this->assertSame('2D', $responseBody['range']['registeredMailType']);
        $this->assertSame('AZPOKF30KDZP', $responseBody['range']['trackerNumber']);
        $this->assertSame(11, $responseBody['range']['rangeStart']);
        $this->assertSame(900, $responseBody['range']['rangeEnd']);
        $this->assertSame($GLOBALS['id'], $responseBody['range']['creator']);
        $this->assertSame(11, $responseBody['range']['currentNumber']);
        $this->assertSame(0, $responseBody['range']['fullness']);
        $this->assertSame('OK', $responseBody['range']['status']);

        \RegisteredMail\models\RegisteredNumberRangeModel::update([
            'set'   => [
                'status' => 'SPD'
            ],
            'where' => ['id = ?'],
            'data'  => [self::$id2]
        ]);

        $body = [
            'registeredMailType' => '2D',
            'trackerNumber'      => 'AZPOKF30KDZP2',
            'rangeStart'         => 1001,
            'rangeEnd'           => 2000,
            'status'             => 'OK'
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response = $registeredNumberRangeController->update($fullRequest, new \Slim\Http\Response(), ['id' => self::$id2]);
        $this->assertSame(204, $response->getStatusCode());

        //  READ
        $environment = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request = \Slim\Http\Request::createFromEnvironment($environment);
        $response = $registeredNumberRangeController->getById($request, new \Slim\Http\Response(), ['id' => self::$id2]);
        $responseBody = json_decode((string)$response->getBody(), true);

        $this->assertNotEmpty($responseBody['range']);
        $this->assertSame(self::$id2, $responseBody['range']['id']);
        $this->assertSame('2D', $responseBody['range']['registeredMailType']);
        $this->assertSame('AZPOKF30KDZP2', $responseBody['range']['trackerNumber']);
        $this->assertSame(1001, $responseBody['range']['rangeStart']);
        $this->assertSame(2000, $responseBody['range']['rangeEnd']);
        $this->assertSame($GLOBALS['id'], $responseBody['range']['creator']);
        $this->assertSame(1001, $responseBody['range']['currentNumber']);
        $this->assertSame(0, $responseBody['range']['fullness']);
        $this->assertSame('OK', $responseBody['range']['status']);

        $response = $registeredNumberRangeController->getById($request, new \Slim\Http\Response(), ['id' => self::$id]);
        $this->assertSame(200, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('END', $responseBody['range']['status']);
        $this->assertNull($responseBody['range']['currentNumber']);

        \RegisteredMail\models\RegisteredNumberRangeModel::update([
            'set'   => [
                'status' => 'SPD'
            ],
            'where' => ['id = ?'],
            'data'  => [self::$id]
        ]);

        $body = [
            'registeredMailType' => '2D',
            'trackerNumber'      => 'AZPOKF30KDZP',
            'rangeStart'         => 11,
            'rangeEnd'           => 900,
            'status'             => 'END'
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response = $registeredNumberRangeController->update($fullRequest, new \Slim\Http\Response(), ['id' => self::$id]);
        $this->assertSame(204, $response->getStatusCode());

        $response = $registeredNumberRangeController->getById($request, new \Slim\Http\Response(), ['id' => self::$id]);
        $this->assertSame(200, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('END', $responseBody['range']['status']);
        $this->assertNull($responseBody['range']['currentNumber']);

        $body = [
            'registeredMailType' => '2D',
            'trackerNumber'      => 'AZPOKF30KDZP2',
            'rangeStart'         => 1001,
            'rangeEnd'           => 2000,
            'status'             => 'SPD'
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response = $registeredNumberRangeController->update($fullRequest, new \Slim\Http\Response(), ['id' => self::$id2]);
        $this->assertSame(204, $response->getStatusCode());

        $body = [
            'registeredMailType' => '2D',
            'trackerNumber'      => 'AZPOKF30KDZP2',
            'rangeStart'         => 1001,
            'rangeEnd'           => 2000,
            'status'             => 'OK'
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response = $registeredNumberRangeController->update($fullRequest, new \Slim\Http\Response(), ['id' => self::$id2]);
        $this->assertSame(204, $response->getStatusCode());

        // fail
        $environment = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'PUT']);
        $request = \Slim\Http\Request::createFromEnvironment($environment);

        $body = [
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response = $registeredNumberRangeController->update($fullRequest, new \Slim\Http\Response(), ['id' => self::$id]);
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Body registeredMailType is empty or not a string', $responseBody['errors']);

        $body = [
            'registeredMailType' => '2D'
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response = $registeredNumberRangeController->update($fullRequest, new \Slim\Http\Response(), ['id' => self::$id]);
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Body trackerNumber is empty or not a string', $responseBody['errors']);

        $body = [
            'registeredMailType' => '2D',
            'trackerNumber'      => 'AZPOKF30KDZP',
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response = $registeredNumberRangeController->update($fullRequest, new \Slim\Http\Response(), ['id' => self::$id]);
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Body rangeStart is empty or not an integer', $responseBody['errors']);

        $body = [
            'registeredMailType' => '2D',
            'trackerNumber'      => 'AZPOKF30KDZP',
            'rangeStart'         => 1,
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response = $registeredNumberRangeController->update($fullRequest, new \Slim\Http\Response(), ['id' => self::$id]);
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Body rangeEnd is empty or not an integer', $responseBody['errors']);

        $response = $registeredNumberRangeController->update($fullRequest, new \Slim\Http\Response(), ['id' => self::$id * 1000]);
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Range not found', $responseBody['errors']);

        $body = [
            'registeredMailType' => '2D',
            'trackerNumber'      => 'AZPOKF30KDZP',
            'rangeStart'         => 500,
            'rangeEnd'           => 1500,
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response = $registeredNumberRangeController->update($fullRequest, new \Slim\Http\Response(), ['id' => self::$id]);
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Range overlaps another range', $responseBody['errors']);

        $body = [
            'registeredMailType' => '2D',
            'trackerNumber'      => 'AZPOKF30KDZP2',
            'rangeStart'         => 1001,
            'rangeEnd'           => 2000,
            'status'             => 'OK'
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response = $registeredNumberRangeController->update($fullRequest, new \Slim\Http\Response(), ['id' => self::$id2]);
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Range cannot be updated', $responseBody['errors']);

        $body = [
            'registeredMailType' => '2D',
            'trackerNumber'      => 'AZPOKF30KDZP',
            'rangeStart'         => 1001,
            'rangeEnd'           => 2000,
            'status'             => 'OK'
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response = $registeredNumberRangeController->update($fullRequest, new \Slim\Http\Response(), ['id' => self::$id2]);
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Body trackerNumber is already used by another range', $responseBody['errors']);

        $GLOBALS['login'] = 'bbain';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];

        $body = [
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response = $registeredNumberRangeController->update($fullRequest, new \Slim\Http\Response(), ['id' => self::$id]);
        $this->assertSame(403, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);

        $this->assertSame('Service forbidden', $responseBody['errors']);

        $GLOBALS['login'] = 'superadmin';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];
    }

    public function testGetLastNumberByType()
    {
        $registeredNumberRangeController = new \RegisteredMail\controllers\RegisteredNumberRangeController();

        //  GET
        $environment = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request = \Slim\Http\Request::createFromEnvironment($environment);

        $response = $registeredNumberRangeController->getLastNumberByType($request, new \Slim\Http\Response(), ['type' => '2D']);
        $this->assertSame(200, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame(2000, $responseBody['lastNumber']);

        $response = $registeredNumberRangeController->getLastNumberByType($request, new \Slim\Http\Response(), ['type' => '2C']);
        $this->assertSame(200, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame(10, $responseBody['lastNumber']);

        $GLOBALS['login'] = 'bbain';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];

        $response = $registeredNumberRangeController->getLastNumberByType($request, new \Slim\Http\Response(), ['type' => '2D']);
        $this->assertSame(403, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Service forbidden', $responseBody['errors']);

        $GLOBALS['login'] = 'superadmin';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];
    }

    public function testDelete()
    {
        $registeredNumberRangeController = new \RegisteredMail\controllers\RegisteredNumberRangeController();

        //  DELETE
        $environment = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'DELETE']);
        $request = \Slim\Http\Request::createFromEnvironment($environment);

        $response = $registeredNumberRangeController->delete($request, new \Slim\Http\Response(), ['id' => self::$id]);
        $this->assertSame(204, $response->getStatusCode());

        $response = $registeredNumberRangeController->delete($request, new \Slim\Http\Response(), ['id' => self::$id]);
        $this->assertSame(204, $response->getStatusCode());

        //  READ
        $environment = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request = \Slim\Http\Request::createFromEnvironment($environment);
        $response = $registeredNumberRangeController->getById($request, new \Slim\Http\Response(), ['id' => self::$id]);
        $responseBody = json_decode((string)$response->getBody(), true);

        $this->assertSame('Range not found', $responseBody['errors']);

        // Fail
        $environment = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'DELETE']);
        $request = \Slim\Http\Request::createFromEnvironment($environment);

        $response = $registeredNumberRangeController->delete($request, new \Slim\Http\Response(), ['id' => self::$id2]);
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Range cannot be deleted', $responseBody['errors']);

        \RegisteredMail\models\RegisteredNumberRangeModel::update([
            'set'   => [
                'status' => 'SPD'
            ],
            'where' => ['id = ?'],
            'data'  => [self::$id2]
        ]);

        $response = $registeredNumberRangeController->delete($request, new \Slim\Http\Response(), ['id' => self::$id2]);
        $this->assertSame(204, $response->getStatusCode());

        $GLOBALS['login'] = 'bbain';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];

        $body = [
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response = $registeredNumberRangeController->delete($fullRequest, new \Slim\Http\Response(), ['id' => self::$id]);
        $this->assertSame(403, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);

        $this->assertSame('Service forbidden', $responseBody['errors']);

        $GLOBALS['login'] = 'superadmin';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];
    }
}
