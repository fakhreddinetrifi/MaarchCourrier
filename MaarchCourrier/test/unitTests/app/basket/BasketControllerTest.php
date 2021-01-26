<?php

/**
* Copyright Maarch since 2008 under licence GPLv3.
* See LICENCE.txt file at the root folder for more details.
* This file is part of Maarch software.
*
*/

use PHPUnit\Framework\TestCase;

class BasketControllerTest extends TestCase
{
    private static $id = null;
    private static $baskets = null;

    public function testCreate()
    {
        $basketController = new \Basket\controllers\BasketController();

        //  CREATE
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'POST']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        $aArgs = [
            'id'                => 'TEST-BASKET123',
            'basket_name'       => 'TEST-BASKET123-NAME',
            'basket_desc'       => 'TEST BASKET123 DESCRIPTION',
            'clause'            => '1=2',
            'isSearchBasket'    => true,
            'color'             => '#123456'
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($aArgs, $request);

        $response     = $basketController->create($fullRequest, new \Slim\Http\Response());
        $responseBody = json_decode((string)$response->getBody());

        $this->assertSame('TEST-BASKET123', $responseBody->basket);

        //  READ
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);
        $response       = $basketController->getById($request, new \Slim\Http\Response(), ['id' => 'TEST-BASKET123']);
        $responseBody   = json_decode((string)$response->getBody());

        $this->assertSame('TEST-BASKET123', $responseBody->basket->basket_id);
        $this->assertSame('TEST-BASKET123-NAME', $responseBody->basket->basket_name);
        $this->assertSame('TEST BASKET123 DESCRIPTION', $responseBody->basket->basket_desc);
        $this->assertSame('1=2', $responseBody->basket->basket_clause);
        $this->assertSame('N', $responseBody->basket->is_visible);
        $this->assertSame('N', $responseBody->basket->flag_notif);
        $this->assertSame('#123456', $responseBody->basket->color);
    }

    public function testUpdate()
    {
        $basketController = new \Basket\controllers\BasketController();

        //  UPDATE
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'PUT']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);
        $aArgs = [
            'basket_name'       => 'TEST-BASKET123-UPDATED',
            'basket_desc'       => 'TEST BASKET123 DESCRIPTION UPDATED',
            'clause'            => '1=3',
            'isSearchBasket'    => false,
            'flagNotif'         => true,
            'color'             => '#111222'
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($aArgs, $request);

        $response     = $basketController->update($fullRequest, new \Slim\Http\Response(), ['id' => 'TEST-BASKET123']);
        $responseBody = json_decode((string)$response->getBody());

        $this->assertSame('success', $responseBody->success);

        //  READ
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);
        $response       = $basketController->getById($request, new \Slim\Http\Response(), ['id' => 'TEST-BASKET123']);
        $responseBody   = json_decode((string)$response->getBody());

        $this->assertSame('TEST-BASKET123', $responseBody->basket->basket_id);
        $this->assertSame('TEST-BASKET123-UPDATED', $responseBody->basket->basket_name);
        $this->assertSame('TEST BASKET123 DESCRIPTION UPDATED', $responseBody->basket->basket_desc);
        $this->assertSame('1=3', $responseBody->basket->basket_clause);
        $this->assertSame('Y', $responseBody->basket->is_visible);
        $this->assertSame('Y', $responseBody->basket->flag_notif);
        $this->assertSame('#111222', $responseBody->basket->color);
    }

    public function testCreateGroup()
    {
        $basketController = new \Basket\controllers\BasketController();

        //  CREATE
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'POST']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        $aArgs = [
            'group_id'      => 'AGENT',
            'list_display'  => [
                'templateColumns' => 0,
                'subInfos' => []
            ],
            'groupActions'  => [
                [
                    'id'                    => '1',
                    'where_clause'          => '1=2',
                    'used_in_basketlist'    => false,
                    'used_in_action_page'   => true,
                    'default_action_list'   => true,
                    'checked'               => true,
                    'redirects'             => [
                        [
                            'entity_id'     => '',
                            'keyword'       => 'MY_ENTITIES',
                            'redirect_mode' => 'ENTITY'
                        ]
                    ]
                ]
            ]
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($aArgs, $request);

        $response     = $basketController->createGroup($fullRequest, new \Slim\Http\Response(), ['id' => 'TEST-BASKET123']);
        $responseBody = json_decode((string)$response->getBody());

        $this->assertSame('success', $responseBody->success);

        //  READ
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);
        $response       = $basketController->getGroups($request, new \Slim\Http\Response(), ['id' => 'TEST-BASKET123']);
        $responseBody   = json_decode((string)$response->getBody());

        $this->assertSame('AGENT', $responseBody->groups[0]->group_id);
        $this->assertSame('TEST-BASKET123', $responseBody->groups[0]->basket_id);
        $this->assertNotEmpty($responseBody->groups[0]->list_display);
        $this->assertIsArray($responseBody->groups[0]->list_display->subInfos);
        $this->assertSame(0, $responseBody->groups[0]->list_display->templateColumns);
        $this->assertEmpty($responseBody->groups[0]->list_display->subInfo);
        $this->assertIsArray($responseBody->groups[0]->groupActions);
        $this->assertNotNull($responseBody->groups[0]->groupActions);
        foreach ($responseBody->groups[0]->groupActions as $groupAction) {
            if ($groupAction->id == 1) {
                $this->assertSame(1, $groupAction->id);
                $this->assertSame('1=2', $groupAction->where_clause);
                $this->assertSame('N', $groupAction->used_in_basketlist);
                $this->assertSame('Y', $groupAction->used_in_action_page);
                $this->assertSame('Y', $groupAction->default_action_list);
                $this->assertIsArray($groupAction->redirects);
                $this->assertNotNull($groupAction->redirects);
                $this->assertSame('', $groupAction->redirects[0]->entity_id);
                $this->assertSame('MY_ENTITIES', $groupAction->redirects[0]->keyword);
                $this->assertSame('ENTITY', $groupAction->redirects[0]->redirect_mode);
            }
        }

        $this->assertIsArray($responseBody->allGroups);
        $this->assertNotNull($responseBody->allGroups);

        $users = \Group\models\GroupModel::getUsersById(['select' => ['id'], 'id' => 2]);
        $group = \Group\models\GroupModel::getByGroupId(['select' => ['id'], 'groupId' => 'AGENT']);
        foreach ($users as $user) {
            $preference = \User\models\UserBasketPreferenceModel::get([
                'select'    => ['display'],
                'where'     => ['user_serial_id = ?', 'group_serial_id = ?', 'basket_id = ?'],
                'data'      => [$user['id'], $group['id'], 'TEST-BASKET123']
            ]);
            $this->assertNotNull($preference[0]);
            $this->assertSame(true, $preference[0]['display']);
        }
    }

    public function testUpdateGroup()
    {
        $basketController = new \Basket\controllers\BasketController();

        //  CREATE
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'PUT']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        $aArgs = [
            'list_display' => [
                'templateColumns' => 2,
                'subInfos'        => [['value' => 'getPriority', 'cssClasses' => ['class1', 'class2']], ['value' => 'getCategory', 'cssClasses' => ['class3', 'class4']]]
            ],
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($aArgs, $request);

        $response     = $basketController->updateGroup($fullRequest, new \Slim\Http\Response(), ['id' => 'TEST-BASKET123', 'groupId' => 'AGENT']);
        $responseBody = json_decode((string)$response->getBody());

        $this->assertSame('success', $responseBody->success);

        //  READ
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);
        $response       = $basketController->getGroups($request, new \Slim\Http\Response(), ['id' => 'TEST-BASKET123']);
        $responseBody   = json_decode((string)$response->getBody());

        $this->assertSame('AGENT', $responseBody->groups[0]->group_id);
        $this->assertSame('TEST-BASKET123', $responseBody->groups[0]->basket_id);
        $this->assertSame(2, $responseBody->groups[0]->list_display->templateColumns);
        $this->assertSame('getPriority', $responseBody->groups[0]->list_display->subInfos[0]->value);
        $this->assertSame('class1', $responseBody->groups[0]->list_display->subInfos[0]->cssClasses[0]);
        $this->assertSame('class2', $responseBody->groups[0]->list_display->subInfos[0]->cssClasses[1]);
        $this->assertSame('getCategory', $responseBody->groups[0]->list_display->subInfos[1]->value);
        $this->assertSame('class3', $responseBody->groups[0]->list_display->subInfos[1]->cssClasses[0]);
        $this->assertSame('class4', $responseBody->groups[0]->list_display->subInfos[1]->cssClasses[1]);
    }

    public function testUpdateGroupActions()
    {
        $basketController = new \Basket\controllers\BasketController();

        //  CREATE
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'PUT']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        $aArgs = [
            'groupActions'  => [
                [
                    'id'                    => '1',
                    'where_clause'          => '1=1',
                    'used_in_basketlist'    => true,
                    'used_in_action_page'   => true,
                    'default_action_list'   => true,
                    'checked'               => true,
                    'redirects'             => [
                        [
                            'entity_id'     => '',
                            'keyword'       => 'ALL_ENTITIES',
                            'redirect_mode' => 'ENTITY'
                        ]
                    ]
                ],
                [
                    'id'                    => '4',
                    'where_clause'          => '1=4',
                    'used_in_basketlist'    => false,
                    'used_in_action_page'   => true,
                    'default_action_list'   => false,
                    'checked'               => true,
                    'redirects'             => [
                        [
                            'entity_id'     => 'PSO',
                            'keyword'       => '',
                            'redirect_mode' => 'ENTITY'
                        ],
                        [
                            'entity_id'     => 'PSF',
                            'keyword'       => '',
                            'redirect_mode' => 'USERS'
                        ]
                    ]
                ]
            ]
        ];
        $fullRequest = \httpRequestCustom::addContentInBody($aArgs, $request);

        $response     = $basketController->updateGroupActions($fullRequest, new \Slim\Http\Response(), ['id' => 'TEST-BASKET123', 'groupId' => 'AGENT']);
        $responseBody = json_decode((string)$response->getBody());

        $this->assertSame('success', $responseBody->success);

        //  READ
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);
        $response       = $basketController->getGroups($request, new \Slim\Http\Response(), ['id' => 'TEST-BASKET123']);
        $responseBody   = json_decode((string)$response->getBody());

        $this->assertSame('AGENT', $responseBody->groups[0]->group_id);
        $this->assertSame('TEST-BASKET123', $responseBody->groups[0]->basket_id);
        $this->assertIsArray($responseBody->groups[0]->groupActions);
        $this->assertNotNull($responseBody->groups[0]->groupActions);

        foreach ($responseBody->groups[0]->groupActions as $groupAction) {
            if ($groupAction->id == 1) {
                $this->assertSame(1, $groupAction->id);
                $this->assertSame('1=1', $groupAction->where_clause);
                $this->assertSame('Y', $groupAction->used_in_basketlist);
                $this->assertSame('Y', $groupAction->used_in_action_page);
                $this->assertSame('Y', $groupAction->default_action_list);
                $this->assertIsArray($groupAction->redirects);
                $this->assertNotNull($groupAction->redirects);
                $this->assertSame('', $groupAction->redirects[0]->entity_id);
                $this->assertSame('ALL_ENTITIES', $groupAction->redirects[0]->keyword);
                $this->assertSame('ENTITY', $groupAction->redirects[0]->redirect_mode);
            } elseif ($groupAction->id == 4) {
                $this->assertSame(4, $groupAction->id);
                $this->assertSame('1=4', $groupAction->where_clause);
                $this->assertSame('N', $groupAction->used_in_basketlist);
                $this->assertSame('Y', $groupAction->used_in_action_page);
                $this->assertSame('N', $groupAction->default_action_list);
                $this->assertIsArray($groupAction->redirects);
                $this->assertNotNull($groupAction->redirects);
                $this->assertSame('PSO', $groupAction->redirects[0]->entity_id);
                $this->assertSame('', $groupAction->redirects[0]->keyword);
                $this->assertSame('ENTITY', $groupAction->redirects[0]->redirect_mode);
                $this->assertSame('PSF', $groupAction->redirects[1]->entity_id);
                $this->assertSame('', $groupAction->redirects[1]->keyword);
                $this->assertSame('USERS', $groupAction->redirects[1]->redirect_mode);
            }
        }
    }

    public function testDeleteGroup()
    {
        $basketController = new \Basket\controllers\BasketController();

        //  DELETE
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'DELETE']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);
        $response       = $basketController->deleteGroup($request, new \Slim\Http\Response(), ['id' => 'TEST-BASKET123', 'groupId' => 'AGENT']);
        $responseBody   = json_decode((string)$response->getBody());

        $this->assertSame('success', $responseBody->success);

        //  READ
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);
        $response       = $basketController->getGroups($request, new \Slim\Http\Response(), ['id' => 'TEST-BASKET123']);
        $responseBody   = json_decode((string)$response->getBody());

        $this->assertEmpty($responseBody->groups);
    }

    public function testGet()
    {
        $basketController = new \Basket\controllers\BasketController();

        //  GET
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);
        $response       = $basketController->get($request, new \Slim\Http\Response());
        $responseBody   = json_decode((string)$response->getBody());

        $this->assertIsArray($responseBody->baskets);
        $this->assertNotNull($responseBody->baskets);
    }

    public function testDelete()
    {
        $basketController = new \Basket\controllers\BasketController();

        //  DELETE
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'DELETE']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);
        $response       = $basketController->delete($request, new \Slim\Http\Response(), ['id' => 'TEST-BASKET123']);
        $responseBody   = json_decode((string)$response->getBody());

        $this->assertIsArray($responseBody->baskets);

        //  READ
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);
        $response       = $basketController->getById($request, new \Slim\Http\Response(), ['id' => 'TEST-BASKET123']);
        $responseBody   = json_decode((string)$response->getBody());

        $this->assertSame('Basket not found', $responseBody->errors);
    }

    public function testGetSorted()
    {
        $basketController = new \Basket\controllers\BasketController();

        //  READ
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'GET']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);
        $response       = $basketController->getSorted($request, new \Slim\Http\Response());
        $responseBody   = json_decode((string)$response->getBody());

        $this->assertIsArray($responseBody->baskets);

        self::$id = $responseBody->baskets[0]->basket_id;
        self::$baskets = $responseBody->baskets;
    }

    public function testUpdateSort()
    {
        $basketController = new \Basket\controllers\BasketController();

        //  PUT
        $environment    = \Slim\Http\Environment::mock(['REQUEST_METHOD' => 'PUT']);
        $request        = \Slim\Http\Request::createFromEnvironment($environment);

        // DOWN
        $firstBasket = self::$baskets[0];
        self::$baskets[0] = self::$baskets[1];
        self::$baskets[1] = $firstBasket;

        $aArgs = self::$baskets;

        $fullRequest = \httpRequestCustom::addContentInBody($aArgs, $request);

        $response       = $basketController->updateSort($fullRequest, new \Slim\Http\Response(), ['id' => self::$id]);
        $responseBody   = json_decode((string)$response->getBody());

        $this->assertIsArray($responseBody->baskets);
        $this->assertSame(self::$id, $responseBody->baskets[1]->basket_id);

        // UP
        self::$baskets = $responseBody->baskets;

        $firstBasket = self::$baskets[0];
        self::$baskets[0] = self::$baskets[1];
        self::$baskets[1] = $firstBasket;

        $aArgs = self::$baskets;

        $fullRequest = \httpRequestCustom::addContentInBody($aArgs, $request);

        $response       = $basketController->updateSort($fullRequest, new \Slim\Http\Response(), ['id' => self::$id]);
        $responseBody   = json_decode((string)$response->getBody());

        $this->assertIsArray($responseBody->baskets);
        $this->assertSame(self::$id, $responseBody->baskets[0]->basket_id);
    }
}
