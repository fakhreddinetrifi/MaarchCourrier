<?php

/**
* Copyright Maarch since 2008 under licence GPLv3.
* See LICENCE.txt file at the root folder for more details.
* This file is part of Maarch software.
*
*/

use PHPUnit\Framework\TestCase;

class ExportControllerTest extends TestCase
{
    public function testGetExportTemplates()
    {
        $exportController = new \Resource\controllers\ExportController();

        //  GET
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        $response     = $exportController->getExportTemplates($request, new \Slim\Http\Response());
        $responseBody = json_decode((string)$response->getBody());

        $this->assertNotEmpty($responseBody->templates);
        $this->assertNotEmpty($responseBody->templates->pdf);
        $this->assertNotEmpty($responseBody->templates->csv);
    }

    public function testUpdateExport()
    {
        $GLOBALS['login'] = 'bbain';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];

        $myBasket = \Basket\models\BasketModel::getByBasketId(['basketId' => 'MyBasket', 'select' => ['id']]);
        $ExportController = new \Resource\controllers\ExportController();

        //  PUT
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'PUT']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        $aArgs = [
            "resources" => $GLOBALS['resources'],
            "delimiter" => ';',
            "format"    => 'pdf',
            "data" => [
                [
                    "value" => "subject",
                    "label" => "Sujet",
                    "isFunction" => false
                ],
                [
                    "value" => "getStatus",
                    "label" => "Status",
                    "isFunction" => true
                ],
                [
                    "value" => "getPriority",
                    "label" => "Priorité",
                    "isFunction" => true
                ],
                [
                    "value" => "getDetailLink",
                    "label" => "Lien page détaillée",
                    "isFunction" => true
                ],
                [
                    "value" => "getInitiatorEntity",
                    "label" => "Entité initiatrice",
                    "isFunction" => true
                ],
                [
                    "value" => "getDestinationEntity",
                    "label" => "Entité traitante",
                    "isFunction" => true
                ],
                [
                    "value" => "getDestinationEntityType",
                    "label" => "Entité traitante",
                    "isFunction" => true
                ],
                [
                    "value" => "getCategory",
                    "label" => "Catégorie",
                    "isFunction" => true
                ],
                [
                    "value" => "getCopies",
                    "label" => "Utilisateurs en copie",
                    "isFunction" => true
                ],
                [
                    "value" => "getSenders",
                    "label" => "Expéditeurs",
                    "isFunction" => true
                ],
                [
                    "value" => "getRecipients",
                    "label" => "Destinataires",
                    "isFunction" => true
                ],
                [
                    "value" => "getTypist",
                    "label" => "Créateurs",
                    "isFunction" => true
                ],
                [
                    "value" => "getAssignee",
                    "label" => "Attributaire",
                    "isFunction" => true
                ],
                [
                    "value" => "getTags",
                    "label" => "Mots-clés",
                    "isFunction" => true
                ],
                [
                    "value" => "getSignatories",
                    "label" => "Signataires",
                    "isFunction" => true
                ],
                [
                    "value" => "getSignatureDates",
                    "label" => "Date de signature",
                    "isFunction" => true
                ],
                [
                    "value" => "getDepartment",
                    "label" => "Département de l'expéditeur",
                    "isFunction" => true
                ],
                [
                    "value" => "getAcknowledgementSendDate",
                    "label" => "Date d'accusé de réception",
                    "isFunction" => true
                ],
                [
                    "value" => "getParentFolder",
                    "label" => "Dossiers parent",
                    "isFunction" => true
                ],
                [
                    "value" => "getFolder",
                    "label" => "Dossiers",
                    "isFunction" => true
                ],
                [
                    "value" => "doc_date",
                    "label" => "Date du courrier",
                    "isFunction" => false
                ],
                [
                    "value" => "custom_4",
                    "label" => "Champ personnalisé",
                    "isFunction" => true
                ],
            ]
        ];

        //PDF
        $fullRequest = \httpRequestCustom::addContentInBody($aArgs, $request);

        $response     = $ExportController->updateExport($fullRequest, new \Slim\Http\Response(), ['userId' => 19, 'groupId' => 2, 'basketId' => $myBasket['id']]);
        $responseBody = json_decode((string)$response->getBody());
        $this->assertSame(null, $responseBody);
        $headers = $response->getHeaders();
        $this->assertSame('application/pdf', $headers['Content-Type'][0]);

        $response     = $ExportController->updateExport($fullRequest, new \Slim\Http\Response(), ['userId' => 19, 'groupId' => 2, 'basketId' => $myBasket['id']]);
        $responseBody = json_decode((string)$response->getBody());
        $this->assertSame(null, $responseBody);
        $headers = $response->getHeaders();
        $this->assertSame('application/pdf', $headers['Content-Type'][0]);

        //  GET
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        $response     = $ExportController->getExportTemplates($request, new \Slim\Http\Response());
        $responseBody = json_decode((string)$response->getBody());

        $templateData = (array)$responseBody->templates->pdf->data;
        foreach ($templateData as $key => $value) {
            $templateData[$key] = (array)$value;
        }
        $this->assertSame($aArgs['data'], $templateData);

        //CSV
        $aArgs['format'] = 'csv';
        $fullRequest = \httpRequestCustom::addContentInBody($aArgs, $request);

        $response     = $ExportController->updateExport($fullRequest, new \Slim\Http\Response(), ['userId' => 19, 'groupId' => 2, 'basketId' => $myBasket['id']]);
        $responseBody = json_decode((string)$response->getBody());

        $this->assertSame(null, $responseBody);

        //  GET
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        $response     = $ExportController->getExportTemplates($request, new \Slim\Http\Response());
        $responseBody = json_decode((string)$response->getBody());

        $templateData = (array)$responseBody->templates->csv->data;
        foreach ($templateData as $key => $value) {
            $templateData[$key] = (array)$value;
        }
        $this->assertSame($aArgs['data'], $templateData);
        $this->assertSame(';', $responseBody->templates->csv->delimiter);


        //ERRORS
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'PUT']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        unset($aArgs['data'][2]['label']);
        $fullRequest = \httpRequestCustom::addContentInBody($aArgs, $request);
        $response = $ExportController->updateExport($fullRequest, new \Slim\Http\Response(), ['userId' => 19, 'groupId' => 2, 'basketId' => $myBasket['id']]);
        $responseBody = json_decode((string)$response->getBody());
        $this->assertSame('One data is not set well', $responseBody->errors);

        unset($aArgs['data']);
        $fullRequest = \httpRequestCustom::addContentInBody($aArgs, $request);
        $response = $ExportController->updateExport($fullRequest, new \Slim\Http\Response(), ['userId' => 19, 'groupId' => 2, 'basketId' => $myBasket['id']]);
        $responseBody = json_decode((string)$response->getBody());
        $this->assertSame('Data data is empty or not an array', $responseBody->errors);

        $aArgs['delimiter'] = 't';
        $fullRequest = \httpRequestCustom::addContentInBody($aArgs, $request);
        $response = $ExportController->updateExport($fullRequest, new \Slim\Http\Response(), ['userId' => 19, 'groupId' => 2, 'basketId' => $myBasket['id']]);
        $responseBody = json_decode((string)$response->getBody());
        $this->assertSame('Delimiter is empty or not a string between [\',\', \';\', \'TAB\']', $responseBody->errors);

        $aArgs['format'] = 'pd';
        $fullRequest = \httpRequestCustom::addContentInBody($aArgs, $request);
        $response = $ExportController->updateExport($fullRequest, new \Slim\Http\Response(), ['userId' => 19, 'groupId' => 2, 'basketId' => $myBasket['id']]);
        $responseBody = json_decode((string)$response->getBody());
        $this->assertSame('Data format is empty or not a string between [\'pdf\', \'csv\']', $responseBody->errors);

        $GLOBALS['login'] = 'superadmin';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];
    }
}
