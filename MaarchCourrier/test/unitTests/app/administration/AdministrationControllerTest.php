<?php
/**
* Copyright Maarch since 2008 under licence GPLv3.
* See LICENCE.txt file at the root folder for more details.
* This file is part of Maarch software.

* @brief   AdministrationControllerTest
* @author  dev <dev@maarch.org>
* @ingroup core
*/

use PHPUnit\Framework\TestCase;

class AdministrationControllerTest extends TestCase
{
    public function testGetDetails()
    {
        $GLOBALS['login'] = 'bblier';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];

        $environment  = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request      = \Slim\Http\Request::createFromEnvironment($environment);

        $administrationController = new \Administration\controllers\AdministrationController();
        $response         = $administrationController->getDetails($request, new \Slim\Http\Response());
        $responseBody     = json_decode((string)$response->getBody());

        $this->assertNotNull($responseBody->count);
        $this->assertIsInt($responseBody->count->users);
        $this->assertIsInt($responseBody->count->groups);
        $this->assertIsInt($responseBody->count->entities);

        $GLOBALS['login'] = 'superadmin';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];
    }
}
