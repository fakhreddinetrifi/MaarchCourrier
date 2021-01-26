<?php

/**
* Copyright Maarch since 2008 under licence GPLv3.
* See LICENCE.txt file at the root folder for more details.
* This file is part of Maarch software.
*
*/

/**
* @brief Message Exchange Review Controller
* @author dev@maarch.org
*/

namespace MessageExchange\controllers;

use Action\models\ActionModel;
use ExportSeda\controllers\SendMessageController;
use MessageExchange\controllers\ReceiveMessageExchangeController;
use MessageExchange\controllers\SendMessageExchangeController;
use MessageExchange\models\MessageExchangeModel;
use Resource\models\ResModel;
use SrcCore\models\CoreConfigModel;
use User\models\UserModel;
use Slim\Http\Request;
use Slim\Http\Response;

require_once 'modules/export_seda/Controllers/ReceiveMessage.php';

class MessageExchangeReviewController
{
    protected static function canSendMessageExchangeReview($aArgs = [])
    {
        if (empty($aArgs['res_id']) || !is_numeric($aArgs['res_id'])) {
            return false;
        }

        $resLetterboxData = ResModel::getOnView([
            'select'  => ['entity_label', 'res_id', 'external_id'],
            'where'   => ['res_id = ?'],
            'data'    => [$aArgs['res_id']],
            'orderBy' => ['res_id'], ]);

        if (!empty($resLetterboxData[0]['external_id'])) {
            $resLetterboxData[0]['external_id'] = json_decode($resLetterboxData[0]['external_id'], true);
            if (!empty($resLetterboxData[0]['external_id']['m2m'])) {
                return $resLetterboxData[0];
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public static function sendMessageExchangeReview($aArgs = [])
    {
        $messageExchangeData = self::canSendMessageExchangeReview(['res_id' => $aArgs['res_id']]);
        if ($messageExchangeData) {
            $actionInfo = ActionModel::getById(['id' => $aArgs['action_id']]);
            $reviewObject = new \stdClass();
            $reviewObject->Comment = array();
            $reviewObject->Comment[0] = new \stdClass();
            $user = UserModel::getByLogin(['login' => $aArgs['userId'], 'select' => ['id']]);
            $primaryEntity = UserModel::getPrimaryEntityById(['id' => $user['id'], 'select' => ['entities.entity_label']]);
            $reviewObject->Comment[0]->value = '['.date('d/m/Y H:i:s').'] "'.$actionInfo['label_action'].'" '._M2M_ACTION_DONE.' '.$primaryEntity['entity_label'].'. '._M2M_ENTITY_DESTINATION.' : '.$messageExchangeData['entity_label'];

            $date = new \DateTime();
            $reviewObject->Date = $date->format(\DateTime::ATOM);

            $reviewObject->MessageIdentifier = new \stdClass();
            $reviewObject->MessageIdentifier->value = $messageExchangeData['external_id']['m2m'].'_NotificationSent';

            $reviewObject->CodeListVersions = new \stdClass();
            $reviewObject->CodeListVersions->value = '';

            $reviewObject->UnitIdentifier = new \stdClass();
            $reviewObject->UnitIdentifier->value = $messageExchangeData['external_id']['m2m'];

            $messageExchangeReply = MessageExchangeModel::getMessageByReference(['reference' => $messageExchangeData['external_id']['m2m'].'_ReplySent']);
            $dataObject = json_decode($messageExchangeReply['data']);
            $reviewObject->OriginatingAgency = $dataObject->TransferringAgency;
            $reviewObject->ArchivalAgency = $dataObject->ArchivalAgency;

            if ($reviewObject->ArchivalAgency->OrganizationDescriptiveMetadata->Communication[0]->Channel == 'url') {
                $tab = explode('saveMessageExchangeReturn', $reviewObject->ArchivalAgency->OrganizationDescriptiveMetadata->Communication[0]->value);
                $reviewObject->ArchivalAgency->OrganizationDescriptiveMetadata->Communication[0]->value = $tab[0].'saveMessageExchangeReview';
            }

            $reviewObject->MessageIdentifier->value = $messageExchangeData['external_id']['m2m'].'_Notification';
            
            $filePath = SendMessageController::generateMessageFile(['messageObject' => $reviewObject, 'type' => 'ArchiveModificationNotification']);

            $reviewObject->MessageIdentifier->value = $messageExchangeData['external_id']['m2m'].'_NotificationSent';
            $reviewObject->TransferringAgency = $reviewObject->OriginatingAgency;
            $messageExchangeSaved = SendMessageExchangeController::saveMessageExchange(['dataObject' => $reviewObject, 'res_id_master' => $aArgs['res_id_master'], 'type' => 'ArchiveModificationNotification', 'file_path' => $filePath, 'userId' => $aArgs['userId']]);

            $reviewObject->MessageIdentifier->value = $messageExchangeData['external_id']['m2m'].'_Notification';

            $reviewObject->DataObjectPackage = new \stdClass();
            $reviewObject->DataObjectPackage->DescriptiveMetadata = new \stdClass();
            $reviewObject->DataObjectPackage->DescriptiveMetadata->ArchiveUnit = array();
            $reviewObject->DataObjectPackage->DescriptiveMetadata->ArchiveUnit[0] = new \stdClass();
            $reviewObject->DataObjectPackage->DescriptiveMetadata->ArchiveUnit[0]->Content = new \stdClass();
            $reviewObject->DataObjectPackage->DescriptiveMetadata->ArchiveUnit[0]->Content->OriginatingSystemId = $aArgs['res_id_master'];
            $reviewObject->DataObjectPackage->DescriptiveMetadata->ArchiveUnit[0]->Content->Title[0] = '[CAPTUREM2M_NOTIFICATION]'.date('Ymd_his');

            $reviewObject->TransferringAgency->OrganizationDescriptiveMetadata = new \stdClass();
            $reviewObject->TransferringAgency->OrganizationDescriptiveMetadata->UserIdentifier = $aArgs['userId'];

            SendMessageController::send($reviewObject, $messageExchangeSaved['messageId'], 'ArchiveModificationNotification');
        }
    }

    public function saveMessageExchangeReview(Request $request, Response $response)
    {
        if (empty($GLOBALS['login'])) {
            return $response->withStatus(401)->withJson(['errors' => 'User Not Connected']);
        }

        $data = $request->getParams();

        if (!ReceiveMessageExchangeController::checkNeededParameters(['data' => $data, 'needed' => ['type']])) {
            return $response->withStatus(400)->withJson(['errors' => 'Bad Request']);
        }

        $tmpName = ReceiveMessageExchangeController::createFile(['base64' => $data['base64'], 'extension' => $data['extension'], 'size' => $data['size']]);
        if (!empty($tmpName['errors'])) {
            return $response->withStatus(400)->withJson($tmpName);
        }

        $receiveMessage = new \ReceiveMessage();
        $tmpPath = CoreConfigModel::getTmpPath();
        $res = $receiveMessage->receive($tmpPath, $tmpName, $data['type']);

        $sDataObject = $res['content'];
        $dataObject = json_decode($sDataObject);
        $dataObject->TransferringAgency = $dataObject->OriginatingAgency;

        $messageExchange = MessageExchangeModel::getMessageByReference(['select' => ['operation_date', 'message_id', 'res_id_master'], 'reference' => $dataObject->UnitIdentifier->value]);
        if (empty($messageExchange['operation_date'])) {
            MessageExchangeModel::updateOperationDateMessage(['operation_date' => $dataObject->Date, 'message_id' => $messageExchange['message_id']]);
        }

        $messageExchangeSaved = SendMessageExchangeController::saveMessageExchange(['dataObject' => $dataObject, 'res_id_master' => $messageExchange['res_id_master'], 'type' => 'ArchiveModificationNotification', 'userId' => $GLOBALS['login']]);

        return $response->withJson([
            'messageId' => $messageExchangeSaved['messageId'],
        ]);
    }
}
