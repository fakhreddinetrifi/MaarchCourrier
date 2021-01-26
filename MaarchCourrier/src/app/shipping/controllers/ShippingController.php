<?php

/**
* Copyright Maarch since 2008 under licence GPLv3.
* See LICENCE.txt file at the root folder for more details.
* This file is part of Maarch software.
*
*/

/**
* @brief Shipping Controller
* @author dev@maarch.org
*/

namespace Shipping\controllers;

use Attachment\models\AttachmentModel;
use Entity\models\EntityModel;
use Resource\controllers\ResController;
use Respect\Validation\Validator;
use Shipping\models\ShippingModel;
use Slim\Http\Request;
use Slim\Http\Response;
use User\models\UserModel;

class ShippingController
{
    public static function getByResId(Request $request, Response $response, array $args)
    {
        if (!Validator::intVal()->validate($args['resId']) || !ResController::hasRightByResId(['resId' => [$args['resId']], 'userId' => $GLOBALS['id']])) {
            return $response->withStatus(403)->withJson(['errors' => 'Document out of perimeter']);
        }

        $attachments = AttachmentModel::get([
            'select' => ['res_id'],
            'where'  => ['res_id_master = ?'],
            'data'   => [$args['resId']]
        ]);
        $attachments = array_column($attachments, 'res_id');

        $where = '(document_id = ? and document_type = ?)';
        $data  = [$args['resId'], 'resource'];

        if (!empty($attachments)) {
            $where .= ' or (document_id in (?) and document_type = ?)';
            $data[] = $attachments;
            $data[] = 'attachment';
        }

        $shippingsModel = ShippingModel::get([
            'select' => ['*'],
            'where'  => [$where],
            'data'   => $data
        ]);

        $shippings = [];

        foreach ($shippingsModel as $shipping) {
            $recipientEntityLabel = EntityModel::getById(['id' => $shipping['recipient_entity_id'], 'select' => ['entity_label']]);
            $recipientEntityLabel = $recipientEntityLabel['entity_label'];
            $recipients = json_decode($shipping['recipients'], true);
            $contacts = [];
            foreach ($recipients as $recipient) {
                $contacts[] = ['company' => $recipient[1], 'contactLabel' => $recipient[2]];
            }

            $shippings[] = [
                'id'                    => $shipping['id'],
                'documentId'            => $shipping['document_id'],
                'documentType'          => $shipping['document_type'],
                'userId'                => $shipping['user_id'],
                'userLabel'             => UserModel::getLabelledUserById(['id' => $shipping['user_id']]),
                'fee'                   => $shipping['fee'],
                'creationDate'          => $shipping['creation_date'],
                'recipientEntityId'     => $shipping['recipient_entity_id'],
                'recipientEntityLabel'  => $recipientEntityLabel,
                'recipients'            => $contacts
            ];
        }

        return $response->withJson($shippings);
    }
}
