<?php

/**
 * Copyright Maarch since 2008 under licence GPLv3.
 * See LICENCE.txt file at the root folder for more details.
 * This file is part of Maarch software.
 *
 */

/**
 * @brief   GroupBasket Model Abstract
 * @author  dev@maarch.org
 */

namespace Basket\models;

use Group\models\GroupModel;
use SrcCore\models\ValidatorModel;
use SrcCore\models\DatabaseModel;

abstract class GroupBasketModelAbstract
{
    public static function get(array $aArgs)
    {
        ValidatorModel::arrayType($aArgs, ['select', 'where', 'data', 'orderBy']);
        ValidatorModel::intType($aArgs, ['limit']);

        $aGroupsBaskets = DatabaseModel::select([
            'select'    => empty($aArgs['select']) ? ['*'] : $aArgs['select'],
            'table'     => ['groupbasket'],
            'where'     => empty($aArgs['where']) ? [] : $aArgs['where'],
            'data'      => empty($aArgs['data']) ? [] : $aArgs['data'],
            'order_by'  => empty($aArgs['orderBy']) ? [] : $aArgs['orderBy'],
            'limit'     => empty($aArgs['limit']) ? 0 : $aArgs['limit']
        ]);

        return $aGroupsBaskets;
    }

    public static function createGroupBasket(array $aArgs)
    {
        ValidatorModel::notEmpty($aArgs, ['basketId', 'groupId', 'listDisplay']);
        ValidatorModel::stringType($aArgs, ['basketId', 'groupId', 'listDisplay']);

        DatabaseModel::insert([
            'table'         => 'groupbasket',
            'columnsValues' => [
                'basket_id'         => $aArgs['basketId'],
                'group_id'          => $aArgs['groupId'],
                'list_display'      => $aArgs['listDisplay']
            ]
        ]);

        return true;
    }

    public static function update(array $aArgs)
    {
        ValidatorModel::notEmpty($aArgs, ['set', 'where', 'data']);
        ValidatorModel::arrayType($aArgs, ['set', 'where', 'data']);

        DatabaseModel::update([
            'table' => 'groupbasket',
            'set'   => $aArgs['set'],
            'where' => $aArgs['where'],
            'data'  => $aArgs['data']
        ]);

        return true;
    }

    public static function deleteGroupBasket(array $aArgs)
    {
        ValidatorModel::notEmpty($aArgs, ['basketId', 'groupId']);
        ValidatorModel::stringType($aArgs, ['basketId', 'groupId']);
        ValidatorModel::boolType($aArgs, ['preferences', 'groupBasket']);

        $group = GroupModel::getByGroupId(['select' => ['id'], 'groupId' => $aArgs['groupId']]);

        if (!empty($aArgs['groupBasket'])) {
            DatabaseModel::delete([
                'table' => 'groupbasket',
                'where' => ['basket_id = ?', 'group_id = ?'],
                'data'  => [$aArgs['basketId'], $aArgs['groupId']]
            ]);
        }
        DatabaseModel::delete([
            'table' => 'actions_groupbaskets',
            'where' => ['basket_id = ?', 'group_id = ?'],
            'data'  => [$aArgs['basketId'], $aArgs['groupId']]
        ]);
        DatabaseModel::delete([
            'table' => 'groupbasket_redirect',
            'where' => ['basket_id = ?', 'group_id = ?'],
            'data'  => [$aArgs['basketId'], $aArgs['groupId']]
        ]);

        if (!empty($aArgs['preferences'])) {
            DatabaseModel::delete([
                'table' => 'users_baskets_preferences',
                'where' => ['basket_id = ?', 'group_serial_id = ?'],
                'data'  => [$aArgs['basketId'], $group['id']]
            ]);
        }

        return true;
    }

    public static function getBasketsByGroupId(array $aArgs)
    {
        ValidatorModel::notEmpty($aArgs, ['groupId']);
        ValidatorModel::stringType($aArgs, ['groupId']);
        ValidatorModel::arrayType($aArgs, ['select']);

        $aGroupsBaskets = DatabaseModel::select([
            'select'    => empty($aArgs['select']) ? ['*'] : $aArgs['select'],
            'table'     => ['groupbasket, baskets'],
            'where'     => ['groupbasket.group_id = ?', 'groupbasket.basket_id = baskets.basket_id'],
            'data'      => [$aArgs['groupId']]
        ]);

        return $aGroupsBaskets;
    }
}
