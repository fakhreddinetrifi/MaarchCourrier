<?php
/**
* Copyright Maarch since 2008 under licence GPLv3.
* See LICENCE.txt file at the root folder for more details.
* This file is part of Maarch software.

* @brief   ContentManagementControllerTest
* @author  dev <dev@maarch.org>
* @ingroup core
*/

use PHPUnit\Framework\TestCase;

class ContentManagementControllerTest extends TestCase
{
    private static $uniqueId = null;

    public function testRenderJnlp()
    {
        $contentManagementController = new \ContentManagement\controllers\JnlpController();

        $environment = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request = \Slim\Http\Request::createFromEnvironment($environment);

        $response     = $contentManagementController->renderJnlp($request, new \Slim\Http\Response(), ['jnlpUniqueId' => 'superadmin_maarchCM_12345.js']);
        $responseBody = json_decode((string)$response->getBody());
        $this->assertSame('File extension forbidden', $responseBody->errors);
    }

    public function testGenerateJnlp()
    {
        $contentManagementController = new \ContentManagement\controllers\JnlpController();

        $environment = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request = \Slim\Http\Request::createFromEnvironment($environment);

        $response     = $contentManagementController->generateJnlp($request, new \Slim\Http\Response(), ['jnlpUniqueId' => 'superadmin_maarchCM_12345.js']);
        $responseBody = json_decode((string)$response->getBody());
        $this->assertNotNull($responseBody->generatedJnlp);
        $this->assertNotNull($responseBody->jnlpUniqueId);

        self::$uniqueId = $responseBody->jnlpUniqueId;
    }

    public function testIsLockFileExisting()
    {
        $contentManagementController = new \ContentManagement\controllers\JnlpController();

        $environment = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request = \Slim\Http\Request::createFromEnvironment($environment);

        $response     = $contentManagementController->isLockFileExisting($request, new \Slim\Http\Response(), ['jnlpUniqueId' => self::$uniqueId]);
        $responseBody = json_decode((string)$response->getBody());
        $this->assertNotNull($responseBody->lockFileFound);
        $this->assertIsBool($responseBody->lockFileFound);
        $this->assertSame(true, $responseBody->lockFileFound);
        $this->assertNotNull($responseBody->fileTrunk);
        $this->assertSame("tmp_file_".$GLOBALS['id']."_".self::$uniqueId, $responseBody->fileTrunk);
    }

    public function testGetDocumentEditorConfig()
    {
        $documentEditorController = new \ContentManagement\controllers\DocumentEditorController();

        $environment = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request = \Slim\Http\Request::createFromEnvironment($environment);

        $response     = $documentEditorController->get($request, new \Slim\Http\Response());
        $responseBody = json_decode((string)$response->getBody());
        
        $this->assertIsArray($responseBody);
        foreach ($responseBody as $value) {
            $this->assertContains($value, ['java', 'onlyoffice']);
        }
    }
}
