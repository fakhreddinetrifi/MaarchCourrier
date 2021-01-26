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

class CoreControllerTest extends TestCase
{
    // scandir(dist): failed to open dir: No such file or directory
    // public function testInitialize()
    // {
    //     $CoreController = new \SrcCore\controllers\CoreController();

    //     $environment = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
    //     $request     = \Slim\Http\Request::createFromEnvironment($environment);

    //     $response     = $CoreController->initialize($request, new \Slim\Http\Response());
    //     $responseBody = json_decode((string)$response->getBody());

    //     $this->assertNotEmpty($responseBody->coreUrl);
    //     $this->assertNotEmpty($responseBody->applicationName);
    //     $this->assertNotEmpty($responseBody->applicationMinorVersion);
    //     $version = explode(".", $responseBody->applicationMinorVersion);
    //     $this->assertSame('18', $version[0]);
    //     $this->assertSame('10', $version[1]);
    //     $this->assertIsInt((int)$version[2]);
    //     $this->assertSame('fr', $responseBody->lang);
    //     $this->assertNotEmpty($responseBody->user);
    //     $this->assertIsInt($responseBody->user->id);
    //     $this->assertSame('superadmin', $responseBody->user->user_id);
    //     $this->assertSame('Super', $responseBody->user->firstname);
    //     $this->assertNotEmpty($responseBody->scriptsToinject);
    // }

    public function testGetHeader()
    {
        $coreController = new \SrcCore\controllers\CoreController();

        $environment = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request     = \Slim\Http\Request::createFromEnvironment($environment);

        $response     = $coreController->getHeader($request, new \Slim\Http\Response());
        $responseBody = json_decode((string)$response->getBody());
        $this->assertNotEmpty($responseBody->user);
        $this->assertIsInt($responseBody->user->id);
        $this->assertSame("superadmin", $responseBody->user->user_id);
        $this->assertSame("Super", $responseBody->user->firstname);
        $this->assertSame("ADMIN", $responseBody->user->lastname);
        $this->assertIsArray($responseBody->user->groups);
        $this->assertIsArray($responseBody->user->entities);
    }

    public function testGetLanguage()
    {
        $this->assertFileExists("src/core/lang/lang-en.php");
        $this->assertStringNotEqualsFile("src/core/lang/lang-en.php", '');
        include("src/core/lang/lang-en.php");
        $this->assertFileExists("src/core/lang/lang-nl.php");
        $this->assertStringNotEqualsFile("src/core/lang/lang-nl.php", '');
        include("src/core/lang/lang-nl.php");

        $language = \SrcCore\models\CoreConfigModel::getLanguage();
        $this->assertFileExists("src/core/lang/lang-{$language}.php");
        $this->assertStringNotEqualsFile("src/core/lang/lang-{$language}.php", '');
        include("src/core/lang/lang-{$language}.php");
        
        $this->assertFileNotExists("src/core/lang/lang-zh.php");
    }

    public function testGetExternalConnectionsEnabled()
    {
        $coreController = new \SrcCore\controllers\CoreController();

        $environment = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request     = \Slim\Http\Request::createFromEnvironment($environment);

        $response     = $coreController->externalConnectionsEnabled($request, new \Slim\Http\Response());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertIsBool($responseBody['connection']['maarchParapheur']);
        $this->assertSame(true, $responseBody['connection']['maarchParapheur']);
    }
}
