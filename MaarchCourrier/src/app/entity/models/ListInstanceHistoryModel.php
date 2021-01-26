<?php

/**
 * Copyright Maarch since 2008 under licence GPLv3.
 * See LICENCE.txt file at the root folder for more details.
 * This file is part of Maarch software.
 *
 */

/**
 * @brief List Instance History Model Abstract
 * @author dev@maarch.org
 */

namespace Entity\models;

use SrcCore\models\ValidatorModel;
use SrcCore\models\DatabaseModel;

class ListInstanceHistoryModel
{
    public static function create(array $args)
    {
        ValidatorModel::notEmpty($args, ['resId', 'userId']);
        ValidatorModel::intVal($args, ['resId', 'userId']);

        $nextSequenceId = DatabaseModel::getNextSequenceValue(['sequenceId' => 'listinstance_history_id_seq']);

        DatabaseModel::insert([
            'table'         => 'listinstance_history',
            'columnsValues' => [
                'listinstance_history_id'   => $nextSequenceId,
                'coll_id'                   => 'letterbox_coll',
                'res_id'                    => $args['resId'],
                'user_id'                   => $args['userId'],
                'updated_date'              => 'CURRENT_TIMESTAMP'
            ]
        ]);

        return $nextSequenceId;
    }

    public static function delete(array $args)
    {
        ValidatorModel::notEmpty($args, ['where', 'data']);
        ValidatorModel::arrayType($args, ['where', 'data']);

        DatabaseModel::delete([
            'table' => 'listinstance_history',
            'where' => $args['where'],
            'data'  => $args['data']
        ]);

        return true;
    }
}
