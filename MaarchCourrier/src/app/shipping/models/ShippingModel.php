<?php

/**
 * Copyright Maarch since 2008 under licence GPLv3.
 * See LICENCE.txt file at the root folder for more details.
 * This file is part of Maarch software.
 *
 */

/**
* @brief   Shipping Model
* @author  dev@maarch.org
*/

namespace Shipping\models;

use SrcCore\models\ValidatorModel;
use SrcCore\models\DatabaseModel;

class ShippingModel
{
    public static function create(array $args)
    {
        ValidatorModel::notEmpty($args, ['userId', 'documentId', 'documentType', 'accountId', 'recipients']);
        ValidatorModel::intVal($args, ['userId', 'documentId', 'recipientEntityId']);
        ValidatorModel::stringType($args, ['accountId', 'documentType', 'recipients']);

        DatabaseModel::insert([
            'table'         => 'shippings',
            'columnsValues' => [
                'user_id'               => $args['userId'],
                'document_id'           => $args['documentId'],
                'document_type'         => $args['documentType'],
                'options'               => $args['options'],
                'fee'                   => $args['fee'],
                'recipient_entity_id'   => $args['recipientEntityId'],
                'recipients'            => $args['recipients'],
                'account_id'            => $args['accountId'],
                'creation_date'         => 'CURRENT_TIMESTAMP'
            ]
        ]);

        return true;
    }

    public static function get(array $args)
    {
        ValidatorModel::notEmpty($args, ['select']);
        ValidatorModel::arrayType($args, ['select', 'where', 'data', 'orderBy']);
        ValidatorModel::intType($args, ['limit']);

        $shippings = DatabaseModel::select([
            'select'    => $args['select'],
            'table'     => ['shippings'],
            'where'     => empty($args['where']) ? [] : $args['where'],
            'data'      => empty($args['data']) ? [] : $args['data'],
            'order_by'  => empty($args['orderBy']) ? [] : $args['orderBy'],
            'offset'    => empty($args['offset']) ? 0 : $args['offset'],
            'limit'     => empty($args['limit']) ? 0 : $args['limit']
        ]);

        return $shippings;
    }
}
