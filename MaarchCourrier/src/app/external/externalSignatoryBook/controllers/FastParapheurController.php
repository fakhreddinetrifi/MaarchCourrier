<?php

/**
 * Copyright Maarch since 2008 under licence GPLv3.
 * See LICENCE.txt file at the root folder for more details.
 * This file is part of Maarch software.
 *
 */

/**
 * @brief fastParapheur Controller
 * @author nathan.cheval@edissyum.com
 * @author dev@maarch.org
 */

namespace ExternalSignatoryBook\controllers;

use Attachment\models\AttachmentModel;
use Convert\controllers\ConvertPdfController;
use Convert\models\AdrModel;
use Docserver\models\DocserverModel;
use Docserver\models\DocserverTypeModel;
use Entity\models\ListInstanceModel;
use Resource\controllers\StoreController;
use Resource\models\ResModel;
use SrcCore\models\CoreConfigModel;
use SrcCore\models\CurlModel;
use SrcCore\models\DatabaseModel;
use User\models\UserModel;

/**
    * @codeCoverageIgnore
*/
class FastParapheurController
{
    public static function retrieveSignedMails($aArgs)
    {
        $version = $aArgs['version'];
        foreach ($aArgs['idsToRetrieve'][$version] as $resId => $value) {
            $xmlPostString = '<?xml version="1.0" encoding="utf-8"?>
            <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:sei="http://sei.ws.fast.cdc.com/">
                <soapenv:Header/>
                    <soapenv:Body>
                        <sei:history>
                            <documentId>' .  $value['external_id'] . '</documentId>
                        </sei:history>
                    </soapenv:Body>
            </soapenv:Envelope>';

            $curlReturn = CurlModel::execSOAP([
                'xmlPostString' => $xmlPostString,
                'url'           => $aArgs['config']['data']['url'],
                'options'       => [
                    CURLOPT_SSLCERT         => $aArgs['config']['data']['certPath'],
                    CURLOPT_SSLCERTPASSWD   => $aArgs['config']['data']['certPass'],
                    CURLOPT_SSLCERTTYPE     => $aArgs['config']['data']['certType']
                ]
            ]);

            if ($curlReturn['infos']['http_code'] == 404) {
                return ['error' => 'Erreur 404 : ' . $curlReturn['raw']];
            }

            $isError = $curlReturn['response']->children('http://schemas.xmlsoap.org/soap/envelope/')->Body;
            if (!empty($isError ->Fault[0]) && !empty($value['res_id_master'])) {
                echo 'PJ n° ' . $resId . ' et document original n° ' . $value['res_id_master'] . ' : ' . (string)$curlReturn['response']->children('http://schemas.xmlsoap.org/soap/envelope/')->Body->Fault[0]->children()->faultstring . PHP_EOL;
                continue;
            } elseif (!empty($isError->Fault[0])) {
                echo 'Document principal n° ' . $resId . ' : ' . (string)$curlReturn['response']->children('http://schemas.xmlsoap.org/soap/envelope/')->Body->Fault[0]->children()->faultstring . PHP_EOL;
                continue;
            }

            $response = $curlReturn['response']->children('http://schemas.xmlsoap.org/soap/envelope/')->Body->children('http://sei.ws.fast.cdc.com/')->historyResponse->children();

            foreach ($response->return as $res) {    // Loop on all steps of the documents (prepared, send to signature, signed etc...)
                $state      = (string) $res->stateName;
                if ($state == $aArgs['config']['data']['validatedState']) {
                    $response = FastParapheurController::download(['config' => $aArgs['config'], 'documentId' => $value['external_id']]);
                    $aArgs['idsToRetrieve'][$version][$resId]['status'] = 'validated';
                    $aArgs['idsToRetrieve'][$version][$resId]['format'] = 'pdf';
                    $aArgs['idsToRetrieve'][$version][$resId]['encodedFile'] = $response['b64FileContent'];
                    $signatoryInfo = FastParapheurController::getSignatoryUserInfo(['resId' => $aArgs['idsToRetrieve'][$version][$resId]['res_id_master']]);
                    $aArgs['idsToRetrieve'][$version][$resId]['signatory_user_serial_id'] = $signatoryInfo['id'];
                    FastParapheurController::processVisaWorkflow(['res_id_master' => $value['res_id_master'], 'res_id' => $value['res_id'], 'processSignatory' => true]);
                    break;
                } elseif ($state == $aArgs['config']['data']['refusedState']) {
                    $signatoryInfo = FastParapheurController::getSignatoryUserInfo(['resId' => $aArgs['idsToRetrieve'][$version][$resId]['res_id_master']]);
                    $response = FastParapheurController::getRefusalMessage(['config' => $aArgs['config'], 'documentId' => $value['external_id']]);
                    $aArgs['idsToRetrieve'][$version][$resId]['status'] = 'refused';
                    $aArgs['idsToRetrieve'][$version][$resId]['noteContent'] = $signatoryInfo['lastname'] . ' ' . $signatoryInfo['firstname'] . ' : ' . $response;
                    break;
                } else {
                    $aArgs['idsToRetrieve'][$version][$resId]['status'] = 'waiting';
                }
            }
        }
        
        return $aArgs['idsToRetrieve'];
    }

    public static function getSignatoryUserInfo($args = [])
    {
        $res = DatabaseModel::select([
            'select'    => ['firstname', 'lastname', 'users.id'],
            'table'     => ['listinstance', 'users'],
            'left_join' => ['listinstance.item_id = users.id'],
            'where'     => ['res_id = ?', 'process_date is null', 'difflist_type = ?'],
            'data'      => [$args['resId'], 'VISA_CIRCUIT']
        ])[0];

        return $res;
    }

    public static function processVisaWorkflow($aArgs = [])
    {
        $resIdMaster = $aArgs['res_id_master'] ?? $aArgs['res_id'];

        $attachments = AttachmentModel::get(['select' => ['count(1)'], 'where' => ['res_id_master = ?', 'status = ?'], 'data' => [$resIdMaster, 'FRZ']]);
        if ((count($attachments) < 2 && $aArgs['processSignatory']) || !$aArgs['processSignatory']) {
            $visaWorkflow = ListInstanceModel::get([
                'select'  => ['listinstance_id', 'requested_signature'],
                'where'   => ['res_id = ?', 'difflist_type = ?', 'process_date IS NULL'],
                'data'    => [$resIdMaster, 'VISA_CIRCUIT'],
                'orderBY' => ['ORDER BY listinstance_id ASC']
            ]);
    
            if (!empty($visaWorkflow)) {
                foreach ($visaWorkflow as $listInstance) {
                    if ($listInstance['requested_signature']) {
                        // Stop to the first signatory user
                        if ($aArgs['processSignatory']) {
                            ListInstanceModel::update(['set' => ['signatory' => 'true', 'process_date' => 'CURRENT_TIMESTAMP'], 'where' => ['listinstance_id = ?'], 'data' => [$listInstance['listinstance_id']]]);
                        }
                        break;
                    }
                    ListInstanceModel::update(['set' => ['process_date' => 'CURRENT_TIMESTAMP'], 'where' => ['listinstance_id = ?'], 'data' => [$listInstance['listinstance_id']]]);
                }
            }
        }
    }

    public static function upload($aArgs)
    {
        $circuitId    = $aArgs['circuitId'];
        $label        = $aArgs['label'];
        $subscriberId = $aArgs['businessId'];

        // Retrieve the annexes of the attachemnt to sign (other attachment and the original document)
        $annexes = [];
        $annexes['letterbox'] = ResModel::get([
            'select' => ['res_id', 'path', 'filename', 'docserver_id', 'format', 'category_id', 'external_id', 'integrations'],
            'where'  => ['res_id = ?'],
            'data'   => [$aArgs['resIdMaster']]
        ]);

        if (!empty($annexes['letterbox'][0]['docserver_id'])) {
            $adrMainInfo = ConvertPdfController::getConvertedPdfById(['resId' => $aArgs['resIdMaster'], 'collId' => 'letterbox_coll']);
            $letterboxPath = DocserverModel::getByDocserverId(['docserverId' => $adrMainInfo['docserver_id'], 'select' => ['path_template']]);
            $annexes['letterbox'][0]['filePath'] = $letterboxPath['path_template'] . str_replace('#', '/', $adrMainInfo['path']) . $adrMainInfo['filename'];
        }

        $attachments = AttachmentModel::get([
            'select'    => [
                'res_id', 'docserver_id', 'path', 'filename', 'format', 'attachment_type', 'fingerprint'
            ],
            'where'     => ["res_id_master = ?", "attachment_type not in (?)", "status not in ('DEL', 'OBS', 'FRZ', 'TMP', 'SEND_MASS')", "in_signature_book = 'true'"],
            'data'      => [$aArgs['resIdMaster'], ['signed_response']]
        ]);

        $attachmentTypes = AttachmentModel::getAttachmentsTypesByXML();
        foreach ($attachments as $key => $value) {
            if (!$attachmentTypes[$value['attachment_type']]['sign']) {
                $annexeAttachmentPath = DocserverModel::getByDocserverId(['docserverId' => $value['docserver_id'], 'select' => ['path_template', 'docserver_type_id']]);
                $value['filePath']    = $annexeAttachmentPath['path_template'] . str_replace('#', DIRECTORY_SEPARATOR, $value['path']) . $value['filename'];

                $docserverType = DocserverTypeModel::getById(['id' => $annexeAttachmentPath['docserver_type_id'], 'select' => ['fingerprint_mode']]);
                $fingerprint = StoreController::getFingerPrint(['filePath' => $value['filePath'], 'mode' => $docserverType['fingerprint_mode']]);
                if ($value['fingerprint'] != $fingerprint) {
                    return ['error' => 'Fingerprints do not match'];
                }

                unset($attachments[$key]);
                $annexes['attachments'][] = $value;
            }
        }
        // END annexes

        $attachmentToFreeze = [];
        foreach ($attachments as $attachment) {
            $resId  = $attachment['res_id'];
            $collId = 'attachments_coll';
            
            $response = FastParapheurController::uploadFile([
                'resId'        => $resId,
                'collId'       => $collId,
                'resIdMaster'  => $aArgs['resIdMaster'],
                'annexes'      => $annexes,
                'circuitId'    => $circuitId,
                'label'        => $label,
                'subscriberId' => $subscriberId,
                'config'       => $aArgs['config']
            ]);

            if (!empty($response['error'])) {
                return $response;
            } else {
                $attachmentToFreeze[$collId][$resId] = $response['success'];
            }
        }

        // Send main document if in signature book
        if (!empty($annexes['letterbox'][0])) {
            $mainDocumentIntegration = json_decode($annexes['letterbox'][0]['integrations'], true);
            $externalId              = json_decode($annexes['letterbox'][0]['external_id'], true);
            if ($mainDocumentIntegration['inSignatureBook'] && empty($externalId['signatureBookId'])) {
                $resId  = $annexes['letterbox'][0]['res_id'];
                $collId = 'letterbox_coll';
                unset($annexes['letterbox']);
    
                $response = FastParapheurController::uploadFile([
                    'resId'        => $resId,
                    'collId'       => $collId,
                    'resIdMaster'  => $aArgs['resIdMaster'],
                    'annexes'      => $annexes,
                    'circuitId'    => $circuitId,
                    'label'        => $label,
                    'subscriberId' => $subscriberId,
                    'config'       => $aArgs['config']
                ]);

                if (!empty($response['error'])) {
                    return $response;
                } else {
                    $attachmentToFreeze[$collId][$resId] = $response['success'];
                }
            }
        }

        return ['sended' => $attachmentToFreeze];
    }

    public static function uploadFile($aArgs)
    {
        $adrInfo = ConvertPdfController::getConvertedPdfById(['resId' => $aArgs['resId'], 'collId' => $aArgs['collId']]);
        if (empty($adrInfo['docserver_id']) || strtolower(pathinfo($adrInfo['filename'], PATHINFO_EXTENSION)) != 'pdf') {
            return ['error' => 'Document ' . $aArgs['resIdMaster'] . ' is not converted in pdf'];
        }
        $attachmentPath     =  DocserverModel::getByDocserverId(['docserverId' => $adrInfo['docserver_id'], 'select' => ['path_template']]);
        $attachmentFilePath = $attachmentPath['path_template'] . str_replace('#', '/', $adrInfo['path']) . $adrInfo['filename'];
        $attachmentFileName = 'projet_courrier_' . $aArgs['resIdMaster'] . '_' . rand(0001, 9999) . '.pdf';

        $zip         = new \ZipArchive();
        $tmpPath     = CoreConfigModel::getTmpPath();
        $zipFilePath = $tmpPath . DIRECTORY_SEPARATOR
            . $attachmentFileName . '.zip';  // The zip file need to have the same name as the attachment we want to sign

        if ($zip->open($zipFilePath, \ZipArchive::CREATE)!==true) {
            return ['error' => "Can not open file : <$zipFilePath>\n"];
        }
        $zip->addFile($attachmentFilePath, $attachmentFileName);

        if (!empty($aArgs['annexes']['letterbox'])) {
            $zip->addFile($aArgs['annexes']['letterbox'][0]['filePath'], 'document_principal.' . $aArgs['annexes']['letterbox'][0]['format']);
        }

        if (isset($aArgs['annexes']['attachments'])) {
            for ($j = 0; $j < count($aArgs['annexes']['attachments']); $j++) {
                $zip->addFile(
                    $aArgs['annexes']['attachments'][$j]['filePath'],
                    'PJ_' . ($j + 1) . '.' . $aArgs['annexes']['attachments'][$j]['format']
                );
            }
        }

        $zip->close();

        $b64Attachment = base64_encode(file_get_contents($zipFilePath));
        $fileName      = $attachmentFileName . '.zip';
        $circuitId     = str_replace('.', '-', $aArgs['circuitId']);

        $xmlPostString = '<?xml version="1.0" encoding="utf-8"?>
            <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:sei="http://sei.ws.fast.cdc.com/">
                <soapenv:Header/>
                    <soapenv:Body>
                        <sei:upload>
                            <label>' . $aArgs['label'] . '</label>
                            <comment></comment>
                            <subscriberId>' . $aArgs['subscriberId'] . '</subscriberId>
                            <circuitId>' . $circuitId . '</circuitId>
                            <dataFileVO>
                                <dataHandler>' . $b64Attachment . '</dataHandler>
                                <filename>' . $fileName . '</filename>
                            </dataFileVO>
                        </sei:upload>
                    </soapenv:Body>
            </soapenv:Envelope>';

        $curlReturn = CurlModel::execSOAP([
            'xmlPostString' => $xmlPostString,
            'url'           => $aArgs['config']['data']['url'],
            'options'       => [
                CURLOPT_SSLCERT       => $aArgs['config']['data']['certPath'],
                CURLOPT_SSLCERTPASSWD => $aArgs['config']['data']['certPass'],
                CURLOPT_SSLCERTTYPE   => $aArgs['config']['data']['certType']
            ]
        ]);

        if ($curlReturn['infos']['http_code'] == 404) {
            return ['error' => 'Erreur 404 : ' . $curlReturn['raw']];
        } elseif (!empty($curlReturn['error'])) {
            return ['error' => $curlReturn['error']];
        } elseif (!empty($curlReturn['response']->children('http://schemas.xmlsoap.org/soap/envelope/')->Body->Fault[0])) {
            $error = (string)$curlReturn['response']->children('http://schemas.xmlsoap.org/soap/envelope/')->Body->Fault[0]->children()->faultstring . PHP_EOL;
            return ['error' => $error];
        }

        FastParapheurController::processVisaWorkflow(['res_id_master' => $aArgs['resIdMaster'], 'processSignatory' => false]);
        $documentId = $curlReturn['response']->children('http://schemas.xmlsoap.org/soap/envelope/')->Body->children('http://sei.ws.fast.cdc.com/')->uploadResponse->children();
        return ['success' => (string)$documentId];
    }

    public static function download($aArgs)
    {
        $xmlPostString = '<?xml version="1.0" encoding="utf-8"?>
            <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:sei="http://sei.ws.fast.cdc.com/">
                <soapenv:Header/>
                <soapenv:Body>
                    <sei:download>
                        <documentId>' . $aArgs['documentId'] . '</documentId>
                    </sei:download>
                </soapenv:Body>
            </soapenv:Envelope>';

        $curlReturn = CurlModel::execSOAP([
            'xmlPostString' => $xmlPostString,
            'url'           => $aArgs['config']['data']['url'],
            'options'       => [
                CURLOPT_SSLCERT       => $aArgs['config']['data']['certPath'],
                CURLOPT_SSLCERTPASSWD => $aArgs['config']['data']['certPass'],
                CURLOPT_SSLCERTTYPE   => $aArgs['config']['data']['certType']
            ]
        ]);

        $isError = $curlReturn['response']->children('http://schemas.xmlsoap.org/soap/envelope/')->Body;
        if (!empty($isError ->Fault[0])) {
            echo (string)$curlReturn['response']->children('http://schemas.xmlsoap.org/soap/envelope/')->Body->Fault[0]->children()->faultstring . PHP_EOL;
            return false;
        } else {
            $response = $curlReturn['response']->children('http://schemas.xmlsoap.org/soap/envelope/')->Body->children('http://sei.ws.fast.cdc.com/')->downloadResponse->children()->return;
            $returnedDocumentId = (string) $response->documentId;
            if ($aArgs['documentId'] !== $returnedDocumentId) {
                return false;
            } else {
                $b64FileContent = $response->content;
                return ['b64FileContent' => (string)$b64FileContent, 'documentId' => $returnedDocumentId];
            }
        }
    }

    public static function sendDatas($aArgs)
    {
        $config = $aArgs['config'];
        // We need the SIRET field and the user_id of the signatory user's primary entity
        $signatory = DatabaseModel::select([
            'select'    => ['user_id', 'business_id', 'entities.entity_label'],
            'table'     => ['listinstance', 'users_entities', 'entities'],
            'left_join' => ['item_id = user_id', 'users_entities.entity_id = entities.entity_id'],
            'where'     => ['res_id = ?', 'item_mode = ?', 'process_date is null'],
            'data'      => [$aArgs['resIdMaster'], 'sign']
        ])[0];
        $redactor = DatabaseModel::select([
            'select'    => ['short_label'],
            'table'     => ['res_view_letterbox', 'users_entities', 'entities'],
            'left_join' => ['dest_user = user_id', 'users_entities.entity_id = entities.entity_id'],
            'where'     => ['res_id = ?'],
            'data'      => [$aArgs['resIdMaster']]
        ])[0];

        if (empty($signatory['business_id']) || substr($signatory['business_id'], 0, 3) == 'org') {
            $signatory['business_id'] = $config['data']['subscriberId'];
        }

        if (!empty($signatory['user_id'])) {
            $user = UserModel::getById(['id' => $signatory['user_id'], 'select' => ['user_id']]);
        }

        return FastParapheurController::upload(['config' => $config, 'resIdMaster' => $aArgs['resIdMaster'], 'businessId' => $signatory['business_id'], 'circuitId' => $user['user_id'], 'label' => $redactor['short_label']]);
    }

    public static function getRefusalMessage($aArgs)
    {
        $xmlPostString = '<?xml version="1.0" encoding="utf-8"?>
        <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:sei="http://sei.ws.fast.cdc.com/">
            <soapenv:Header/>
                <soapenv:Body>
                    <sei:getRefusalMessage>
                        <nodeRefId>' .  $aArgs['documentId'] . '</nodeRefId>
                    </sei:getRefusalMessage>
                </soapenv:Body>
         </soapenv:Envelope>';

        $curlReturn = CurlModel::execSOAP([
            'xmlPostString' => $xmlPostString,
            'url'           => $aArgs['config']['data']['url'],
            'options'       => [
                CURLOPT_SSLCERT       => $aArgs['config']['data']['certPath'],
                CURLOPT_SSLCERTPASSWD => $aArgs['config']['data']['certPass'],
                CURLOPT_SSLCERTTYPE   => $aArgs['config']['data']['certType']
            ]
        ]);

        $response = $curlReturn['response']->children('http://schemas.xmlsoap.org/soap/envelope/')->Body->children('http://sei.ws.fast.cdc.com/')->getRefusalMessageResponse->children()->return;

        return $response;
    }
}
