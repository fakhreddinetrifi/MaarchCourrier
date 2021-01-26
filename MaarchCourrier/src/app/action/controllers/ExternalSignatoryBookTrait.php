<?php

/**
* Copyright Maarch since 2008 under licence GPLv3.
* See LICENCE.txt file at the root folder for more details.
* This file is part of Maarch software.

* @brief   ExternalSignatoryBookTrait
* @author  dev <dev@maarch.org>
* @ingroup core
*/

namespace Action\controllers;

use Attachment\controllers\AttachmentController;
use Attachment\models\AttachmentModel;
use ExternalSignatoryBook\controllers\IxbusController;
use ExternalSignatoryBook\controllers\IParapheurController;
use ExternalSignatoryBook\controllers\FastParapheurController;
use ExternalSignatoryBook\controllers\MaarchParapheurController;
use ExternalSignatoryBook\controllers\XParaphController;
use Resource\models\ResModel;
use SrcCore\models\CoreConfigModel;
use SrcCore\models\ValidatorModel;

trait ExternalSignatoryBookTrait
{
    public static function sendExternalSignatoryBookAction(array $args)
    {
        ValidatorModel::notEmpty($args, ['resId']);
        ValidatorModel::intVal($args, ['resId']);
        ValidatorModel::arrayType($args, ['note']);

        $loadedXml = CoreConfigModel::getXmlLoaded(['path' => 'modules/visa/xml/remoteSignatoryBooks.xml']);
        $config = [];

        if (!empty($loadedXml)) {
            $config['id'] = (string)$loadedXml->signatoryBookEnabled;
            foreach ($loadedXml->signatoryBook as $value) {
                if ($value->id == $config['id']) {
                    $config['data'] = (array)$value;
                    break;
                }
            }

            if (!empty($config['id'])) {
                $attachments = AttachmentModel::get([
                    'select'    => [
                        'res_id', 'status'
                    ],
                    'where'     => ["res_id_master = ?", "attachment_type not in (?)", "status not in ('DEL', 'OBS', 'FRZ', 'TMP')", "in_signature_book = 'true'"],
                    'data'      => [$args['resId'], ['signed_response']]
                ]);

                foreach ($attachments as $attachment) {
                    if ($attachment['status'] == 'SEND_MASS') {
                        $generated = AttachmentController::generateMailing(['id' => $attachment['res_id'], 'userId' => $GLOBALS['id']]);
                        if (!empty($generated['errors'])) {
                            return ['errors' => [$generated['errors']]];
                        }
                    }
                }
            }

            if ($config['id'] == 'ixbus') {
                $loginIxbus    = $args['data']['ixbus']['login'];
                $passwordIxbus = $args['data']['ixbus']['password'];
                $userInfo      = IxbusController::getInfoUtilisateur(['config' => $config, 'login' => $loginIxbus, 'password' => $passwordIxbus]);
                if (empty($userInfo->Identifiant)) {
                    return ['errors' => [_BAD_LOGIN_OR_PSW]];
                }
            }

            $integratedResource = ResModel::get([
                'select' => [1],
                'where'  => ['integrations->>\'inSignatureBook\' = \'true\'', 'external_id->>\'signatureBookId\' is null', 'res_id = ?'],
                'data'   => [$args['resId']]
            ]);

            if (empty($attachments) && empty($integratedResource) && $args['data']['objectSent'] == 'attachment') {
                $noAttachmentsResource = ResModel::getById(['resId' => $args['resId'], 'select' => ['alt_identifier']]);
                return ['errors' => ['No attachment for this mail : ' . $noAttachmentsResource['alt_identifier']]];
            }

            if ($config['id'] == 'maarchParapheur') {
                $sentInfo = MaarchParapheurController::sendDatas([
                    'config'      => $config,
                    'resIdMaster' => $args['resId'],
                    'objectSent'  => 'attachment',
                    'userId'      => $GLOBALS['login'],
                    'steps'       => $args['data']['steps'],
                    'note'        => $args['note']['content'] ?? null
                ]);
            } elseif ($config['id'] == 'fastParapheur') {
                $sentInfo = FastParapheurController::sendDatas([
                    'config'      => $config,
                    'resIdMaster' => $args['resId']
                ]);
            } elseif ($config['id'] == 'iParapheur') {
                $sentInfo = IParapheurController::sendDatas([
                    'config'      => $config,
                    'resIdMaster' => $args['resId']
                ]);
            } elseif ($config['id'] == 'ixbus') {
                $sentInfo = IxbusController::sendDatas([
                    'config'        => $config,
                    'resIdMaster'   => $args['resId'],
                    'loginIxbus'    => $loginIxbus,
                    'passwordIxbus' => $passwordIxbus,
                    'classeurName'  => $args['data']['ixbus']['nature'],
                    'messageModel'  => $args['data']['ixbus']['messageModel'],
                    'manSignature'  => $args['data']['ixbus']['signatureMode']
                ]);
            } elseif ($config['id'] == 'xParaph') {
                $sentInfo = XParaphController::sendDatas([
                    'config'      => $config,
                    'resIdMaster' => $args['resId'],
                    'info'        => $args['data']['info'],
                    'steps'       => $args['data']['steps'],
                ]);
            }
            if (!empty($sentInfo['error'])) {
                return ['errors' => [$sentInfo['error']]];
            } else {
                $attachmentToFreeze = $sentInfo['sended'];
            }

            $historyInfo = $sentInfo['historyInfos'];
        }

        if (!empty($attachmentToFreeze)) {
            if (!empty($attachmentToFreeze['letterbox_coll'])) {
                ResModel::update([
                    'postSet' => ['external_id' => "jsonb_set(external_id, '{signatureBookId}', '\"{$attachmentToFreeze['letterbox_coll'][$args['resId']]}\"'::text::jsonb)"],
                    'where'   => ['res_id = ?'],
                    'data'    => [$args['resId']]
                ]);
            }
            if (!empty($attachmentToFreeze['attachments_coll'])) {
                foreach ($attachmentToFreeze['attachments_coll'] as $resId => $externalId) {
                    AttachmentModel::freezeAttachment([
                        'resId' => $resId,
                        'externalId' => $externalId
                    ]);
                }
            }
        }

        return ['history' => $historyInfo];
    }
}
