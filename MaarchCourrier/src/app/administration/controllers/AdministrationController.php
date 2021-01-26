<?php

/**
* Copyright Maarch since 2008 under licence GPLv3.
* See LICENCE.txt file at the root folder for more details.
* This file is part of Maarch software.
*
*/

/**
* @brief Administration Controller
* @author dev@maarch.org
*/

namespace Administration\controllers;

use Entity\models\EntityModel;
use Group\controllers\PrivilegeController;
use Group\models\GroupModel;
use Slim\Http\Request;
use Slim\Http\Response;
use User\controllers\UserController;
use User\models\UserEntityModel;
use User\models\UserModel;

class AdministrationController
{
    public function getDetails(Request $request, Response $response)
    {
        $count = [];

        if (PrivilegeController::hasPrivilege(['privilegeId' => 'admin_users', 'userId' => $GLOBALS['id']])) {
            if (UserController::isRoot(['id' => $GLOBALS['id']])) {
                $users = UserModel::get([
                    'select'    => [1],
                    'where'     => ['status != ?'],
                    'data'      => ['DEL']
                ]);
            } else {
                $entities = EntityModel::getAllEntitiesByUserId(['userId' => $GLOBALS['id']]);
                $users = [];
                if (!empty($entities)) {
                    $users = UserEntityModel::getWithUsers([
                        'select'    => ['DISTINCT users.id', 'users.user_id', 'firstname', 'lastname', 'status', 'mail'],
                        'where'     => ['users_entities.entity_id in (?)', 'status != ?'],
                        'data'      => [$entities, 'DEL']
                    ]);
                }
                $usersNoEntities = UserEntityModel::getUsersWithoutEntities(['select' => ['id', 'users.user_id', 'firstname', 'lastname', 'status', 'mail']]);
                $users = array_merge($users, $usersNoEntities);
            }
            $count['users'] = count($users);
        }

        if (PrivilegeController::hasPrivilege(['privilegeId' => 'admin_groups', 'userId' => $GLOBALS['id']])) {
            $groups = GroupModel::get(['select' => [1]]);
            $count['groups'] = count($groups);
        }

        if (PrivilegeController::hasPrivilege(['privilegeId' => 'manage_entities', 'userId' => $GLOBALS['id']])) {
            $entities = EntityModel::get(['select' => [1]]);
            $count['entities'] = count($entities);
        }

        return $response->withJson(['count' => $count]);
    }

}
