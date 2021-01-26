<?php
/**
* Copyright Maarch since 2008 under licence GPLv3.
* See LICENCE.txt file at the root folder for more details.
* This file is part of Maarch software.

* @brief   AuthenticationControllerTest
* @author  dev <dev@maarch.org>
* @ingroup core
*/

use PHPUnit\Framework\TestCase;

class AuthenticationControllerTest extends TestCase
{
    public function testAuthentication()
    {
        $_SERVER['PHP_AUTH_USER'] = 'superadmin';
        $_SERVER['PHP_AUTH_PW'] = 'superadmin';
        $response = \SrcCore\controllers\AuthenticationController::authentication();

        $this->assertNotEmpty($response);
        $this->assertSame(23, $response);
    }

    public function testAuthenticate()
    {
        $authenticationController = new \SrcCore\controllers\AuthenticationController();

        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'POST']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        $args = [
            'login'     => 'bbain',
            'password'  => 'maarch'
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($args, $request);
        $response     = $authenticationController->authenticate($fullRequest, new \Slim\Http\Response());
        $this->assertSame(204, $response->getStatusCode());

        //  ERRORS
        $args = [
            'login'     => 'bbain',
            'password'  => 'maarche'
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($args, $request);
        $response     = $authenticationController->authenticate($fullRequest, new \Slim\Http\Response());
        $this->assertSame(401, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody());

        $this->assertSame('Authentication Failed', $responseBody->errors);

        $args = [
            'logi'     => 'bbain',
            'password'  => 'maarche'
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($args, $request);
        $response     = $authenticationController->authenticate($fullRequest, new \Slim\Http\Response());
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody());

        $this->assertSame('Bad Request', $responseBody->errors);

        // MUST CONNECT WITH SUPERADMIN
        $args = [
            'login'     => 'superadmin',
            'password'  => 'superadmin'
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($args, $request);
        $response     = $authenticationController->authenticate($fullRequest, new \Slim\Http\Response());
        $this->assertSame(204, $response->getStatusCode());
    }

    public function testIsRouteAvailable()
    {
        $response = \SrcCore\controllers\AuthenticationController::isRouteAvailable(['userId' => 23, 'currentRoute' => '/actions', 'currentMethod' => 'POST']);
        $this->assertSame(true, $response['isRouteAvailable']);
    }

    public function testHandleFailedAuthentication()
    {
        $passwordController = new \SrcCore\controllers\PasswordController();

        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        $response     = $passwordController->getRules($request, new \Slim\Http\Response());
        $responseBody = json_decode((string)$response->getBody());

        // reset rules
        $rules = (array)$responseBody->rules;
        foreach ($rules as $key => $rule) {
            $rules[$key] = (array)$rule;
            $rule = (array)$rule;
            if ($rule['label'] == 'complexitySpecial' || $rule['label'] == 'complexityNumber' || $rule['label'] == 'complexityUpper') {
                $rules[$key]['enabled'] = false;
            }
            if ($rule['label'] == 'minLength') {
                $rules[$key]['value'] = 6;
                $rules[$key]['enabled'] = true;
            }
            if ($rule['label'] == 'lockAttempts') {
                $lockAttempts = $rule['value'];
                $rules[$key]['enabled'] = true;
            }
            if ($rule['label'] == 'lockTime') {
                $lockTime = $rule['value'];
                $rules[$key]['enabled'] = true;
            }
        }

        if (!empty($lockAttempts) && !empty($lockTime)) {
            $fullRequest = \httpRequestCustom::addContentInBody(['rules' => $rules], $request);
            $passwordController->updateRules($fullRequest, new \Slim\Http\Response());
    
            \User\models\UserModel::update([
                'set'   => ['failed_authentication' => 0, 'locked_until' => null],
                'where' => ['user_id = ?'],
                'data'  => ['superadmin']
            ]);

            for ($i = 1; $i < $lockAttempts; $i++) {
                $response = \SrcCore\controllers\AuthenticationController::handleFailedAuthentication(['userId' => $GLOBALS['id']]);
                $this->assertSame(true, $response);
            }
            $response = \SrcCore\controllers\AuthenticationController::handleFailedAuthentication(['userId' => $GLOBALS['id']]);
            $this->assertSame(true, $response['accountLocked']);
            $response = \SrcCore\controllers\AuthenticationController::handleFailedAuthentication(['userId' => $GLOBALS['id']]);
            $this->assertSame(true, $response['accountLocked']);
            $this->assertNotNull($response['lockedDate']);

            \User\models\UserModel::update([
                'set'   => ['failed_authentication' => 0, 'locked_until' => null],
                'where' => ['user_id = ?'],
                'data'  => ['superadmin']
            ]);
        }
    }
}
