<?php

/**
* Copyright Maarch since 2008 under licence GPLv3.
* See LICENCE.txt file at the root folder for more details.
* This file is part of Maarch software.
*
*/

use PHPUnit\Framework\TestCase;

class SummarySheetControllerTest extends TestCase
{
    private static $noteId = null;

    public function testCreateList()
    {
        $GLOBALS['login'] = 'ddur';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];

        $noteController = new \Note\controllers\NoteController();

        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'POST']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        $body = [
            'value'     => "Test d'ajout d'une note par php unit",
            'entities'  => ['COU', 'CAB', 'PJS'],
            'resId'     => $GLOBALS['resources'][0]
        ];

        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response     = $noteController->create($fullRequest, new \Slim\Http\Response());
        $responseBody = json_decode((string)$response->getBody(), true);

        $this->assertIsInt($responseBody['noteId']);
        self::$noteId = $responseBody['noteId'];

        $GLOBALS['login'] = 'superadmin';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];
        $userInfo = \User\models\UserModel::getByLogin(['login' => 'bbain', 'select' => ['id']]);

        \IndexingModel\models\IndexingModelFieldModel::create([
            'model_id'   => 1,
            'identifier' => 'indexingCustomField_4',
            'mandatory'  => 'false',
            'enabled'    => 'true',
            'unit'       => 'mail'
        ]);

        \IndexingModel\models\IndexingModelFieldModel::create([
            'model_id'   => 1,
            'identifier' => 'recipients',
            'mandatory'  => 'false',
            'enabled'    => 'true',
            'unit'       => 'mail'
        ]);

        \Entity\models\ListInstanceModel::create([
            'res_id'          => $GLOBALS['resources'][0],
            'sequence'        => 0,
            'item_id'         => $userInfo['id'],
            'item_type'       => 'user_id',
            'item_mode'       => 'dest',
            'added_by_user'   => $GLOBALS['id'],
            'viewed'          => 0,
            'difflist_type'   => 'VISA_CIRCUIT'
        ]);

        \Entity\models\ListInstanceModel::create([
            'res_id'          => $GLOBALS['resources'][0],
            'sequence'        => 0,
            'item_id'         => $userInfo['id'],
            'item_type'       => 'user_id',
            'item_mode'       => 'dest',
            'added_by_user'   => $GLOBALS['id'],
            'viewed'          => 0,
            'difflist_type'   => 'AVIS_CIRCUIT'
        ]);

        $GLOBALS['login'] = 'bbain';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];
        $myBasket = \Basket\models\BasketModel::getByBasketId(['basketId' => 'MyBasket', 'select' => ['id']]);

        $summarySheetController = new \Resource\controllers\SummarySheetController();

        //  POST
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'POST']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        $body = [
            "resources" => $GLOBALS['resources'],
            "units" => [
                ['label' => 'Informations', 'unit' => 'primaryInformations'],
                ['label' => 'Informations Secondaires', 'unit' => 'secondaryInformations'],
                ["label" => "Informations de destination", "unit" => "senderRecipientInformations"],
                ['label' => 'Liste de diffusion', 'unit' => 'diffusionList'],
                ['label' => 'Ptit avis les potos.', 'unit' => 'freeField'],
                ['label' => 'Annotation(s)', 'unit' => 'notes'],
                ['label' => 'Circuit de visa', 'unit' => 'visaWorkflow'],
                ['label' => 'Circuit d\'avis', 'unit' => 'opinionWorkflow'],
                ['label' => 'Commentaires', 'unit' => 'freeField'],
                ['unit' => 'qrcode']
            ],
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response     = $summarySheetController->createList($fullRequest, new \Slim\Http\Response());
        $responseBody = json_decode((string)$response->getBody());

        $this->assertSame(null, $responseBody);


        //ERRORS
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'POST']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        unset($body['resources']);
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);
        $response = $summarySheetController->createList($fullRequest, new \Slim\Http\Response());
        $this->assertSame(403, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Resources is not set or empty', $responseBody['errors']);

        $GLOBALS['login'] = 'superadmin';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];

        \IndexingModel\models\IndexingModelFieldModel::delete([
            'where' => ['identifier in (?)', 'model_id = ?'],
            'data'  => [['indexingCustomField_4', 'recipients'], 1]
        ]);

        \Entity\models\ListInstanceModel::delete([
            'where' => ['res_id = ?', 'difflist_type in (?)'],
            'data'  => [$GLOBALS['resources'][0], ['AVIS_CIRCUIT', 'VISA_CIRCUIT']]
        ]);
    }
}
