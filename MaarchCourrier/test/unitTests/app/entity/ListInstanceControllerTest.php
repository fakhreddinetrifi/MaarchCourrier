<?php

/**
* Copyright Maarch since 2008 under licence GPLv3.
* See LICENCE.txt file at the root folder for more details.
* This file is part of Maarch software.
*
*/

use PHPUnit\Framework\TestCase;

class ListInstanceControllerTest extends TestCase
{
    private static $resourceId = null;

    public function testInit()
    {
        $GLOBALS['login'] = 'cchaplin';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];

        $resController = new \Resource\controllers\ResController();

        //  CREATE
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'POST']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);


        $aArgs = [
            'modelId'           => 1,
            'status'            => 'NEW',
            'confidentiality'   => false,
            'documentDate'      => '2019-01-01 17:18:47',
            'arrivalDate'       => '2019-01-01 17:18:47',
            'processLimitDate'  => '2029-01-01',
            'doctype'           => 102,
            'destination'       => 15,
            'initiator'         => 15,
            'subject'           => 'Du matin au soir, ils disent du mal de la vie, et ils ne peuvent se résoudre à la quitter !',
            'typist'            => 19,
            'priority'          => 'poiuytre1357nbvc',
            'diffusionList'    => [
                [
                    'id'   => 11,
                    'type' => 'user',
                    'mode' => 'dest'
                ], [
                    'id'   => 6,
                    'type' => 'entity_id',
                    'mode' => 'cc'
                ], [
                    'id'   => 10,
                    'type' => 'user',
                    'mode' => 'avis',
                ]
            ]
        ];

        $fullRequest = \httpRequestCustom::addContentInBody($aArgs, $request);

        $response     = $resController->create($fullRequest, new \Slim\Http\Response());
        $responseBody = json_decode((string)$response->getBody(), true);
        self::$resourceId = $responseBody['resId'];
        $this->assertIsInt(self::$resourceId);

        $GLOBALS['login'] = 'superadmin';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];
    }

    public function testUpdateCircuits()
    {
        $listInstanceController = new \Entity\controllers\ListInstanceController();

        //  UPDATE
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'PUT']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        $body = [
            'resources' => [
                [
                    'resId'  => self::$resourceId,
                    'listInstances' => [
                        ["item_id" => 17, "requested_signature" => false],
                        ["item_id" => 18, "requested_signature" => true]
                    ]
                ],
            ],
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response = $listInstanceController->updateCircuits($fullRequest, new \Slim\Http\Response(), ['type' => 'visaCircuit']);
        $this->assertSame(204, $response->getStatusCode());

        $body = [
            'resources' => [
                [
                    'resId'         => self::$resourceId,
                    'listInstances' => [
                        [
                            'item_id'       => 10,
                            'item_mode'     => 'avis',
                            'item_type'     => 'user'
                        ]
                    ]
                ]
            ]
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);
        $response = $listInstanceController->updateCircuits($fullRequest, new \Slim\Http\Response(), ['type' => 'opinionCircuit']);

        $this->assertSame(204, $response->getStatusCode());

        // Errors
        $fullRequest = \httpRequestCustom::addContentInBody([], $request);

        $response = $listInstanceController->updateCircuits($fullRequest, new \Slim\Http\Response(), ['type' => 'visaCircuit']);
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Body is not set or not an array', $responseBody['errors']);

        $body = [
            'resources' => [
                []
            ]
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);

        $response = $listInstanceController->updateCircuits($fullRequest, new \Slim\Http\Response(), ['type' => 'toto']);
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Route params type is empty or not valid', $responseBody['errors']);

        $response = $listInstanceController->updateCircuits($fullRequest, new \Slim\Http\Response(), ['type' => 'visaCircuit']);
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Body resources[0] resId is empty', $responseBody['errors']);

        $GLOBALS['login'] = 'bblier';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];

        $body = [
            'resources' => [
                [
                    'resId' => self::$resourceId * 1000
                ]
            ]
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);
        $response = $listInstanceController->updateCircuits($fullRequest, new \Slim\Http\Response(), ['type' => 'visaCircuit']);
        $this->assertSame(403, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Resource out of perimeter', $responseBody['errors']);

        $GLOBALS['login'] = 'superadmin';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];

        $body = [
            'resources' => [
                [
                    'resId' => self::$resourceId
                ]
            ]
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);
        $response = $listInstanceController->updateCircuits($fullRequest, new \Slim\Http\Response(), ['type' => 'visaCircuit']);
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Body resources[0] listInstances is empty', $responseBody['errors']);

        $body = [
            'resources' => [
                [
                    'resId' => self::$resourceId,
                    'listInstances' => [
                        ['item_id' => '', 'requested_signature' => false],
                        ['item_id' => 18, 'requested_signature' => true]
                    ]
                ]
            ]
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);
        $response = $listInstanceController->updateCircuits($fullRequest, new \Slim\Http\Response(), ['type' => 'visaCircuit']);
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Body resources[0] listInstances[0] item_id is empty', $responseBody['errors']);

        $body = [
            'resources' => [
                [
                    'resId' => self::$resourceId,
                    'listInstances' => [
                        ['item_id' => 17, 'requested_signature' => false, 'process_comment' => 'too long !!#######################################################################################################################################################################################################################################################'],
                        ['item_id' => 18, 'requested_signature' => true]
                    ]
                ]
            ]
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);
        $response = $listInstanceController->updateCircuits($fullRequest, new \Slim\Http\Response(), ['type' => 'visaCircuit']);
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Body resources[0] listInstances[0] process_comment is too long', $responseBody['errors']);

        $body = [
            'resources' => [
                [
                    'resId' => self::$resourceId,
                    'listInstances' => [
                        ['item_id' => 'mscott', 'requested_signature' => false],
                        ['item_id' => 18, 'requested_signature' => true]
                    ]
                ]
            ]
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);
        $response = $listInstanceController->updateCircuits($fullRequest, new \Slim\Http\Response(), ['type' => 'visaCircuit']);
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Body resources[0] listInstances[0] item_id does not exist', $responseBody['errors']);

        $body = [
            'resources' => [
                [
                    'resId' => self::$resourceId,
                    'listInstances' => [
                        ['item_id' => 'bbain', 'requested_signature' => false],
                        ['item_id' => 18, 'requested_signature' => true]
                    ]
                ]
            ]
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);
        $response = $listInstanceController->updateCircuits($fullRequest, new \Slim\Http\Response(), ['type' => 'visaCircuit']);
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Body resources[0] listInstances[0] item_id has not enough privileges', $responseBody['errors']);

        $body = [
            'resources' => [
                [
                    'resId' => self::$resourceId,
                    'listInstances' => [
                        ['item_id' => 'bbain', 'requested_signature' => false],
                        ['item_id' => 18, 'requested_signature' => true]
                    ]
                ]
            ]
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);
        $response = $listInstanceController->updateCircuits($fullRequest, new \Slim\Http\Response(), ['type' => 'opinionCircuit']);
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Body resources[0] listInstances[0] item_id has not enough privileges', $responseBody['errors']);
    }

    public function testGetVisaCircuitByResId()
    {
        $listInstanceController = new \Entity\controllers\ListInstanceController();

        //  READ
        $environment = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request = \Slim\Http\Request::createFromEnvironment($environment);

        $response = $listInstanceController->getVisaCircuitByResId($request, new \Slim\Http\Response(), ['resId' => self::$resourceId]);
        $this->assertSame(200, $response->getStatusCode());

        $responseBody = json_decode((string)$response->getBody(), true);

        $this->assertSame(17, $responseBody['circuit'][0]['item_id']);
        $this->assertSame('user', $responseBody['circuit'][0]['item_type']);
        $this->assertSame(false, $responseBody['circuit'][0]['requested_signature']);
        $this->assertNotEmpty($responseBody['circuit'][0]['labelToDisplay']);
        $this->assertSame(18, $responseBody['circuit'][1]['item_id']);
        $this->assertSame('user', $responseBody['circuit'][1]['item_type']);
        $this->assertSame(true, $responseBody['circuit'][1]['requested_signature']);
        $this->assertNotEmpty($responseBody['circuit'][1]['labelToDisplay']);

        $GLOBALS['login'] = 'ddur';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];

        $response = $listInstanceController->getVisaCircuitByResId($request, new \Slim\Http\Response(), ['resId' => self::$resourceId]);
        $this->assertSame(403, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Document out of perimeter', $responseBody['errors']);

        $GLOBALS['login'] = 'superadmin';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];
    }

    public function testGetByResId()
    {
        $listInstanceController = new \Entity\controllers\ListInstanceController();

        //  READ
        $environment = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request = \Slim\Http\Request::createFromEnvironment($environment);

        $response = $listInstanceController->getByResId($request, new \Slim\Http\Response(), ['resId' => self::$resourceId]);
        $this->assertSame(200, $response->getStatusCode());

        $responseBody = json_decode((string)$response->getBody(), true);

        $userInfo = \User\models\UserModel::getByLogin(['login' => 'cchaplin', 'select' => ['id']]);

        $this->assertIsArray($responseBody['listInstance']);

        $this->assertSame(self::$resourceId, $responseBody['listInstance'][0]['res_id']);
        $this->assertSame(0, $responseBody['listInstance'][0]['sequence']);
        $this->assertSame('aackermann', $responseBody['listInstance'][0]['item_id']);
        $this->assertSame('user_id', $responseBody['listInstance'][0]['item_type']);
        $this->assertSame('dest', $responseBody['listInstance'][0]['item_mode']);
        $this->assertSame($userInfo['id'], $responseBody['listInstance'][0]['added_by_user']);
        $this->assertSame(0, $responseBody['listInstance'][0]['viewed']);
        $this->assertSame('entity_id', $responseBody['listInstance'][0]['difflist_type']);
        $this->assertEmpty($responseBody['listInstance'][0]['process_date']);
        $this->assertEmpty($responseBody['listInstance'][0]['process_comment']);
        $this->assertEmpty($responseBody['listInstance'][0]['signatory']);
        $this->assertSame(false, $responseBody['listInstance'][0]['requested_signature']);
        $this->assertSame(11, $responseBody['listInstance'][0]['itemSerialId']);
        $this->assertNotEmpty($responseBody['listInstance'][0]['labelToDisplay']);
        $this->assertNotEmpty($responseBody['listInstance'][0]['descriptionToDisplay']);

        $this->assertSame(self::$resourceId, $responseBody['listInstance'][1]['res_id']);
        $this->assertSame(0, $responseBody['listInstance'][1]['sequence']);
        $this->assertSame('PJS', $responseBody['listInstance'][1]['item_id']);
        $this->assertSame('entity_id', $responseBody['listInstance'][1]['item_type']);
        $this->assertSame('cc', $responseBody['listInstance'][1]['item_mode']);
        $this->assertSame($userInfo['id'], $responseBody['listInstance'][1]['added_by_user']);
        $this->assertSame(0, $responseBody['listInstance'][1]['viewed']);
        $this->assertSame('entity_id', $responseBody['listInstance'][1]['difflist_type']);
        $this->assertEmpty($responseBody['listInstance'][1]['process_date']);
        $this->assertEmpty($responseBody['listInstance'][1]['process_comment']);
        $this->assertEmpty($responseBody['listInstance'][1]['signatory']);
        $this->assertSame(false, $responseBody['listInstance'][1]['requested_signature']);
        $this->assertSame(6, $responseBody['listInstance'][1]['itemSerialId']);
        $this->assertNotEmpty($responseBody['listInstance'][1]['labelToDisplay']);
        $this->assertEmpty($responseBody['listInstance'][1]['descriptionToDisplay']);

        $this->assertSame(self::$resourceId, $responseBody['listInstance'][2]['res_id']);
        $this->assertSame(0, $responseBody['listInstance'][2]['sequence']);
        $this->assertSame('ppetit', $responseBody['listInstance'][2]['item_id']);
        $this->assertSame('user_id', $responseBody['listInstance'][2]['item_type']);
        $this->assertSame('avis', $responseBody['listInstance'][2]['item_mode']);
        $this->assertSame($userInfo['id'], $responseBody['listInstance'][2]['added_by_user']);
        $this->assertSame(0, $responseBody['listInstance'][2]['viewed']);
        $this->assertSame('entity_id', $responseBody['listInstance'][2]['difflist_type']);
        $this->assertEmpty($responseBody['listInstance'][2]['process_date']);
        $this->assertEmpty($responseBody['listInstance'][2]['process_comment']);
        $this->assertEmpty($responseBody['listInstance'][2]['signatory']);
        $this->assertSame(false, $responseBody['listInstance'][2]['requested_signature']);
        $this->assertSame(10, $responseBody['listInstance'][2]['itemSerialId']);
        $this->assertNotEmpty($responseBody['listInstance'][2]['labelToDisplay']);
        $this->assertNotEmpty($responseBody['listInstance'][2]['descriptionToDisplay']);

        $GLOBALS['login'] = 'ddur';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];

        $response = $listInstanceController->getByResId($request, new \Slim\Http\Response(), ['resId' => self::$resourceId]);
        $this->assertSame(403, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Document out of perimeter', $responseBody['errors']);

        $GLOBALS['login'] = 'superadmin';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];
    }

    public function testGetOpinionCircuitByResId()
    {
        $listInstanceController = new \Entity\controllers\ListInstanceController();

        //  READ
        $environment  = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request = \Slim\Http\Request::createFromEnvironment($environment);

        $response = $listInstanceController->getOpinionCircuitByResId($request, new \Slim\Http\Response(), ['resId' => self::$resourceId]);
        $this->assertSame(200, $response->getStatusCode());

        $responseBody = json_decode((string)$response->getBody(), true);

        $userInfo = \User\models\UserModel::getByLogin(['login' => 'ppetit', 'select' => ['id']]);

        $this->assertIsArray($responseBody['circuit']);

        $this->assertSame(0, $responseBody['circuit'][0]['sequence']);
        $this->assertSame($userInfo['id'], $responseBody['circuit'][0]['item_id']);
        $this->assertSame('user', $responseBody['circuit'][0]['item_type']);
        $this->assertSame('Patricia', $responseBody['circuit'][0]['item_firstname']);
        $this->assertSame('PETIT', $responseBody['circuit'][0]['item_lastname']);
        $this->assertSame('Ville de Maarch-les-bains', $responseBody['circuit'][0]['item_entity']);
        $this->assertSame(0, $responseBody['circuit'][0]['viewed']);
        $this->assertEmpty($responseBody['circuit'][0]['process_date']);
        $this->assertEmpty($responseBody['circuit'][0]['process_comment']);
        $this->assertNotEmpty($responseBody['circuit'][0]['labelToDisplay']);
        $this->assertSame(true, $responseBody['circuit'][0]['hasPrivilege']);

        $GLOBALS['login'] = 'ddur';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];

        $response = $listInstanceController->getOpinionCircuitByResId($request, new \Slim\Http\Response(), ['resId' => self::$resourceId]);
        $this->assertSame(403, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Document out of perimeter', $responseBody['errors']);

        $GLOBALS['login'] = 'superadmin';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];
    }

    public function testGetParallelOpinionByResId()
    {
        $listInstanceController = new \Entity\controllers\ListInstanceController();

        //  READ
        $environment  = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request = \Slim\Http\Request::createFromEnvironment($environment);

        $response = $listInstanceController->getParallelOpinionByResId($request, new \Slim\Http\Response(), ['resId' => self::$resourceId]);
        $this->assertSame(200, $response->getStatusCode());

        $responseBody = json_decode((string)$response->getBody(), true);

        $userInfo = \User\models\UserModel::getByLogin(['login' => 'ppetit', 'select' => ['id']]);

        $this->assertIsArray($responseBody);

        $this->assertSame(0, $responseBody[0]['sequence']);
        $this->assertSame('avis', $responseBody[0]['item_mode']);
        $this->assertSame($userInfo['id'], $responseBody[0]['item_id']);
        $this->assertSame('user', $responseBody[0]['item_type']);
        $this->assertSame('Patricia', $responseBody[0]['item_firstname']);
        $this->assertSame('PETIT', $responseBody[0]['item_lastname']);
        $this->assertSame('Ville de Maarch-les-bains', $responseBody[0]['item_entity']);
        $this->assertSame(0, $responseBody[0]['viewed']);
        $this->assertEmpty($responseBody[0]['process_date']);
        $this->assertEmpty($responseBody[0]['process_comment']);
        $this->assertNotEmpty($responseBody[0]['labelToDisplay']);
        $this->assertSame(true, $responseBody[0]['hasPrivilege']);

        $GLOBALS['login'] = 'ddur';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];

        $response = $listInstanceController->getParallelOpinionByResId($request, new \Slim\Http\Response(), ['resId' => self::$resourceId]);
        $this->assertSame(403, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Document out of perimeter', $responseBody['errors']);

        $GLOBALS['login'] = 'superadmin';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];
    }

    public function testUpdate()
    {
        $listInstanceController = new \Entity\controllers\ListInstanceController();
        $environment  = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'PUT']);
        $request = \Slim\Http\Request::createFromEnvironment($environment);

        // Success
        $body = [
            [
                'resId' => self::$resourceId
            ]
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);
        $response = $listInstanceController->update($fullRequest, new \Slim\Http\Response());
        $this->assertSame(204, $response->getStatusCode());

        $body = [
            [
                'resId'         => self::$resourceId,
                'listInstances' => [
                    [
                        'item_id'   => 10,
                        'item_mode' => 'dest',
                        'item_type' => 'user'
                    ]
                ]
            ]
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);
        $response = $listInstanceController->update($fullRequest, new \Slim\Http\Response());
        $this->assertSame(204, $response->getStatusCode());

        // Errors
        $body = [
            [
                'resId'         => self::$resourceId,
                'listInstances' => [
                    'item_id'   => 10,
                    'item_mode' => 'avis',
                    'item_type' => 'user'
                ]
            ]
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);
        $response = $listInstanceController->update($fullRequest, new \Slim\Http\Response());
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Dest is missing', $responseBody['errors']);

        $body = [
            [
                'toto' => self::$resourceId
            ]
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($body, $request);
        $response = $listInstanceController->update($fullRequest, new \Slim\Http\Response());
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('resId is empty', $responseBody['errors']);

        $response = $listInstanceController->update($request, new \Slim\Http\Response());
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Body is not set or not an array', $responseBody['errors']);

        $GLOBALS['login'] = 'ddur';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];

        $response = $listInstanceController->update($request, new \Slim\Http\Response());
        $this->assertSame(403, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Service forbidden', $responseBody['errors']);

        $GLOBALS['login'] = 'superadmin';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];
    }

    public function testDeleteCircuit()
    {
        $listInstanceController = new \Entity\controllers\ListInstanceController();
        $environment  = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'PUT']);
        $request = \Slim\Http\Request::createFromEnvironment($environment);

        // Success
        $response = $listInstanceController->deleteCircuit($request, new \Slim\Http\Response(), ['resId' => self::$resourceId, 'type' => 'visaCircuit']);
        $this->assertSame(204, $response->getStatusCode());

        // Errors
        $response = $listInstanceController->deleteCircuit($request, new \Slim\Http\Response(), ['resId' => self::$resourceId]);
        $this->assertSame(400, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Route params type is empty or not valid', $responseBody['errors']);

        $GLOBALS['login'] = 'ddur';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];

        $response = $listInstanceController->deleteCircuit($request, new \Slim\Http\Response(), ['resId' => self::$resourceId]);
        $this->assertSame(403, $response->getStatusCode());
        $responseBody = json_decode((string)$response->getBody(), true);
        $this->assertSame('Resource out of perimeter', $responseBody['errors']);

        $GLOBALS['login'] = 'superadmin';
        $userInfo = \User\models\UserModel::getByLogin(['login' => $GLOBALS['login'], 'select' => ['id']]);
        $GLOBALS['id'] = $userInfo['id'];
    }

    public function testClean()
    {
        \SrcCore\models\DatabaseModel::delete([
            'table' => 'res_letterbox',
            'where' => ['res_id = ?'],
            'data'  => [self::$resourceId]
        ]);
        \SrcCore\models\DatabaseModel::delete([
            'table' => 'listinstance',
            'where' => ['res_id = ?'],
            'data'  => [self::$resourceId]
        ]);

        $res = \Resource\models\ResModel::getById(['resId' => self::$resourceId, 'select' => ['*']]);
        $this->assertIsArray($res);
        $this->assertEmpty($res);
    }
}
