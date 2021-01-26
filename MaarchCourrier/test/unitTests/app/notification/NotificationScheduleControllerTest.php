<?php
/**
* Copyright Maarch since 2008 under licence GPLv3.
* See LICENCE.txt file at the root folder for more details.
* This file is part of Maarch software.

*
* @brief   NotificationsScheduleControllerTest
*
* @author  dev <dev@maarch.org>
* @ingroup core
*/
use PHPUnit\Framework\TestCase;

class NotificationScheduleControllerTest extends TestCase
{
    private static $id = null;

    public function testCreateScript()
    {
        $NotificationScheduleController = new \Notification\controllers\NotificationScheduleController();

        // CREATE FAIL
        $environment = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'POST']);
        $request = \Slim\Http\Request::createFromEnvironment($environment);

        $aArgs = [
            'notification_sid' => 'gaz',
            'notification_id' => '',
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($aArgs, $request);

        $response = $NotificationScheduleController->createScriptNotification($fullRequest, new \Slim\Http\Response());
        $responseBody = json_decode((string) $response->getBody());

        $this->assertSame('notification_sid is not a numeric', $responseBody->errors[0]);
        $this->assertSame('one of arguments is empty', $responseBody->errors[1]);

        // CREATE
        $environment = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'POST']);
        $request = \Slim\Http\Request::createFromEnvironment($environment);

        $aArgs = [
            'notification_sid' => 1,
            'notification_id' => 'USERS',
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($aArgs, $request);

        $response = $NotificationScheduleController->createScriptNotification($fullRequest, new \Slim\Http\Response());
        $responseBody = json_decode((string) $response->getBody());

        $this->assertSame(true, $responseBody);
    }

    public function testSaveCrontab()
    {
        $NotificationScheduleController = new \Notification\controllers\NotificationScheduleController();

        $environment = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request = \Slim\Http\Request::createFromEnvironment($environment);

        $response = $NotificationScheduleController->get($request, new \Slim\Http\Response());
        $responseBody = json_decode((string) $response->getBody());

        // CREATE FAIL
        $aArgs = $responseBody->crontab;

        $corePath = dirname(__FILE__, 5).'/';
        $newCrontab = [
            'm' => 12,
            'h' => 23,
            'dom' => '',
            'mon' => '*',
            'dow' => '*',
            'cmd' => $corePath.'bin/notification/scripts/notification_testtu.sh',
            'state' => 'normal',
        ];

        array_push($aArgs, $newCrontab);
        $fullRequest = \httpRequestCustom::addContentInBody($aArgs, $request);
        $response = $NotificationScheduleController->create($fullRequest, new \Slim\Http\Response());
        $responseBodyFail = json_decode((string) $response->getBody());

        $this->assertSame('wrong format for dom', $responseBodyFail->errors[ count($responseBodyFail->errors) - 1 ]);

        // CREATE
        $environment = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'POST']);
        $request = \Slim\Http\Request::createFromEnvironment($environment);

        $aArgs = $responseBody->crontab;

        $corePath = dirname(__FILE__, 5).'/';
        $newCrontab = [
            'm' => 12,
            'h' => 23,
            'dom' => '*',
            'mon' => '*',
            'dow' => '*',
            'cmd' => $corePath.'bin/notification/scripts/notification_testtu.sh',
            'state' => 'normal',
        ];

        array_push($aArgs, $newCrontab);
        $fullRequest = \httpRequestCustom::addContentInBody($aArgs, $request);
        $response = $NotificationScheduleController->create($fullRequest, new \Slim\Http\Response());
        $responseBodyCreate = json_decode((string) $response->getBody());

        $this->assertSame(true, $responseBodyCreate);
    }

    public function testReadAll()
    {
        $environment = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request = \Slim\Http\Request::createFromEnvironment($environment);

        $NotificationScheduleController = new \Notification\controllers\NotificationScheduleController();
        $response = $NotificationScheduleController->get($request, new \Slim\Http\Response());
        $responseBody = json_decode((string) $response->getBody());

        $this->assertIsArray($responseBody->crontab);
        $this->assertIsArray($responseBody->authorizedNotification);
        $this->assertNotNull($responseBody->authorizedNotification);
        $this->assertNotNull($responseBody->crontab);
    }

    public function testUpdateCrontab()
    {
        $NotificationScheduleController = new \Notification\controllers\NotificationScheduleController();
        $environment = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request = \Slim\Http\Request::createFromEnvironment($environment);

        $response = $NotificationScheduleController->get($request, new \Slim\Http\Response());
        $responseBody = json_decode((string) $response->getBody());

        //  UPDATE
        $environment = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'POST']);
        $request = \Slim\Http\Request::createFromEnvironment($environment);

        $aArgs = $responseBody->crontab;

        $corePath = dirname(__FILE__, 5).'/';

        $aArgs[count($aArgs) - 1] = [
            'm' => 35,
            'h' => 22,
            'dom' => '*',
            'mon' => '*',
            'dow' => '*',
            'cmd' => $corePath.'bin/notification/scripts/notification_testtu.sh',
            'state' => 'normal',
        ];

        $fullRequest = \httpRequestCustom::addContentInBody($aArgs, $request);

        $response = $NotificationScheduleController->create($fullRequest, new \Slim\Http\Response());
        $responseBody = json_decode((string) $response->getBody());

        $this->assertSame(true, $responseBody);

        $environment = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request = \Slim\Http\Request::createFromEnvironment($environment);

        $response = $NotificationScheduleController->get($request, new \Slim\Http\Response());
        $responseBody = json_decode((string) $response->getBody());

        $this->assertSame('35', $responseBody->crontab[count($responseBody->crontab) - 1]->m);
        $this->assertSame('22', $responseBody->crontab[count($responseBody->crontab) - 1]->h);
    }

    public function testDelete()
    {
        // DELETE FAIL
        $NotificationScheduleController = new \Notification\controllers\NotificationScheduleController();

        $environment = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request = \Slim\Http\Request::createFromEnvironment($environment);

        $response = $NotificationScheduleController->get($request, new \Slim\Http\Response());
        $responseBody = json_decode((string) $response->getBody());

        $aArgs = $responseBody->crontab;

        foreach ($aArgs as $id => $value) {
            if ($value->cmd == dirname(__FILE__, 5).'/'.'bin/notification/scripts/notification_testtu.sh') {
                $aArgs[$id]->state = 'hidden';
            }
        }

        $environment = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'POST']);
        $request = \Slim\Http\Request::createFromEnvironment($environment);
        $fullRequest = \httpRequestCustom::addContentInBody($aArgs, $request);

        $response = $NotificationScheduleController->create($fullRequest, new \Slim\Http\Response());
        $responseBodyFail = json_decode((string) $response->getBody());

        $this->assertSame('Problem with crontab', $responseBodyFail->errors);

        // DELETE
        $aArgs = $responseBody->crontab;

        foreach ($aArgs as $id => $value) {
            if ($value->cmd == dirname(__FILE__, 5).'/'.'bin/notification/scripts/notification_testtu.sh') {
                $aArgs[$id]->state = 'deleted';
            }
        }

        $fullRequest = \httpRequestCustom::addContentInBody($aArgs, $request);

        $response = $NotificationScheduleController->create($fullRequest, new \Slim\Http\Response());
        $responseBody = json_decode((string) $response->getBody());

        $this->assertSame(true, $responseBody);

        unlink('bin/notification/scripts/notification_USERS.sh');
    }
}
