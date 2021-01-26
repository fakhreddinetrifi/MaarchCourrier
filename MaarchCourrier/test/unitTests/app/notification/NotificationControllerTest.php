<?php
/**
* Copyright Maarch since 2008 under licence GPLv3.
* See LICENCE.txt file at the root folder for more details.
* This file is part of Maarch software.

*
* @brief   NotificationsControllerTest
*
* @author  dev <dev@maarch.org>
* @ingroup core
*/
use PHPUnit\Framework\TestCase;

class NotificationControllerTest extends TestCase
{
    private static $id = null;

    public function testCreate()
    {
        $NotificationController = new \Notification\controllers\NotificationController();

        //  CREATE
        $environment = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'POST']);
        $request = \Slim\Http\Request::createFromEnvironment($environment);

        $aArgs = [
            'notification_id' => 'testcreatetu',
            'description' => 'description de la notification',
            'is_enabled' => 'Y',
            'event_id' => 'users%',
            'notification_mode' => 'EMAIL',
            'template_id' => 4,
            'diffusion_type' => 'group',
            'diffusion_properties' => ['ADMINISTRATEUR', 'ARCHIVISTE', 'DIRECTEUR'],
            'attachfor_type' => 'entity',
            'attachfor_properties' => ['COU', 'PJS'],
        ];

        $fullRequest = \httpRequestCustom::addContentInBody($aArgs, $request);
        $response = $NotificationController->create($fullRequest, new \Slim\Http\Response());
        $responseBody = json_decode((string) $response->getBody());

        $this->assertIsInt($responseBody->notification_sid);
        self::$id = $responseBody->notification_sid;

        $this->assertSame('testcreatetu', $responseBody->notification_id);
        $this->assertSame('description de la notification', $responseBody->description);
        $this->assertSame('Y', $responseBody->is_enabled);
        $this->assertSame('users%', $responseBody->event_id);
        $this->assertSame('EMAIL', $responseBody->notification_mode);
        $this->assertSame(4, $responseBody->template_id);
        $this->assertSame('group', $responseBody->diffusion_type);
        $this->assertSame('ADMINISTRATEUR,ARCHIVISTE,DIRECTEUR', $responseBody->diffusion_properties);
        $this->assertSame('entity', $responseBody->attachfor_type);
        $this->assertSame('COU,PJS', $responseBody->attachfor_properties);
    }

    public function testCreateFail1()
    {
        //Fail Create 1
        $NotificationController = new \Notification\controllers\NotificationController();
        $environment = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'POST']);
        $request = \Slim\Http\Request::createFromEnvironment($environment);

        $aArgs = [
            'notification_id' => '',
            'description' => 'description de la notification',
            'is_enabled' => 'Y',
            'event_id' => '',
            'notification_mode' => 'EMAIL',
            'template_id' => '',
            'diffusion_type' => 'user',
            'diffusion_properties' => 'superadmin',
            'attachfor_type' => 'zz',
            'attachfor_properties' => 'cc',
        ];

        $fullRequest = \httpRequestCustom::addContentInBody($aArgs, $request);
        $response = $NotificationController->create($fullRequest, new \Slim\Http\Response());
        $responseBody = json_decode((string) $response->getBody());

        $this->assertSame('notification_id is empty', $responseBody->errors[0]);
        $this->assertSame('wrong format for template_id', $responseBody->errors[1]);
    }

    public function testCreateFail2()
    {
        //Fail Create 2
        $NotificationController = new \Notification\controllers\NotificationController();
        $environment = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'POST']);
        $request = \Slim\Http\Request::createFromEnvironment($environment);

        $aArgs = [
            'notification_id' => 'testcreatetu',
            'description' => 'description de la notification',
            'is_enabled' => 'Y',
            'event_id' => 'users%',
            'notification_mode' => 'EMAIL',
            'template_id' => 4,
            'diffusion_type' => 'user',
            'diffusion_properties' => 'superadmin',
            'attachfor_type' => 'zz',
            'attachfor_properties' => 'cc',
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($aArgs, $request);
        $response = $NotificationController->create($fullRequest, new \Slim\Http\Response());
        $responseBody = json_decode((string) $response->getBody());
        $this->assertSame('Notification déjà existante', $responseBody->errors);
    }

    public function testRead()
    {
        //READ
        $NotificationController = new \Notification\controllers\NotificationController();
        $environment = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request = \Slim\Http\Request::createFromEnvironment($environment);
        $response = $NotificationController->getBySid($request, new \Slim\Http\Response(), ['id' => self::$id]);
        $responseBody = json_decode((string) $response->getBody());

        $this->assertSame(self::$id, $responseBody->notification->notification_sid);
        $this->assertSame('testcreatetu', $responseBody->notification->notification_id);
        $this->assertSame('description de la notification', $responseBody->notification->description);
        $this->assertSame('Y', $responseBody->notification->is_enabled);
        $this->assertSame('users%', $responseBody->notification->event_id);
        $this->assertSame('EMAIL', $responseBody->notification->notification_mode);
        $this->assertSame(4, $responseBody->notification->template_id);
        $this->assertSame('group', $responseBody->notification->diffusion_type);
    }

    public function testReadFail()
    {
        $NotificationController = new \Notification\controllers\NotificationController();
        $environment = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request = \Slim\Http\Request::createFromEnvironment($environment);
        $response = $NotificationController->getBySid($request, new \Slim\Http\Response(), ['id' => 'test']);
        $responseBody = json_decode((string) $response->getBody());
        $this->assertSame('Id is not a numeric', $responseBody->errors);
    }

    public function testReadFail2()
    {
        //I CANT READ BECAUSE NO EXIST
        $NotificationController = new \Notification\controllers\NotificationController();
        $environment = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request = \Slim\Http\Request::createFromEnvironment($environment);
        $response = $NotificationController->getBySid($request, new \Slim\Http\Response(), ['id' => '9999999999']);
        $responseBody = json_decode((string) $response->getBody());
        $this->assertSame('Notification not found', $responseBody->errors);
    }

    public function testReadAll()
    {
        $NotificationController = new \Notification\controllers\NotificationController();
        $environment = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request = \Slim\Http\Request::createFromEnvironment($environment);
        $response = $NotificationController->get($request, new \Slim\Http\Response());
        $responseBody = json_decode((string) $response->getBody());

        $this->assertNotNull($responseBody->notifications);
    }

    public function testUpdate()
    {
        //  UPDATE
        $NotificationController = new \Notification\controllers\NotificationController();
        $environment = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'PUT']);
        $request = \Slim\Http\Request::createFromEnvironment($environment);
        $aArgs = [
            'notification_id' => 'testcreatetu',
            'description' => 'nouvelle description',
            'is_enabled' => 'N',
            'event_id' => 'users%',
            'notification_mode' => 'EMAIL',
            'template_id' => 3,
            'diffusion_type' => 'group',
            'diffusion_properties' => ['ADMINISTRATEUR', 'ARCHIVISTE', 'DIRECTEUR'],
            'attachfor_type' => 'entity',
            'attachfor_properties' => ['COU', 'PJS'],
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($aArgs, $request);
        $response = $NotificationController->update($fullRequest, new \Slim\Http\Response(), ['id' => self::$id]);
        $responseBody = json_decode((string) $response->getBody());

        $this->assertSame(self::$id, $responseBody->notification->notification_sid);
        $this->assertSame('testcreatetu', $responseBody->notification->notification_id);
        $this->assertSame('nouvelle description', $responseBody->notification->description);
        $this->assertSame('N', $responseBody->notification->is_enabled);
        $this->assertSame('users%', $responseBody->notification->event_id);
        $this->assertSame('EMAIL', $responseBody->notification->notification_mode);
        $this->assertSame(3, $responseBody->notification->template_id);
        $this->assertSame('group', $responseBody->notification->diffusion_type);
        $this->assertSame('ADMINISTRATEUR,ARCHIVISTE,DIRECTEUR', $responseBody->notification->diffusion_properties);
        $this->assertSame('entity', $responseBody->notification->attachfor_type);
        $this->assertSame('COU,PJS', $responseBody->notification->attachfor_properties);
    }

    public function testUpdateFail()
    {
        //  UPDATE
        $NotificationController = new \Notification\controllers\NotificationController();
        $environment = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'PUT']);
        $request = \Slim\Http\Request::createFromEnvironment($environment);
        $aArgs = [
            'notification_id' => 'testcreatetu',
            'description' => '',
            'is_enabled' => 'N',
            'event_id' => 'users%',
            'notification_mode' => 'EMAIL',
            'template_id' => '',
            'diffusion_type' => 'group',
            'diffusion_properties' => ['ADMINISTRATEUR', 'ARCHIVISTE', 'DIRECTEUR'],
            'attachfor_type' => 'entity',
            'attachfor_properties' => ['COU', 'PJS'],
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($aArgs, $request);
        $response = $NotificationController->update($fullRequest, new \Slim\Http\Response(), ['id' => self::$id]);
        $responseBody = json_decode((string) $response->getBody());

        $this->assertSame('wrong format for description', $responseBody->errors[0]);
        $this->assertSame('wrong format for template_id', $responseBody->errors[1]);
    }

    public function testUpdateFail2()
    {
        //  UPDATE
        $NotificationController = new \Notification\controllers\NotificationController();
        $environment = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'PUT']);
        $request = \Slim\Http\Request::createFromEnvironment($environment);
        $aArgs = [
            'notification_id' => 'testcreatetu',
            'description' => 'description',
            'is_enabled' => 'N',
            'event_id' => 'users%',
            'notification_mode' => 'EMAIL',
            'template_id' => 4,
            'diffusion_type' => 'group',
            'diffusion_properties' => ['ADMINISTRATEUR', 'ARCHIVISTE', 'DIRECTEUR'],
            'attachfor_type' => 'entity',
            'attachfor_properties' => ['COU', 'PJS'],
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($aArgs, $request);
        $response = $NotificationController->update($fullRequest, new \Slim\Http\Response(), ['id' => 'fail']);
        $responseBody = json_decode((string) $response->getBody());

        $this->assertSame('notification_sid is not a numeric', $responseBody->errors[0]);
        $this->assertSame('notification does not exists', $responseBody->errors[1]);
    }

    public function testDelete()
    {
        $NotificationController = new \Notification\controllers\NotificationController();

        //  DELETE
        $environment = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'DELETE']);
        $request = \Slim\Http\Request::createFromEnvironment($environment);

        $response = $NotificationController->delete($request, new \Slim\Http\Response(), ['id' => self::$id]);
        $responseBody = json_decode((string) $response->getBody());

        $this->assertNotNull($responseBody->notifications[0]);

        $environment = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request = \Slim\Http\Request::createFromEnvironment($environment);
        $response = $NotificationController->getBySid($request, new \Slim\Http\Response(), ['id' => self::$id]);
        $responseBody = json_decode((string) $response->getBody());

        $this->assertNull($responseBody->notifications[0]);

        // FAIL DELETE
        $response = $NotificationController->delete($request, new \Slim\Http\Response(), ['id' => 'gaz']);
        $responseBody = json_decode((string) $response->getBody());

        $this->assertSame('Id is not a numeric', $responseBody->errors);
    }

    public function testGetInitNotification()
    {
        // InitAction
        $environment = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request = \Slim\Http\Request::createFromEnvironment($environment);

        $NotificationController = new \Notification\controllers\NotificationController();
        $response = $NotificationController->initNotification($request, new \Slim\Http\Response());
        $responseBody = json_decode((string) $response->getBody());

        $this->assertNotNull($responseBody->notification->data->event);
        $this->assertNotNull($responseBody->notification->data->template);
        $this->assertNotNull($responseBody->notification->data->diffusionType);
        $this->assertNotNull($responseBody->notification->data->groups);
        $this->assertNotNull($responseBody->notification->data->users);
        $this->assertNotNull($responseBody->notification->data->entities);
        $this->assertNotNull($responseBody->notification->data->status);
    }
}
