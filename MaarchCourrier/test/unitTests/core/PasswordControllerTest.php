<?php

/**
* Copyright Maarch since 2008 under licence GPLv3.
* See LICENCE.txt file at the root folder for more details.
* This file is part of Maarch software.
*
*/

use PHPUnit\Framework\TestCase;

class PasswordControllerTest extends TestCase
{
    public function testGetRules()
    {
        $passwordController = new \SrcCore\controllers\PasswordController();

        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        $response     = $passwordController->getRules($request, new \Slim\Http\Response());
        $responseBody = json_decode((string)$response->getBody());

        $this->assertIsArray($responseBody->rules);
        $this->assertNotEmpty($responseBody->rules);
    }

    public function testUpdateRules()
    {
        $passwordController = new \SrcCore\controllers\PasswordController();

        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        $response     = $passwordController->getRules($request, new \Slim\Http\Response());
        $responseBody = json_decode((string)$response->getBody());

        // reset
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
        }

        $fullRequest    = \httpRequestCustom::addContentInBody(['rules' => $rules], $request);
        $response       = $passwordController->updateRules($fullRequest, new \Slim\Http\Response());
        $responseBody   = json_decode((string)$response->getBody());

        $this->assertSame($responseBody->success, 'success');

        $isPasswordValid = $passwordController->isPasswordValid(['password' => 'maarch']);
        $this->assertSame($isPasswordValid, true);

        // minLength
        foreach ($rules as $key => $rule) {
            if ($rule['label'] == 'minLength') {
                $rules[$key]['value'] = 7;
                $rules[$key]['enabled'] = true;
            }
        }

        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'PUT']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        $fullRequest    = \httpRequestCustom::addContentInBody(['rules' => $rules], $request);
        $response       = $passwordController->updateRules($fullRequest, new \Slim\Http\Response());
        $responseBody   = json_decode((string)$response->getBody());

        $this->assertSame($responseBody->success, 'success');

        $isPasswordValid = $passwordController->isPasswordValid(['password' => 'maarch']);
        $this->assertSame($isPasswordValid, false);
        $isPasswordValid = $passwordController->isPasswordValid(['password' => 'maaarch']);
        $this->assertSame($isPasswordValid, true);

        // complexityUpper
        foreach ($rules as $key => $rule) {
            if ($rule['label'] == 'complexityUpper') {
                $rules[$key]['enabled'] = true;
            }
        }

        $fullRequest    = \httpRequestCustom::addContentInBody(['rules' => $rules], $request);
        $response       = $passwordController->updateRules($fullRequest, new \Slim\Http\Response());
        $responseBody   = json_decode((string)$response->getBody());

        $this->assertSame($responseBody->success, 'success');

        $isPasswordValid = $passwordController->isPasswordValid(['password' => 'maaarch']);
        $this->assertSame($isPasswordValid, false);
        $isPasswordValid = $passwordController->isPasswordValid(['password' => 'Maaarch']);
        $this->assertSame($isPasswordValid, true);

        // complexityNumber
        foreach ($rules as $key => $rule) {
            if ($rule['label'] == 'complexityNumber') {
                $rules[$key]['enabled'] = true;
            }
        }

        $fullRequest    = \httpRequestCustom::addContentInBody(['rules' => $rules], $request);
        $response       = $passwordController->updateRules($fullRequest, new \Slim\Http\Response());
        $responseBody   = json_decode((string)$response->getBody());

        $this->assertSame($responseBody->success, 'success');

        $isPasswordValid = $passwordController->isPasswordValid(['password' => 'Maaarch']);
        $this->assertSame($isPasswordValid, false);
        $isPasswordValid = $passwordController->isPasswordValid(['password' => 'Maaarch1']);
        $this->assertSame($isPasswordValid, true);

        // complexitySpecial
        foreach ($rules as $key => $rule) {
            if ($rule['label'] == 'complexitySpecial') {
                $rules[$key]['enabled'] = true;
            }
        }

        $fullRequest    = \httpRequestCustom::addContentInBody(['rules' => $rules], $request);
        $response       = $passwordController->updateRules($fullRequest, new \Slim\Http\Response());
        $responseBody   = json_decode((string)$response->getBody());

        $this->assertSame($responseBody->success, 'success');

        $isPasswordValid = $passwordController->isPasswordValid(['password' => 'Maaarch1']);
        $this->assertSame($isPasswordValid, false);
        $isPasswordValid = $passwordController->isPasswordValid(['password' => 'Maaarch1!']);
        $this->assertSame($isPasswordValid, true);

        // reset
        foreach ($rules as $key => $rule) {
            if ($rule['label'] == 'complexitySpecial' || $rule['label'] == 'complexityNumber' || $rule['label'] == 'complexityUpper') {
                $rules[$key]['enabled'] = false;
            }
            if ($rule['label'] == 'minLength') {
                $rules[$key]['value'] = 6;
                $rules[$key]['enabled'] = true;
            }
        }

        $fullRequest    = \httpRequestCustom::addContentInBody(['rules' => $rules], $request);
        $response       = $passwordController->updateRules($fullRequest, new \Slim\Http\Response());
        $responseBody   = json_decode((string)$response->getBody());

        $this->assertSame($responseBody->success, 'success');

        $isPasswordValid = $passwordController->isPasswordValid(['password' => 'maarch']);
        $this->assertSame($isPasswordValid, true);
    }
}
