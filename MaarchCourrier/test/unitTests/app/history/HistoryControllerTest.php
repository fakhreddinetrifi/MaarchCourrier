<?php

/**
* Copyright Maarch since 2008 under licence GPLv3.
* See LICENCE.txt file at the root folder for more details.
* This file is part of Maarch software.
*
*/

use PHPUnit\Framework\TestCase;
use SrcCore\models\DatabaseModel;

class HistoryControllerTest extends TestCase
{
    public function testGetHistoryByUserId()
    {
        $environment = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request     = \Slim\Http\Request::createFromEnvironment($environment);
        $history     = new \History\controllers\HistoryController();

        $currentUser = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $response = $history->getByUserId($request, new \Slim\Http\Response(), ['userSerialId' => $currentUser['id']]);

        $responseBody = json_decode((string)$response->getBody());

        $this->assertNotEmpty($responseBody->histories);
    }

    public function testGetHistory()
    {
        $history     = new \History\controllers\HistoryController();

        //  GET
        $environment = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request     = \Slim\Http\Request::createFromEnvironment($environment);

        $userInfo = \User\models\UserModel::getByLogin(['login' => 'superadmin', 'select' => ['id']]);

        $aArgs = [
            'startDate' => date('Y-m-d H:i:s', 1521100000),
            'endDate'   => date('Y-m-d H:i:s', time()),
            'users'     => [$userInfo['id']]
        ];
        $fullRequest = $request->withQueryParams($aArgs);

        $response = $history->get($fullRequest, new \Slim\Http\Response());
        $responseBody = json_decode((string)$response->getBody(), true);

        $this->assertIsArray($responseBody['history']);
        $this->assertNotEmpty($responseBody['history']);
    }

    public function testGetBatchHistory()
    {
        $batchHistory     = new \History\controllers\BatchHistoryController();

        //  GET
        $environment = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request     = \Slim\Http\Request::createFromEnvironment($environment);

        $aArgs = [
            'startDate' => date('Y-m-d H:i:s', 1521100000),
            'endDate'   => date('Y-m-d H:i:s', time())
        ];
        $fullRequest = $request->withQueryParams($aArgs);

        $response = $batchHistory->get($fullRequest, new \Slim\Http\Response());
        $responseBody = json_decode((string)$response->getBody(), true);

        $this->assertIsArray($responseBody['history']);
        $this->assertIsInt($responseBody['count']);
        $this->assertNotNull($responseBody['history']);
    }

    public function testGetBatchAvailableFilters()
    {
        $batchHistory = new \History\controllers\BatchHistoryController();

        //  GET
        $environment = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request     = \Slim\Http\Request::createFromEnvironment($environment);

        $response = $batchHistory->getAvailableFilters($request, new \Slim\Http\Response());
        $responseBody = json_decode((string)$response->getBody(), true);

        $this->assertIsArray($responseBody['modules']);
    }

    public function testGetAvailableFilters()
    {
        $historyController = new \History\controllers\HistoryController();

        //  GET
        $environment = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request     = \Slim\Http\Request::createFromEnvironment($environment);

        $response = $historyController->getAvailableFilters($request, new \Slim\Http\Response());
        $responseBody = json_decode((string)$response->getBody(), true);

        $this->assertIsArray($responseBody['actions']);
        $this->assertIsArray($responseBody['systemActions']);
        $this->assertIsArray($responseBody['users']);
    }

    public function testRealDelete()
    {
        $userInfo = \User\models\UserModel::getByLogin(['login' => 'bbain', 'select' => ['id']]);

        $aResId = DatabaseModel::select([
            'select'    => ['res_id'],
            'table'     => ['res_letterbox'],
            'where'     => ['subject like ?','typist = ?', 'dest_user = ?'],
            'data'      => ['%Superman is alive - PHP unit', 19, $userInfo['id']],
            'order_by'  => ['res_id DESC']
        ]);

        $aNewResId = array_column($aResId, 'res_id');

        //  REAL DELETE
        \SrcCore\models\DatabaseModel::delete([
            'table' => 'res_letterbox',
            'where' => ['res_id in (?)'],
            'data'  => [$aNewResId]
        ]);

        //  READ
        foreach ($aNewResId as $resId) {
            $res = \Resource\models\ResModel::getById(['resId' => $resId, 'select' => ['*']]);
            $this->assertIsArray($res);
            $this->assertEmpty($res);
        }
    }
}
