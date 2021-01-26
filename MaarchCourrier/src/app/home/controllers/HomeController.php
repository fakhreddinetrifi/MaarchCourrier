<?php

/**
 * Copyright Maarch since 2008 under licence GPLv3.
 * See LICENCE.txt file at the root folder for more details.
 * This file is part of Maarch software.
 *
 */

/**
 * @brief Home Controller
 * @author dev@maarch.org
 */

namespace Home\controllers;

use Basket\models\BasketModel;
use Basket\models\RedirectBasketModel;
use Group\models\GroupModel;
use Priority\models\PriorityModel;
use Resource\models\ResModel;
use Slim\Http\Request;
use Slim\Http\Response;
use SrcCore\models\CoreConfigModel;
use SrcCore\models\CurlModel;
use User\models\UserModel;
use Parameter\models\ParameterModel;

class HomeController
{
    public function get(Request $request, Response $response)
    {
        $regroupedBaskets = [];

        $user = UserModel::getById(['id' => $GLOBALS['id'], 'select' => ['preferences', 'external_id']]);

        $redirectedBaskets = RedirectBasketModel::getRedirectedBasketsByUserId(['userId' => $GLOBALS['id']]);
        $groups = UserModel::getGroupsByLogin(['login' => $GLOBALS['login']]);

        $preferences = json_decode($user['preferences'], true);
        if (!empty($preferences['homeGroups'])) {
            $orderGroups   = [];
            $noOrderGroups = [];
            foreach ($groups as $group) {
                $key = array_search($group['id'], $preferences['homeGroups']);
                if ($key === false) {
                    $noOrderGroups[] = $group;
                } else {
                    $orderGroups[$key] = $group;
                }
            }
            ksort($orderGroups);
            $groups = array_merge($orderGroups, $noOrderGroups);
        }

        foreach ($groups as $group) {
            $baskets = BasketModel::getAvailableBasketsByGroupUser([
                'select'        => ['baskets.id', 'baskets.basket_id', 'baskets.basket_name', 'baskets.basket_desc', 'baskets.basket_clause', 'baskets.color', 'users_baskets_preferences.color as pcolor'],
                'userSerialId'  => $GLOBALS['id'],
                'groupId'       => $group['group_id'],
                'groupSerialId' => $group['id']
            ]);

            foreach ($baskets as $kBasket => $basket) {
                $baskets[$kBasket]['owner_user_id'] = $GLOBALS['id'];
                if (!empty($basket['pcolor'])) {
                    $baskets[$kBasket]['color'] = $basket['pcolor'];
                }
                if (empty($baskets[$kBasket]['color'])) {
                    $baskets[$kBasket]['color'] = '#666666';
                }

                $baskets[$kBasket]['redirected'] = false;
                foreach ($redirectedBaskets as $redirectedBasket) {
                    if ($redirectedBasket['basket_id'] == $basket['basket_id'] && $redirectedBasket['group_id'] == $group['id']) {
                        $baskets[$kBasket]['redirected'] = true;
                        $baskets[$kBasket]['redirectedUser'] = $redirectedBasket['userToDisplay'];
                    }
                }

                $baskets[$kBasket]['resourceNumber'] = BasketModel::getResourceNumberByClause(['userId' => $GLOBALS['id'], 'clause' => $basket['basket_clause']]);

                unset($baskets[$kBasket]['pcolor'], $baskets[$kBasket]['basket_clause']);
            }

            if (!empty($baskets)) {
                $regroupedBaskets[] = [
                    'groupSerialId' => $group['id'],
                    'groupId'       => $group['group_id'],
                    'groupDesc'     => $group['group_desc'],
                    'baskets'       => $baskets
                ];
            }
        }

        $assignedBaskets = RedirectBasketModel::getAssignedBasketsByUserId(['userId' => $GLOBALS['id']]);
        foreach ($assignedBaskets as $key => $assignedBasket) {
            $basket = BasketModel::getByBasketId(['select' => ['id', 'basket_clause'], 'basketId' => $assignedBasket['basket_id']]);
            $assignedBaskets[$key]['id'] = $basket['id'];
            $assignedBaskets[$key]['resourceNumber'] = BasketModel::getResourceNumberByClause(['userId' => $assignedBasket['owner_user_id'], 'clause' => $basket['basket_clause']]);
            $assignedBaskets[$key]['uselessGroupId'] = GroupModel::getById(['id' => $assignedBasket['group_id'], 'select' => ['group_id']])['group_id'];
            $assignedBaskets[$key]['ownerLogin'] = UserModel::getById(['id' => $assignedBasket['owner_user_id'], 'select' => ['user_id']])['user_id'];
        }

        $externalId = json_decode($user['external_id'], true);

        $isMaarchParapheurConnected = false;
        $loadedXml = CoreConfigModel::getXmlLoaded(['path' => 'modules/visa/xml/remoteSignatoryBooks.xml']);
        if (!empty($loadedXml)) {
            foreach ($loadedXml->signatoryBook as $value) {
                if ($value->id == "maarchParapheur") {
                    if (!empty($value->url) && !empty($value->userId) && !empty($value->password) && !empty($externalId['maarchParapheur'])) {
                        $isMaarchParapheurConnected = true;
                    }
                    break;
                }
            }
        }

        $homeMessage = ParameterModel::getById(['select' => ['param_value_string'], 'id'=> 'homepage_message']);
        $homeMessage = trim($homeMessage['param_value_string']);

        return $response->withJson([
            'regroupedBaskets'              => $regroupedBaskets,
            'assignedBaskets'               => $assignedBaskets,
            'homeMessage'                   => $homeMessage,
            'isLinkedToMaarchParapheur'     => $isMaarchParapheurConnected
        ]);
    }

    public function getLastRessources(Request $request, Response $response)
    {
        $lastResources = ResModel::getLastResources([
            'select'    => [
                'res_letterbox.alt_identifier',
                'res_letterbox.closing_date',
                'res_letterbox.creation_date',
                'res_letterbox.priority',
                'res_letterbox.process_limit_date',
                'res_letterbox.res_id',
                'res_letterbox.subject',
                'res_letterbox.confidentiality',
                'status.img_filename as status_icon',
                'status.label_status as status_label',
                'status.id as status_id',
                'res_letterbox.filename'
            ],
            'limit'     => 5,
            'userId'    => $GLOBALS['id']
        ]);

        if (!empty($lastResources)) {
            $priorities     = array_column($lastResources, 'priority');
            $prioritiesInfo = PriorityModel::get(['select' => ['id', 'color'], 'where' => ['id in (?)'], 'data' => [$priorities]]);
            $aPriorities    = array_column($prioritiesInfo, 'color', 'id');

            foreach ($lastResources as $key => $lastResource) {
                $lastResources[$key]['hasDocument'] = $lastResource['filename'] != null;
                unset($lastResources[$key]['filename']);
                if (!empty($lastResource['priority'])) {
                    $lastResources[$key]['priority_color'] = $aPriorities[$lastResource['priority']] ?? null;
                }
            }
        }

        return $response->withJson(['lastResources' => $lastResources]);
    }

    public function getMaarchParapheurDocuments(Request $request, Response $response)
    {
        $user = UserModel::getById(['id' => $GLOBALS['id'], 'select' => ['external_id']]);

        $externalId = json_decode($user['external_id'], true);
        if (empty($externalId['maarchParapheur'])) {
            return $response->withStatus(400)->withJson(['errors' => 'User is not linked to Maarch Parapheur']);
        }

        $loadedXml = CoreConfigModel::getXmlLoaded(['path' => 'modules/visa/xml/remoteSignatoryBooks.xml']);
        if (empty($loadedXml)) {
            return $response->withStatus(400)->withJson(['errors' => 'SignatoryBooks configuration file missing']);
        }

        $url      = '';
        $userId   = '';
        $password = '';
        foreach ($loadedXml->signatoryBook as $value) {
            if ($value->id == "maarchParapheur") {
                $url      = rtrim($value->url, '/');
                $userId   = $value->userId;
                $password = $value->password;
                break;
            }
        }

        if (empty($url)) {
            return $response->withStatus(400)->withJson(['errors' => 'Maarch Parapheur configuration missing']);
        }

        $curlResponse = CurlModel::execSimple([
            'url'           => rtrim($url, '/') . '/rest/documents',
            'basicAuth'     => ['user' => $userId, 'password' => $password],
            'headers'       => ['content-type:application/json'],
            'method'        => 'GET',
            'queryParams'   => ['userId' => $externalId['maarchParapheur'], 'limit' => 10]
        ]);

        if ($curlResponse['code'] != '200') {
            if (!empty($curlResponse['response']['errors'])) {
                $errors =  $curlResponse['response']['errors'];
            } else {
                $errors =  $curlResponse['errors'];
            }
            if (empty($errors)) {
                $errors = 'An error occured. Please check your configuration file.';
            }
            return $response->withStatus(400)->withJson(['errors' => $errors]);
        }

        $curlResponse['response']['url'] = $url;
        return $response->withJson($curlResponse['response']);
    }
}
