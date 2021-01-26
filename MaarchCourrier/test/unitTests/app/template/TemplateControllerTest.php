<?php

/**
* Copyright Maarch since 2008 under licence GPLv3.
* See LICENCE.txt file at the root folder for more details.
* This file is part of Maarch software.
*
*/

use PHPUnit\Framework\TestCase;

class TemplateControllerTest extends TestCase
{
    private static $id = null;
    private static $id2 = null;
    private static $idDuplicated = null;
    private static $idDuplicated2 = null;
    private static $idAcknowledgementReceipt = null;
    private static $resId = null;


    public function testCreate()
    {
        $templates   = new \Template\controllers\TemplateController();

        ########## CREATE ##########
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'POST']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        $aArgs = [
            'label'                     => 'TEST TEMPLATE',
            'description'               => 'DESCRIPTION OF THIS TEMPLATE',
            'target'                    => 'sendmail',
            'template_attachment_type'  => 'all',
            'type'                      => 'HTML',
            'file'                      => [
                'content'               => 'Content of this template',
            ],
            'datasource'                => 'letterbox_attachment',
            'entities'                  => ['DGS', 'COU', 'PSF']
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($aArgs, $request);

        $response     = $templates->create($fullRequest, new \Slim\Http\Response());
        $this->assertSame(200, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody());

        self::$id = $responseBody->template;
        $this->assertIsInt(self::$id);


        ########## CREATE FAIL ##########
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        $aArgs = [
            'label'                     => '',
            'description'               => '',
            'target'                    => 'sendmail',
            'template_attachment_type'  => 'all',
            'type'                      => 'HTML',
            'content'                   => 'Content of this template',
            'datasource'                => 'letterbox_attachment'
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($aArgs, $request);

        $response     = $templates->create($fullRequest, new \Slim\Http\Response());
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody());

        $this->assertSame('Bad Request', $responseBody->errors);

        ########## CREATE ACKNOLEDGEMENT RECEIPT ##########

        //Create entity
        $entityController = new \Entity\controllers\EntityController();
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'POST']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        $aArgs = [
            'entity_id'         => 'TST_AR',
            'entity_label'      => 'TEST-ENTITY_AR',
            'short_label'       => 'TEST-ENTITY_AR',
            'entity_type'       => 'Service',
            'email'             => 'test@test.fr',
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($aArgs, $request);

        $response     = $entityController->create($fullRequest, new \Slim\Http\Response());
        $this->assertSame(200, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody());

        $this->assertIsArray($responseBody->entities);

        //Create template
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'POST']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        $aArgs = [
            'label'            => 'TEST TEMPLATE AR',
            'description'          => 'DESCRIPTION OF THIS TEMPLATE',
            'target'           => 'acknowledgementReceipt',
            'template_attachment_type'  => 'ARsimple',
            'type'             => 'OFFICE_HTML',
            'file' => [
                'electronic' => [
                    'content'          => 'Content of this template',
                ]
            ],
            'datasource'       => 'letterbox_attachment',
            'entities'                  => ['TST']
        ];

        $fullRequest = \httpRequestCustom::addContentInBody($aArgs, $request);

        $response     = $templates->create($fullRequest, new \Slim\Http\Response());
        $this->assertSame(200, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody());

        self::$idAcknowledgementReceipt = $responseBody->template;
        $this->assertIsInt(self::$idAcknowledgementReceipt);

        $fileContent = file_get_contents('modules/templates/templates/styles/AR_Masse_Simple.docx');
        $encodedFile = base64_encode($fileContent);

        $aArgs = [
            'label'                     => 'TEST TEMPLATE AR OFFICE',
            'description'               => 'DESCRIPTION OF THIS TEMPLATE',
            'target'                    => 'OFFICE',
            'template_attachment_type'  => 'ARsimple',
            'type'                      => 'OFFICE',
            'datasource'                => 'letterbox_attachment',
            'entities'                  => ['TST', 'BAD'],
            'file'                      => [
                'content'               => $encodedFile,
                'format'                => 'docx'
            ]
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($aArgs, $request);

        $response     = $templates->create($fullRequest, new \Slim\Http\Response());
        $this->assertSame(200, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);

        $this->assertIsInt($responseBody['template']);
        self::$id2 = $responseBody['template'];

        ########## CREATE FAIL ##########
        $fileContent = file_get_contents('test/unitTests/samples/test.txt');
        $encodedFile = base64_encode($fileContent);

        $aArgs = [
            'label'                     => 'TEST TEMPLATE AR OFFICE',
            'description'               => 'DESCRIPTION OF THIS TEMPLATE',
            'target'                    => 'OFFICE',
            'template_attachment_type'  => 'ARsimple',
            'type'                      => 'OFFICE',
            'datasource'                => 'letterbox_attachment',
            'entities'                  => ['TST', 'BAD'],
            'file'                      => [
                'content'               => $encodedFile,
                'format'                => 'txt'
            ]
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($aArgs, $request);

        $response     = $templates->create($fullRequest, new \Slim\Http\Response());
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame(_WRONG_FILE_TYPE . ' : text/plain', $responseBody['errors']);

        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        $aArgs = [
            'template_label'            => '',
            'template_comment'          => '',
            'template_target'           => 'sendmail',
            'template_attachment_type'  => 'all',
            'template_type'             => 'HTML',
            'template_content'          => 'Content of this template',
            'template_datasource'       => 'letterbox_attachment'
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($aArgs, $request);

        $response     = $templates->create($fullRequest, new \Slim\Http\Response());
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody());

        $this->assertSame('Bad Request', $responseBody->errors);

        ########## CREATE FAIL ACKNOLEDGEMENT RECEIPT - entity already associated ##########
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        $aArgs = [
            'label'                     => 'TEST TEMPLATE AR FAIL',
            'description'               => 'DESCRIPTION OF THIS TEMPLATE',
            'target'                    => 'acknowledgementReceipt',
            'template_attachment_type'  => 'ARsimple',
            'type'                      => 'OFFICE_HTML',
            'file' => [
                'electronic' => [
                    'content'          => 'Content of this template',
                ]
            ],
            'datasource'                => 'letterbox_attachment',
            'entities'                  => ['TST', 'BAD']
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($aArgs, $request);

        $response     = $templates->create($fullRequest, new \Slim\Http\Response());
        $this->assertSame(200, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody());

        $this->assertIsArray($responseBody->checkEntities);

        ########## CREATE FAIL ACKNOLEDGEMENT RECEIPT - no html and no office ##########
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        $aArgs = [
            'label'            => 'TEST TEMPLATE AR FAIL',
            'description'          => 'DESCRIPTION OF THIS TEMPLATE',
            'target'           => 'acknowledgementReceipt',
            'template_attachment_type'  => 'ARsimple',
            'type'             => 'OFFICE_HTML',
            'datasource'       => 'letterbox_attachment',
            'entities'                  => ['TST', 'BAD']
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($aArgs, $request);

        $response     = $templates->create($fullRequest, new \Slim\Http\Response());
        $this->assertSame(400, $response->getStatusCode());

        // Fail
        $GLOBALS['login'] = 'bbain';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];

        $response     = $templates->create($fullRequest, new \Slim\Http\Response());
        $this->assertSame(403, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);

        $this->assertSame('Service forbidden', $responseBody['errors']);

        $GLOBALS['login'] = 'superadmin';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];
    }

    public function testRead()
    {
        $templates   = new \Template\controllers\TemplateController();

        //  READ
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);
        $response       = $templates->getDetailledById($request, new \Slim\Http\Response(), ['id' => self::$id]);
        $this->assertSame(200, $response->getStatusCode());
        $responseBody   = json_decode((string)$response->getBody());

        $this->assertIsInt($responseBody->template->template_id);
        $this->assertSame('TEST TEMPLATE', $responseBody->template->template_label);
        $this->assertSame('DESCRIPTION OF THIS TEMPLATE', $responseBody->template->template_comment);
        $this->assertSame('sendmail', $responseBody->template->template_target);
        $this->assertSame('all', $responseBody->template->template_attachment_type);
        $this->assertSame('HTML', $responseBody->template->template_type);
        $this->assertSame('Content of this template', $responseBody->template->template_content);
        $this->assertSame('letterbox_attachment', $responseBody->template->template_datasource);
        $this->assertNotNull($responseBody->templatesModels);
        $this->assertNotNull($responseBody->templatesModels[0]->fileName);
        $this->assertNotNull($responseBody->templatesModels[0]->fileExt);
        $this->assertNotNull($responseBody->templatesModels[0]->filePath);
        $this->assertNotNull($responseBody->attachmentTypes);
        $this->assertNotNull($responseBody->attachmentTypes[0]->label);
        $this->assertNotNull($responseBody->attachmentTypes[0]->id);
        $this->assertNotNull($responseBody->datasources);
        $this->assertNotNull($responseBody->datasources[0]->id);
        $this->assertNotNull($responseBody->datasources[0]->label);
        $this->assertNotNull($responseBody->datasources[0]->function);
        $this->assertNotNull($responseBody->datasources[0]->target);
        $this->assertNotNull($responseBody->entities);
        $this->assertNotNull($responseBody->entities[0]->entity_id);
        $this->assertNotNull($responseBody->entities[0]->entity_label);

        //  READ FAIL
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);
        $response       = $templates->getDetailledById($request, new \Slim\Http\Response(), ['id' => '11119999']);
        $this->assertSame(400, $response->getStatusCode());
        $responseBody   = json_decode((string)$response->getBody());
        $this->assertSame('Template does not exist', $responseBody->errors);

        $GLOBALS['login'] = 'bbain';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];

        $response       = $templates->getDetailledById($request, new \Slim\Http\Response(), ['id' => self::$id]);
        $this->assertSame(403, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);

        $this->assertSame('Service forbidden', $responseBody['errors']);

        $GLOBALS['login'] = 'superadmin';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];
    }

    public function testUpdate()
    {
        $templates   = new \Template\controllers\TemplateController();

        ########## UPDATE ##########
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'POST']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        $aArgs = [
            'label'                     => 'TEST TEMPLATE UPDATE',
            'description'               => 'DESCRIPTION OF THIS TEMPLATE UPDATE',
            'template_attachment_type'  => 'all',
            'file'                      => [
                'content'               => 'Content of this template',
            ],
            'entities'                  => ['TST_AR', 'PSF']
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($aArgs, $request);

        $response     = $templates->update($fullRequest, new \Slim\Http\Response(), ['id' => self::$id]);
        $this->assertSame(204, $response->getStatusCode());

        //  READ
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);
        $response       = $templates->getDetailledById($request, new \Slim\Http\Response(), ['id' => self::$id]);
        $this->assertSame(200, $response->getStatusCode());
        $responseBody   = json_decode((string)$response->getBody());

        $this->assertIsInt($responseBody->template->template_id);
        $this->assertSame('TEST TEMPLATE UPDATE', $responseBody->template->template_label);
        $this->assertSame('DESCRIPTION OF THIS TEMPLATE UPDATE', $responseBody->template->template_comment);
        $this->assertSame('sendmail', $responseBody->template->template_target);
        $this->assertSame('all', $responseBody->template->template_attachment_type);
        $this->assertSame('HTML', $responseBody->template->template_type);
        $this->assertSame('Content of this template', $responseBody->template->template_content);
        $this->assertSame('letterbox_attachment', $responseBody->template->template_datasource);

        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'POST']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        $fileContent = file_get_contents('modules/templates/templates/styles/AR_Masse_Simple.docx');
        $encodedFile = base64_encode($fileContent);

        $aArgs = [
            'label'                     => 'TEST TEMPLATE AR OFFICE',
            'description'               => 'DESCRIPTION OF THIS TEMPLATE',
            'template_attachment_type'  => 'ARsimple',
            'entities'                  => ['TST', 'BAD'],
            'file'                      => [
                'content'               => $encodedFile,
                'format'                => 'docx'
            ]
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($aArgs, $request);

        $response     = $templates->update($fullRequest, new \Slim\Http\Response(), ['id' => self::$id2]);
        $this->assertSame(204, $response->getStatusCode());

        ########## UPDATE FAIL MISSING PARAMETERS ##########

        $aArgs = [
            'template_label'            => '',
            'template_comment'          => '',
            'template_target'           => 'sendmail',
            'template_attachment_type'  => 'all',
            'template_type'             => 'HTML',
            'template_content'          => 'Content of this template',
            'template_datasource'       => 'letterbox_attachment',
            'entities'                  => []
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($aArgs, $request);

        $response     = $templates->update($fullRequest, new \Slim\Http\Response(), ['id' => self::$id]);
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody());

        $this->assertSame("Bad Request", $responseBody->errors);

        ########## UPDATE FAIL WRONG ID ##########
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'POST']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        $aArgs = [
            'template_label'            => '',
            'template_comment'          => '',
            'template_target'           => 'sendmail',
            'template_attachment_type'  => 'all',
            'template_type'             => 'HTML',
            'template_content'          => 'Content of this template',
            'template_datasource'       => 'letterbox_attachment',
            'entities'                  => []
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($aArgs, $request);

        $response     = $templates->update($fullRequest, new \Slim\Http\Response(), ['id' => '1235789']);
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody());

        $this->assertSame('Template does not exist', $responseBody->errors);

        $fileContent = file_get_contents('test/unitTests/samples/test.txt');
        $encodedFile = base64_encode($fileContent);

        $aArgs = [
            'label'                     => 'TEST TEMPLATE AR OFFICE',
            'description'               => 'DESCRIPTION OF THIS TEMPLATE',
            'target'                    => 'OFFICE',
            'template_attachment_type'  => 'ARsimple',
            'type'                      => 'OFFICE',
            'entities'                  => ['TST', 'BAD'],
            'file'                      => [
                'content'               => $encodedFile,
                'format'                => 'txt'
            ]
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($aArgs, $request);

        $response     = $templates->update($fullRequest, new \Slim\Http\Response(), ['id' => self::$id2]);
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame(_WRONG_FILE_TYPE . ' : text/plain', $responseBody['errors']);

        // Fail
        $GLOBALS['login'] = 'bbain';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];

        $response     = $templates->update($fullRequest, new \Slim\Http\Response(), ['id' => self::$id2]);
        $this->assertSame(403, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);

        $this->assertSame('Service forbidden', $responseBody['errors']);

        $GLOBALS['login'] = 'superadmin';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];
    }

    public function testDuplicate()
    {
        $templates   = new \Template\controllers\TemplateController();

        ########## DUPLICATE ##########
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'POST']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        $response     = $templates->duplicate($request, new \Slim\Http\Response(), ['id' => self::$id]);
        $this->assertSame(200, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody());

        $this->assertIsInt($responseBody->id);
        self::$idDuplicated = $responseBody->id;

        $response     = $templates->duplicate($request, new \Slim\Http\Response(), ['id' => self::$id2]);
        $this->assertSame(200, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody());

        $this->assertIsInt($responseBody->id);
        self::$idDuplicated2 = $responseBody->id;

        //  READ
        $environment  = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request      = \Slim\Http\Request::createFromEnvironment($environment);
        $response     = $templates->getDetailledById($request, new \Slim\Http\Response(), ['id' => self::$idDuplicated]);
        $this->assertSame(200, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody());

        $this->assertIsInt($responseBody->template->template_id);
        $this->assertSame('Copie de TEST TEMPLATE UPDATE', $responseBody->template->template_label);
        $this->assertSame('DESCRIPTION OF THIS TEMPLATE UPDATE', $responseBody->template->template_comment);
        $this->assertSame('sendmail', $responseBody->template->template_target);
        $this->assertSame('all', $responseBody->template->template_attachment_type);
        $this->assertSame('HTML', $responseBody->template->template_type);
        $this->assertSame('Content of this template', $responseBody->template->template_content);
        $this->assertSame('letterbox_attachment', $responseBody->template->template_datasource);

        ########## DUPLICATE FAIL ##########
        $environment  = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'POST']);
        $request      = \Slim\Http\Request::createFromEnvironment($environment);

        $response     = $templates->duplicate($request, new \Slim\Http\Response(), ['id' => 139875323456]);
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody());

        $this->assertSame('Template not found', $responseBody->errors);


        $response     = $templates->duplicate($request, new \Slim\Http\Response(), ['id' => self::$idAcknowledgementReceipt]);
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody());

        $this->assertSame('Forbidden duplication', $responseBody->errors);

        // Fail
        $GLOBALS['login'] = 'bbain';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];

        $response     = $templates->duplicate($request, new \Slim\Http\Response(), ['id' => self::$id]);
        $this->assertSame(403, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);

        $this->assertSame('Service forbidden', $responseBody['errors']);

        $GLOBALS['login'] = 'superadmin';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];
    }

    public function testGetList()
    {
        $templates   = new \Template\controllers\TemplateController();

        //  READ
        $environment  = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request      = \Slim\Http\Request::createFromEnvironment($environment);
        $response     = $templates->get($request, new \Slim\Http\Response());
        $this->assertSame(200, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody());

        $idFound = false;
        $idDuplicatedFound = false;
        foreach ($responseBody->templates as $template) {
            $this->assertIsInt($template->template_id);
            $this->assertNotNull($template->template_label);
            $this->assertNotNull($template->template_comment);
            $this->assertNotNull($template->template_type);

            if ($template->template_id == self::$id) {
                $idFound = true;
            }
            if ($template->template_id == self::$idDuplicated) {
                $idDuplicatedFound = true;
            }
        }

        $this->assertSame(true, $idFound);
        $this->assertSame(true, $idDuplicatedFound);

        // Fail
        $GLOBALS['login'] = 'bbain';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];

        $response     = $templates->get($request, new \Slim\Http\Response());
        $this->assertSame(403, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);

        $this->assertSame('Service forbidden', $responseBody['errors']);

        $GLOBALS['login'] = 'superadmin';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];
    }

    public function testGetContentById()
    {
        $templates   = new \Template\controllers\TemplateController();

        //  READ
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);
        $response       = $templates->getContentById($request, new \Slim\Http\Response(), ['id' => self::$id2]);
        $this->assertSame(200, $response->getStatusCode());
        $responseBody   = json_decode((string)$response->getBody(), true);

        $this->assertIsString($responseBody['encodedDocument']);


        // Fail
        $response       = $templates->getContentById($request, new \Slim\Http\Response(), ['id' => self::$id2 * 1000]);
        $this->assertSame(400, $response->getStatusCode());
        $responseBody   = json_decode((string)$response->getBody(), true);

        $this->assertSame('Template does not exist', $responseBody['errors']);

        $response       = $templates->getContentById($request, new \Slim\Http\Response(), ['id' => self::$id]);
        $this->assertSame(400, $response->getStatusCode());
        $responseBody   = json_decode((string)$response->getBody(), true);

        $this->assertSame('Template has no office content', $responseBody['errors']);

        $GLOBALS['login'] = 'bbain';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];

        $response       = $templates->getContentById($request, new \Slim\Http\Response(), ['id' => self::$id2 * 1000]);
        $this->assertSame(403, $response->getStatusCode());
        $responseBody   = json_decode((string)$response->getBody(), true);

        $this->assertSame('Service forbidden', $responseBody['errors']);

        $GLOBALS['login'] = 'superadmin';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];
    }

    public function testGetByResId()
    {
        $templates   = new \Template\controllers\TemplateController();
        $resController = new \Resource\controllers\ResController();

        //  CREATE
        $GLOBALS['login'] = 'cchaplin';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];

        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'POST']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        $fileContent = file_get_contents('test/unitTests/samples/test.txt');
        $encodedFile = base64_encode($fileContent);

        $argsMailNew = [
            'modelId'          => 1,
            'status'           => 'NEW',
            'encodedFile'      => $encodedFile,
            'format'           => 'txt',
            'confidentiality'  => false,
            'documentDate'     => '2019-01-01 17:18:47',
            'arrivalDate'      => '2019-01-01 17:18:47',
            'processLimitDate' => '2029-01-01',
            'doctype'          => 102,
            'destination'      => 15,
            'initiator'        => 15,
            'subject'          => 'Breaking News : Superman is alive - PHP unit',
            'typist'           => 19,
            'priority'         => 'poiuytre1357nbvc',
            'followed'         => true,
            'diffusionList'    => [
                [
                    'id'   => 11,
                    'type' => 'user',
                    'mode' => 'dest'
                ]
            ]
        ];

        $fullRequest = \httpRequestCustom::addContentInBody($argsMailNew, $request);

        $response     = $resController->create($fullRequest, new \Slim\Http\Response());
        $responseBody = json_decode((string)$response->getBody(), true);
        self::$resId = $responseBody['resId'];
        $this->assertIsInt(self::$resId);

        $GLOBALS['login'] = 'superadmin';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];

        //  READ
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        $queryParams = [
            'attachmentType' => 'response_project'
        ];

        $fullRequest = $request->withQueryParams($queryParams);
        $response       = $templates->getByResId($fullRequest, new \Slim\Http\Response(), ['resId' => self::$resId]);
        $this->assertSame(200, $response->getStatusCode());
        $responseBody   = json_decode((string)$response->getBody(), true);

        $this->assertIsArray($responseBody['templates']);
        $this->assertNotEmpty($responseBody['templates']);

        $this->assertSame(1042, $responseBody['templates'][0]['id']);
        $this->assertSame('PR - Demande de Place en crèche', $responseBody['templates'][0]['label']);
        $this->assertSame('docx', $responseBody['templates'][0]['extension']);
        $this->assertSame(false, $responseBody['templates'][0]['exists']);
        $this->assertSame('response_project', $responseBody['templates'][0]['attachmentType']);


        // Fail
        $response       = $templates->getByResId($request, new \Slim\Http\Response(), ['resId' => 'wrong format']);
        $this->assertSame(400, $response->getStatusCode());
        $responseBody   = json_decode((string)$response->getBody(), true);

        $this->assertSame('Route resId is not an integer', $responseBody['errors']);

        $GLOBALS['login'] = 'bbain';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];

        $response       = $templates->getByResId($request, new \Slim\Http\Response(), ['resId' => self::$resId * 1000]);
        $responseBody   = json_decode((string)$response->getBody(), true);
        $this->assertSame(403, $response->getStatusCode());

        $this->assertSame('Document out of perimeter', $responseBody['errors']);

        $GLOBALS['login'] = 'superadmin';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];
    }

    public function testGetEmailTemplatesByResId()
    {
        $templates   = new \Template\controllers\TemplateController();

        //  READ
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        $response       = $templates->getEmailTemplatesByResId($request, new \Slim\Http\Response(), ['resId' => self::$resId]);
        $this->assertSame(200, $response->getStatusCode());
        $responseBody   = json_decode((string)$response->getBody(), true);

        $this->assertIsArray($responseBody['templates']);
        $this->assertNotEmpty($responseBody['templates']);

        $this->assertSame(1046, $responseBody['templates'][0]['id']);
        $this->assertSame('TEST TEMPLATE UPDATE', $responseBody['templates'][0]['label']);


        // Fail
        $GLOBALS['login'] = 'bbain';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];

        $response       = $templates->getEmailTemplatesByResId($request, new \Slim\Http\Response(), ['resId' => self::$resId * 1000]);
        $responseBody   = json_decode((string)$response->getBody(), true);
        $this->assertSame(403, $response->getStatusCode());

        $this->assertSame('Document out of perimeter', $responseBody['errors']);

        $GLOBALS['login'] = 'superadmin';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];
    }

    public function testMergeEmailTemplate()
    {
        $GLOBALS['login'] = 'aackermann';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];

        $templates   = new \Template\controllers\TemplateController();

        //  READ
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        $body = [
            'data' => [
                'resId' => self::$resId
            ]
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);
        $response       = $templates->mergeEmailTemplate($fullRequest, new \Slim\Http\Response(), ['id' => self::$id]);
        $responseBody   = json_decode((string)$response->getBody(), true);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('Content of this template', $responseBody['mergedDocument']);

        // Fail
        $response       = $templates->mergeEmailTemplate($request, new \Slim\Http\Response(), ['id' => 'wrong format']);
        $responseBody   = json_decode((string)$response->getBody(), true);
        $this->assertSame(400, $response->getStatusCode());

        $this->assertSame('Route param id is not an integer', $responseBody['errors']);

        $response       = $templates->mergeEmailTemplate($request, new \Slim\Http\Response(), ['id' => self::$id * 1000]);
        $responseBody   = json_decode((string)$response->getBody(), true);
        $this->assertSame(400, $response->getStatusCode());

        $this->assertSame('Template does not exist', $responseBody['errors']);

        $response       = $templates->mergeEmailTemplate($request, new \Slim\Http\Response(), ['id' => self::$id]);
        $this->assertSame(400, $response->getStatusCode());
        $responseBody   = json_decode((string)$response->getBody(), true);
        $this->assertSame('Body param resId is missing', $responseBody['errors']);

        $GLOBALS['login'] = 'superadmin';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];
    }

    public function testDelete()
    {
        $templates   = new \Template\controllers\TemplateController();

        ########## DELETE ##########
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'DELETE']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        $response     = $templates->delete($request, new \Slim\Http\Response(), ['id' => self::$id]);
        $this->assertSame(200, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody());

        $this->assertSame("success", $responseBody->success);

        $response     = $templates->delete($request, new \Slim\Http\Response(), ['id' => self::$id2]);
        $this->assertSame(200, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody());

        $this->assertSame("success", $responseBody->success);

        ########## DELETE DUPLICATED ##########
        $response     = $templates->delete($request, new \Slim\Http\Response(), ['id' => self::$idDuplicated]);
        $this->assertSame(200, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody());

        $this->assertSame("success", $responseBody->success);

        $response     = $templates->delete($request, new \Slim\Http\Response(), ['id' => self::$idDuplicated2]);
        $this->assertSame(200, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody());

        $this->assertSame("success", $responseBody->success);

        ########## DELETE ACKNOWLEDGEMENT RECEIPT ##########
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'DELETE']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        $response     = $templates->delete($request, new \Slim\Http\Response(), ['id' => self::$idAcknowledgementReceipt]);
        $this->assertSame(200, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody());

        $this->assertSame("success", $responseBody->success);

        //Delete entity
        \User\models\UserEntityModel::deleteUserEntity(['id' => $GLOBALS['id'], 'entityId' => 'TST_AR']);
        \User\models\UserEntityModel::deleteUserEntity(['id' => 19, 'entityId' => 'TST_AR']);

        $entityController = new \Entity\controllers\EntityController();
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'DELETE']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);
        $response       = $entityController->delete($request, new \Slim\Http\Response(), ['id' => 'TST_AR']);
        $this->assertSame(200, $response->getStatusCode());
        $responseBody   = json_decode((string)$response->getBody());

        $this->assertIsArray($responseBody->entities);


        ########## DELETE FAIL ##########
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'DELETE']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        $response     = $templates->delete($request, new \Slim\Http\Response(), ['id' => '8928191923']);
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody());

        $this->assertSame('Template does not exist', $responseBody->errors);

        // Fail
        $GLOBALS['login'] = 'bbain';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];

        $response     = $templates->delete($request, new \Slim\Http\Response(), ['id' => self::$id]);
        $this->assertSame(403, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);

        $this->assertSame('Service forbidden', $responseBody['errors']);

        $GLOBALS['login'] = 'superadmin';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];

        // Delete resource
        \Resource\models\ResModel::delete([
            'where' => ['res_id = ?'],
            'data' => [self::$resId]
        ]);

        $res = \Resource\models\ResModel::getById(['resId' => self::$resId, 'select' => ['*']]);
        $this->assertIsArray($res);
        $this->assertEmpty($res);
    }

    public function testInitTemplate()
    {
        $templates   = new \Template\controllers\TemplateController();

        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);
        $response       = $templates->initTemplates($request, new \Slim\Http\Response());
        $this->assertSame(200, $response->getStatusCode());
        $responseBody   = json_decode((string)$response->getBody());

        $this->assertNotNull($responseBody->templatesModels);
        $this->assertNotNull($responseBody->templatesModels[0]->fileName);
        $this->assertNotNull($responseBody->templatesModels[0]->fileExt);
        $this->assertNotNull($responseBody->templatesModels[0]->filePath);
        $this->assertNotNull($responseBody->attachmentTypes);
        $this->assertNotNull($responseBody->attachmentTypes[0]->label);
        $this->assertNotNull($responseBody->attachmentTypes[0]->id);
        $this->assertNotNull($responseBody->datasources);
        $this->assertNotNull($responseBody->datasources[0]->id);
        $this->assertNotNull($responseBody->datasources[0]->label);
        $this->assertNotNull($responseBody->datasources[0]->function);
        $this->assertNotNull($responseBody->datasources[0]->target);
        $this->assertNotNull($responseBody->entities);
        $this->assertNotNull($responseBody->entities[0]->entity_id);
        $this->assertNotNull($responseBody->entities[0]->entity_label);

        // Fail
        $GLOBALS['login'] = 'bbain';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];

        $response       = $templates->initTemplates($request, new \Slim\Http\Response());
        $this->assertSame(403, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);

        $this->assertSame('Service forbidden', $responseBody['errors']);

        $GLOBALS['login'] = 'superadmin';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];
    }
}
