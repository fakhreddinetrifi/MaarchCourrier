<?php
/**
 * Copyright Maarch since 2008 under licence GPLv3.
 * See LICENCE.txt file at the root folder for more details.
 * This file is part of Maarch software.
 *
 */

/**
 * @brief Contact Group Abstract Model
 * @author dev@maarch.org
 */

namespace Contact\models;

use SrcCore\models\ValidatorModel;
use SrcCore\models\DatabaseModel;

abstract class ContactGroupModelAbstract
{
    public static function get(array $args = [])
    {
        ValidatorModel::arrayType($args, ['select', 'where', 'data', 'orderBy']);
        ValidatorModel::intType($args, ['limit']);

        $contactGroups = DatabaseModel::select([
            'select'    => empty($args['select']) ? ['*'] : $args['select'],
            'table'     => ['contacts_groups'],
            'where'     => empty($args['where']) ? [] : $args['where'],
            'data'      => empty($args['data']) ? [] : $args['data'],
            'order_by'  => empty($args['orderBy']) ? [] : $args['orderBy'],
            'limit'     => empty($args['limit']) ? 0 : $args['limit']
        ]);

        return $contactGroups;
    }

    public static function getById(array $aArgs)
    {
        ValidatorModel::notEmpty($aArgs, ['id']);
        ValidatorModel::intVal($aArgs, ['id']);
        ValidatorModel::arrayType($aArgs, ['select']);

        $aContactGroup = DatabaseModel::select([
            'select'    => empty($aArgs['select']) ? ['*'] : $aArgs['select'],
            'table'     => ['contacts_groups'],
            'where'     => ['id = ?'],
            'data'      => [$aArgs['id']]
        ]);

        return $aContactGroup[0];
    }

    public static function create(array $aArgs)
    {
        ValidatorModel::notEmpty($aArgs, ['label', 'description', 'public', 'owner']);
        ValidatorModel::stringType($aArgs, ['label', 'description', 'public']);
        ValidatorModel::intVal($aArgs, ['owner']);

        $nextSequenceId = DatabaseModel::getNextSequenceValue(['sequenceId' => 'contacts_groups_id_seq']);
        DatabaseModel::insert([
            'table'         => 'contacts_groups',
            'columnsValues' => [
                'id'            => $nextSequenceId,
                'label'         => $aArgs['label'],
                'description'   => $aArgs['description'],
                'public'        => $aArgs['public'],
                'owner'         => $aArgs['owner']
            ]
        ]);

        return $nextSequenceId;
    }

    public static function update(array $aArgs)
    {
        ValidatorModel::notEmpty($aArgs, ['id', 'label', 'description', 'public']);
        ValidatorModel::stringType($aArgs, ['label', 'description', 'public']);
        ValidatorModel::intVal($aArgs, ['id']);

        DatabaseModel::update([
            'table'     => 'contacts_groups',
            'set'       => [
                'label'         => $aArgs['label'],
                'description'   => $aArgs['description'],
                'public'        => $aArgs['public']
            ],
            'where'     => ['id = ?'],
            'data'      => [$aArgs['id']]
        ]);

        return true;
    }

    public static function delete(array $aArgs)
    {
        ValidatorModel::notEmpty($aArgs, ['id']);
        ValidatorModel::intVal($aArgs, ['id']);

        DatabaseModel::delete([
            'table' => 'contacts_groups',
            'where' => ['id = ?'],
            'data'  => [$aArgs['id']]
        ]);
        DatabaseModel::delete([
            'table' => 'contacts_groups_lists',
            'where' => ['contacts_groups_id = ?'],
            'data'  => [$aArgs['id']]
        ]);

        return true;
    }

    public static function getListById(array $aArgs)
    {
        ValidatorModel::notEmpty($aArgs, ['id']);
        ValidatorModel::intVal($aArgs, ['id']);
        ValidatorModel::arrayType($aArgs, ['select']);

        $aList = DatabaseModel::select([
            'select'    => empty($aArgs['select']) ? ['*'] : $aArgs['select'],
            'table'     => ['contacts_groups_lists'],
            'where'     => ['contacts_groups_id = ?'],
            'data'      => [$aArgs['id']]
        ]);

        return $aList;
    }

    public static function addContact(array $aArgs)
    {
        ValidatorModel::notEmpty($aArgs, ['id', 'contactId']);
        ValidatorModel::intVal($aArgs, ['id', 'contactId']);

        DatabaseModel::insert([
            'table'         => 'contacts_groups_lists',
            'columnsValues' => [
                'contacts_groups_id' => $aArgs['id'],
                'contact_id'         => $aArgs['contactId']
            ]
        ]);

        return true;
    }

    public static function deleteContact(array $aArgs)
    {
        ValidatorModel::notEmpty($aArgs, ['id', 'contactId']);
        ValidatorModel::intVal($aArgs, ['id', 'contactId']);

        DatabaseModel::delete([
            'table' => 'contacts_groups_lists',
            'where' => ['contacts_groups_id = ?', 'contact_id = ?'],
            'data'  => [$aArgs['id'], $aArgs['contactId']]
        ]);

        return true;
    }

    public static function deleteByContactId(array $aArgs)
    {
        ValidatorModel::notEmpty($aArgs, ['contactId']);
        ValidatorModel::intVal($aArgs, ['contactId']);

        DatabaseModel::delete([
            'table' => 'contacts_groups_lists',
            'where' => ['contact_id = ?'],
            'data'  => [$aArgs['contactId']]
        ]);

        return true;
    }
}
