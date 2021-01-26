<?php
/**
* Copyright Maarch since 2008 under licence GPLv3.
* See LICENCE.txt file at the root folder for more details.
* This file is part of Maarch software.

* @brief   ActionsControllerTest
* @author  dev <dev@maarch.org>
* @ingroup core
*/

use PHPUnit\Framework\TestCase;

class ActionsControllerTest extends TestCase
{
    private static $id = null;

    public function testCreate()
    {
        $actionController = new \Action\controllers\ActionController();

        //  CREATE
        $environment = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'POST']);
        $request     = \Slim\Http\Request::createFromEnvironment($environment);

        $aArgs = [
            'keyword'       => 'indexing',
            'label_action'  => 'TEST-LABEL',
            'id_status'     => '_NOSTATUS_',
            'actionPageId'  => 'close_mail',
            'history'       => true
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($aArgs, $request);

        $response     = $actionController->create($fullRequest, new \Slim\Http\Response());
        $responseBody = json_decode((string)$response->getBody());

        self::$id = $responseBody->actionId;

        $this->assertIsInt(self::$id);

        // FAIL CREATE
        $aArgs = [
            'keyword'          => 'indexing',
            'label_action'     => '',
            'id_status'        => '',
            'actionPageId'     => 'close_mail',
            'history'          => true
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($aArgs, $request);

        $response     = $actionController->create($fullRequest, new \Slim\Http\Response());
        $responseBody = json_decode((string)$response->getBody());
        
        $this->assertSame('Invalid Status', $responseBody->errors[0]);
        $this->assertSame('Invalid label action', $responseBody->errors[1]);
        $this->assertSame('id_status is empty', $responseBody->errors[2]);
    }

    public function testRead()
    {
        //  READ
        $environment      = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request          = \Slim\Http\Request::createFromEnvironment($environment);

        $actionController = new \Action\controllers\ActionController();
        $response         = $actionController->getById($request, new \Slim\Http\Response(), ['id' => self::$id]);
        $responseBody     = json_decode((string)$response->getBody());

        $this->assertIsInt(self::$id);
        $this->assertSame(self::$id, $responseBody->action->id);
        $this->assertSame('indexing', $responseBody->action->keyword);
        $this->assertSame('TEST-LABEL', $responseBody->action->label_action);
        $this->assertSame('_NOSTATUS_', $responseBody->action->id_status);
        $this->assertSame(false, $responseBody->action->is_system);
        $this->assertSame('close_mail', $responseBody->action->action_page);
        $this->assertSame(true, $responseBody->action->history);

        // FAIL READ
        $actionController = new \Action\controllers\ActionController();
        $response         = $actionController->getById($request, new \Slim\Http\Response(), ['id' => 'gaz']);
        $responseBody     = json_decode((string)$response->getBody());
        $this->assertSame('Route id is not an integer', $responseBody->errors);
    }

    public function testReadList()
    {
        $environment  = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request      = \Slim\Http\Request::createFromEnvironment($environment);

        $actionController = new \Action\controllers\ActionController();
        $response         = $actionController->get($request, new \Slim\Http\Response());
        $responseBody     = json_decode((string)$response->getBody());

        $this->assertNotNull($responseBody->actions);
    }

    public function testUpdate()
    {
        //  UPDATE
        $environment = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'PUT']);
        $request     = \Slim\Http\Request::createFromEnvironment($environment);
        $aArgs = [
            'keyword'          => '',
            'label_action'     => 'TEST-LABEL_UPDATED',
            'id_status'        => 'COU',
            'actionPageId'     => 'close_mail',
            'history'          => false
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($aArgs, $request);

        $actionController = new \Action\controllers\ActionController();
        $response         = $actionController->update($fullRequest, new \Slim\Http\Response(), ['id' => self::$id]);
        $responseBody     = json_decode((string)$response->getBody());

        $this->assertSame(200, $response->getStatusCode());

        // UPDATE FAIL
        $aArgs = [
            'keyword'          => '',
            'label_action'     => 'TEST-LABEL_UPDATED',
            'id_status'        => 'COU',
            'actionPageId'     => 'close_mail',
            'history'          => false
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($aArgs, $request);

        $actionController = new \Action\controllers\ActionController();
        $response         = $actionController->update($fullRequest, new \Slim\Http\Response(), ['id' => 'gaz']);
        $responseBody     = json_decode((string)$response->getBody());
        $this->assertSame('Id is not a numeric', $responseBody->errors[0]);
    }

    public function testDelete()
    {
        //  DELETE
        $environment  = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'DELETE']);
        $request      = \Slim\Http\Request::createFromEnvironment($environment);

        $actionController = new \Action\controllers\ActionController();
        $response         = $actionController->delete($request, new \Slim\Http\Response(), ['id' => self::$id]);
        $responseBody     = json_decode((string)$response->getBody());

        $this->assertNotNull($responseBody->actions);

        $environment  = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request      = \Slim\Http\Request::createFromEnvironment($environment);
        $actionController = new \Action\controllers\ActionController();
        $response     = $actionController->getById($request, new \Slim\Http\Response(), ['id' => self::$id]);
        $responseBody = json_decode((string)$response->getBody());

        $this->assertNull($responseBody->actions[0]);

        // FAIL DELETE
        $actionController = new \Action\controllers\ActionController();
        $response         = $actionController->delete($request, new \Slim\Http\Response(), ['id' => 'gaz']);
        $responseBody     = json_decode((string)$response->getBody());

        $this->assertSame('Route id is not an integer', $responseBody->errors);
    }

    public function testGetInitAction()
    {
        // InitAction
        $environment  = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request      = \Slim\Http\Request::createFromEnvironment($environment);

        $actionController = new \Action\controllers\ActionController();
        $response         = $actionController->initAction($request, new \Slim\Http\Response());
        $responseBody     = json_decode((string)$response->getBody());

        $this->assertNotNull($responseBody->action);
        $this->assertNotNull($responseBody->categoriesList);
        $this->assertNotNull($responseBody->statuses);
        $this->assertNotNull($responseBody->actionPages);
        $this->assertNotNull($responseBody->keywordsList);
    }
}
