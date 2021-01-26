<?php

/**
* Copyright Maarch since 2008 under licence GPLv3.
* See LICENCE.txt file at the root folder for more details.
* This file is part of Maarch software.

* @brief   PreProcessActionSEDATrait
* @author  dev <dev@maarch.org>
* @ingroup core
*/

namespace ExportSeda\controllers;

use Respect\Validation\Validator;
use Action\controllers\PreProcessActionController;
use Attachment\models\AttachmentModel;
use Docserver\models\DocserverModel;
use Docserver\models\DocserverTypeModel;
use MessageExchange\models\MessageExchangeModel;
use Resource\controllers\ResController;
use Resource\controllers\ResourceListController;
use Resource\controllers\StoreController;
use Resource\models\ResModel;
use Slim\Http\Request;
use Slim\Http\Response;

trait PreProcessActionSEDATrait
{
    public function checkAcknowledgementRecordManagement(Request $request, Response $response, array $args)
    {
        $body = $request->getParsedBody();

        if (!Validator::arrayType()->notEmpty()->validate($body['resources'])) {
            return $response->withStatus(400)->withJson(['errors' => 'Body resources is empty or not an array']);
        }

        $errors = ResourceListController::listControl(['groupId' => $args['groupId'], 'userId' => $args['userId'], 'basketId' => $args['basketId'], 'currentUserId' => $GLOBALS['id']]);
        if (!empty($errors['errors'])) {
            return $response->withStatus($errors['code'])->withJson(['errors' => $errors['errors']]);
        }

        $body['resources'] = array_slice($body['resources'], 0, 500);
        if (!ResController::hasRightByResId(['resId' => $body['resources'], 'userId' => $GLOBALS['id']])) {
            return $response->withStatus(403)->withJson(['errors' => 'Document out of perimeter']);
        }

        $resourcesInformations = ['success' => [], 'errors' => []];
        $body['resources'] = PreProcessActionController::getNonLockedResources(['resources' => $body['resources'], 'userId' => $GLOBALS['id']]);

        $attachments = AttachmentModel::get([
            'select' => ['res_id_master', 'path', 'filename', 'docserver_id', 'fingerprint'],
            'where'  => ['res_id_master in (?)', 'attachment_type = ?', 'status = ?'],
            'data'   => [$body['resources'], 'acknowledgement_record_management', 'TRA']
        ]);
        $resourcesAcknowledgement = array_column($attachments, null, 'res_id_master');
        $resIdAcknowledgement     = array_column($attachments, 'res_id_master');

        $resourceAltIdentifier = ResModel::get(['select' => ['alt_identifier', 'res_id'], 'where' => ['res_id in (?)'], 'data' => [$body['resources']]]);
        $altIdentifiers        = array_column($resourceAltIdentifier, 'alt_identifier', 'res_id');

        foreach ($body['resources'] as $resId) {
            if (!in_array($resId, $resIdAcknowledgement)) {
                $resourcesInformations['errors'][] = ['alt_identifier' => $altIdentifiers[$resId], 'res_id' => $resId, 'reason' => 'recordManagement_noAcknowledgement'];
                continue;
            }
            $acknowledgement = $resourcesAcknowledgement[$resId];

            $docserver = DocserverModel::getByDocserverId(['docserverId' => $acknowledgement['docserver_id'], 'select' => ['path_template', 'docserver_type_id']]);
            if (empty($docserver['path_template']) || !file_exists($docserver['path_template'])) {
                $resourcesInformations['errors'][] = ['alt_identifier' => $altIdentifiers[$resId], 'res_id' => $resId, 'reason' => 'docserverDoesNotExists'];
                continue;
            }
    
            $pathToDocument = $docserver['path_template'] . str_replace('#', DIRECTORY_SEPARATOR, $acknowledgement['path']) . $acknowledgement['filename'];
            if (!file_exists($pathToDocument)) {
                $resourcesInformations['errors'][] = ['alt_identifier' => $altIdentifiers[$resId], 'res_id' => $resId, 'reason' => 'fileDoesNotExists'];
                continue;
            }
    
            $docserverType = DocserverTypeModel::getById(['id' => $docserver['docserver_type_id'], 'select' => ['fingerprint_mode']]);
            $fingerprint = StoreController::getFingerPrint(['filePath' => $pathToDocument, 'mode' => $docserverType['fingerprint_mode']]);
            if (empty($acknowledgement['fingerprint'])) {
                AttachmentModel::update(['set' => ['fingerprint' => $fingerprint], 'where' => ['res_id = ?'], 'data' => [$args['resId']]]);
                $acknowledgement['fingerprint'] = $fingerprint;
            }
            if (!empty($acknowledgement['fingerprint']) && $acknowledgement['fingerprint'] != $fingerprint) {
                $resourcesInformations['errors'][] = ['alt_identifier' => $altIdentifiers[$resId], 'res_id' => $resId, 'reason' => 'fingerprintsDoNotMatch'];
                continue;
            }
            
            $acknowledgementXml = @simplexml_load_file($pathToDocument);
            if (empty($acknowledgementXml)) {
                $resourcesInformations['errors'][] = ['alt_identifier' => $altIdentifiers[$resId], 'res_id' => $resId, 'reason' => 'recordManagement_acknowledgementNotReadable'];
                continue;
            }

            $messageExchange = MessageExchangeModel::getMessageByReference(['select' => ['message_id'], 'reference' => (string)$acknowledgementXml->MessageReceivedIdentifier]);
            if (empty($messageExchange)) {
                $resourcesInformations['errors'][] = ['alt_identifier' => $altIdentifiers[$resId], 'res_id' => $resId, 'reason' => 'recordManagement_noAcknowledgementReference'];
                continue;
            }

            $unitIdentifier = MessageExchangeModel::getUnitIdentifierByResId(['select' => ['message_id'], 'resId' => $resId]);
            if ($unitIdentifier[0]['message_id'] != $messageExchange['message_id']) {
                $resourcesInformations['errors'][] = ['alt_identifier' => $altIdentifiers[$resId], 'res_id' => $resId, 'reason' => 'recordManagement_wrongAcknowledgement'];
                continue;
            }

            $resourcesInformations['success'][] = $resId;
        }

        return $response->withJson(['resourcesInformations' => $resourcesInformations]);
    }

    public function checkReplyRecordManagement(Request $request, Response $response, array $args)
    {
        $body = $request->getParsedBody();

        if (!Validator::arrayType()->notEmpty()->validate($body['resources'])) {
            return $response->withStatus(400)->withJson(['errors' => 'Body resources is empty or not an array']);
        }

        $errors = ResourceListController::listControl(['groupId' => $args['groupId'], 'userId' => $args['userId'], 'basketId' => $args['basketId'], 'currentUserId' => $GLOBALS['id']]);
        if (!empty($errors['errors'])) {
            return $response->withStatus($errors['code'])->withJson(['errors' => $errors['errors']]);
        }

        $body['resources'] = array_slice($body['resources'], 0, 500);
        if (!ResController::hasRightByResId(['resId' => $body['resources'], 'userId' => $GLOBALS['id']])) {
            return $response->withStatus(403)->withJson(['errors' => 'Document out of perimeter']);
        }

        $resourcesInformations = ['success' => [], 'errors' => []];
        $body['resources'] = PreProcessActionController::getNonLockedResources(['resources' => $body['resources'], 'userId' => $GLOBALS['id']]);

        $attachments = AttachmentModel::get([
            'select' => ['res_id_master', 'path', 'filename', 'docserver_id', 'fingerprint'],
            'where'  => ['res_id_master in (?)', 'attachment_type = ?', 'status = ?'],
            'data'   => [$body['resources'], 'reply_record_management', 'TRA']
        ]);
        $resourcesReply = array_column($attachments, null, 'res_id_master');
        $resIdReply     = array_column($attachments, 'res_id_master');

        $resourceAltIdentifier = ResModel::get(['select' => ['alt_identifier', 'res_id'], 'where' => ['res_id in (?)'], 'data' => [$body['resources']]]);
        $altIdentifiers        = array_column($resourceAltIdentifier, 'alt_identifier', 'res_id');

        foreach ($body['resources'] as $resId) {
            if (!in_array($resId, $resIdReply)) {
                $resourcesInformations['errors'][] = ['alt_identifier' => $altIdentifiers[$resId], 'res_id' => $resId, 'reason' => 'recordManagement_noReply'];
                continue;
            }
            $reply = $resourcesReply[$resId];

            $docserver = DocserverModel::getByDocserverId(['docserverId' => $reply['docserver_id'], 'select' => ['path_template', 'docserver_type_id']]);
            if (empty($docserver['path_template']) || !file_exists($docserver['path_template'])) {
                $resourcesInformations['errors'][] = ['alt_identifier' => $altIdentifiers[$resId], 'res_id' => $resId, 'reason' => 'docserverDoesNotExists'];
                continue;
            }
    
            $pathToDocument = $docserver['path_template'] . str_replace('#', DIRECTORY_SEPARATOR, $reply['path']) . $reply['filename'];
            if (!file_exists($pathToDocument)) {
                $resourcesInformations['errors'][] = ['alt_identifier' => $altIdentifiers[$resId], 'res_id' => $resId, 'reason' => 'fileDoesNotExists'];
                continue;
            }
    
            $docserverType = DocserverTypeModel::getById(['id' => $docserver['docserver_type_id'], 'select' => ['fingerprint_mode']]);
            $fingerprint = StoreController::getFingerPrint(['filePath' => $pathToDocument, 'mode' => $docserverType['fingerprint_mode']]);
            if (empty($reply['fingerprint'])) {
                AttachmentModel::update(['set' => ['fingerprint' => $fingerprint], 'where' => ['res_id = ?'], 'data' => [$args['resId']]]);
                $reply['fingerprint'] = $fingerprint;
            }
            if (!empty($reply['fingerprint']) && $reply['fingerprint'] != $fingerprint) {
                $resourcesInformations['errors'][] = ['alt_identifier' => $altIdentifiers[$resId], 'res_id' => $resId, 'reason' => 'fingerprintsDoNotMatch'];
                continue;
            }
            
            $replyXml = @simplexml_load_file($pathToDocument);
            if (empty($replyXml)) {
                $resourcesInformations['errors'][] = ['alt_identifier' => $altIdentifiers[$resId], 'res_id' => $resId, 'reason' => 'recordManagement_replyNotReadable'];
                continue;
            }

            $messageExchange = MessageExchangeModel::getMessageByReference(['select' => ['message_id'], 'reference' => (string)$replyXml->MessageRequestIdentifier]);
            if (empty($messageExchange)) {
                $resourcesInformations['errors'][] = ['alt_identifier' => $altIdentifiers[$resId], 'res_id' => $resId, 'reason' => 'recordManagement_noReplyReference'];
                continue;
            }

            $unitIdentifier = MessageExchangeModel::getUnitIdentifierByResId(['select' => ['message_id'], 'resId' => $resId]);
            if ($unitIdentifier[0]['message_id'] != $messageExchange['message_id']) {
                $resourcesInformations['errors'][] = ['alt_identifier' => $altIdentifiers[$resId], 'res_id' => $resId, 'reason' => 'recordManagement_wrongReply'];
                continue;
            }

            if ($body['resetAction'] && strpos((string)$replyXml->ReplyCode, '000') !== false) {
                $resourcesInformations['errors'][] = ['alt_identifier' => $altIdentifiers[$resId], 'res_id' => $resId, 'reason' => 'recordManagement_alreadyArchived'];
                continue;
            }
            if (!$body['resetAction'] && strpos((string)$replyXml->ReplyCode, '000') === false) {
                $resourcesInformations['errors'][] = ['alt_identifier' => $altIdentifiers[$resId], 'res_id' => $resId, 'reason' => 'recordManagement_rejectedReply'];
                continue;
            }

            $resourcesInformations['success'][] = $resId;
        }

        return $response->withJson(['resourcesInformations' => $resourcesInformations]);
    }
}
