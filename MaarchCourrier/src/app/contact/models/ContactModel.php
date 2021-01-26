<?php

/**
* Copyright Maarch since 2008 under licence GPLv3.
* See LICENCE.txt file at the root folder for more details.
* This file is part of Maarch software.
*
*/

/**
* @brief Contact Model
* @author dev@maarch.org
*/

namespace Contact\models;

use Resource\models\ResourceContactModel;
use SrcCore\models\CoreConfigModel;
use SrcCore\models\DatabaseModel;
use SrcCore\models\ValidatorModel;

class ContactModel
{
    public static function get(array $args)
    {
        ValidatorModel::notEmpty($args, ['select']);
        ValidatorModel::arrayType($args, ['select', 'where', 'data', 'orderBy']);
        ValidatorModel::intType($args, ['limit']);

        $contacts = DatabaseModel::select([
            'select'    => $args['select'],
            'table'     => ['contacts'],
            'where'     => empty($args['where']) ? [] : $args['where'],
            'data'      => empty($args['data']) ? [] : $args['data'],
            'order_by'  => empty($args['orderBy']) ? [] : $args['orderBy'],
            'offset'    => empty($args['offset']) ? 0 : $args['offset'],
            'limit'     => empty($args['limit']) ? 0 : $args['limit']
        ]);

        return $contacts;
    }

    public static function getById(array $args)
    {
        ValidatorModel::notEmpty($args, ['id', 'select']);
        ValidatorModel::intVal($args, ['id']);
        ValidatorModel::arrayType($args, ['select']);

        $contact = DatabaseModel::select([
            'select'    => $args['select'],
            'table'     => ['contacts'],
            'where'     => ['id = ?'],
            'data'      => [$args['id']],
        ]);

        if (empty($contact[0])) {
            return [];
        }

        return $contact[0];
    }

    public static function create(array $args)
    {
        ValidatorModel::notEmpty($args, ['creator']);
        ValidatorModel::intVal($args, ['creator']);

        $nextSequenceId = DatabaseModel::getNextSequenceValue(['sequenceId' => 'contacts_id_seq']);
        $args['id'] = $nextSequenceId;

        DatabaseModel::insert([
            'table'         => 'contacts',
            'columnsValues' => $args
        ]);

        return $nextSequenceId;
    }

    public static function update(array $args)
    {
        ValidatorModel::notEmpty($args, ['where', 'data']);
        ValidatorModel::arrayType($args, ['set', 'postSet', 'where', 'data']);

        DatabaseModel::update([
            'table'     => 'contacts',
            'set'       => $args['set'],
            'postSet'   => $args['postSet'],
            'where'     => $args['where'],
            'data'      => $args['data']
        ]);

        return true;
    }

    public static function delete(array $args)
    {
        ValidatorModel::notEmpty($args, ['where', 'data']);
        ValidatorModel::arrayType($args, ['where', 'data']);

        DatabaseModel::delete([
            'table' => 'contacts',
            'where' => $args['where'],
            'data'  => $args['data']
        ]);

        return true;
    }

    public static function purgeContact($aArgs)
    {
        ValidatorModel::notEmpty($aArgs, ['id']);
        ValidatorModel::intVal($aArgs, ['id']);

        $count = ResourceContactModel::get(['select' => ['count(1)'], 'where' => ['item_id = ?', 'type= ?'], 'data' => [$aArgs['id'], 'contact']]);

        if ($count[0]['count'] < 1) {
            ContactModel::delete(['where' => ['id = ?'], 'data' => [$aArgs['id']]]);
        }
    }

    public static function getCivilities()
    {
        static $civilities;

        if (!empty($civilities)) {
            return $civilities;
        }

        $civilities = [];

        $loadedXml = CoreConfigModel::getXmlLoaded(['path' => 'apps/maarch_entreprise/xml/entreprise.xml']);
        if ($loadedXml != false) {
            $result = $loadedXml->xpath('/ROOT/titles');
            foreach ($result as $title) {
                foreach ($title as $value) {
                    if (!empty((string) $value->id)) {
                        $civilities[(string) $value->id] = [
                            'label'         => (string)$value->label,
                            'abbreviation'  => (string)$value->abbreviation,
                        ];
                    }
                }
            }
        }

        return $civilities;
    }

    public static function getCivilityLabel(array $args)
    {
        ValidatorModel::stringType($args, ['civilityId']);

        $civilities = ContactModel::getCivilities();
        if (!empty($civilities[$args['civilityId']])) {
            return $civilities[$args['civilityId']]['label'];
        }

        return '';
    }

    public static function getCivilityId(array $args)
    {
        ValidatorModel::stringType($args, ['civilityLabel']);

        if (!empty($args['civilityLabel'])) {
            $aCivility = ContactModel::getCivilities();
            foreach ($aCivility as $civilityId => $civility) {
                if (mb_strtolower($civility['label']) == mb_strtolower($args['civilityLabel'])) {
                    return $civilityId;
                }
            }
        }
        return null;
    }
}
