<?php

/**
* Copyright Maarch since 2008 under licence GPLv3.
* See LICENCE.txt file at the root folder for more details.
* This file is part of Maarch software.

* @brief   ExportSEDATrait
* @author  dev <dev@maarch.org>
* @ingroup core
*/

namespace ExportSeda\controllers;

use Attachment\models\AttachmentModel;
use Contact\controllers\ContactController;
use Convert\controllers\ConvertPdfController;
use Docserver\models\DocserverModel;
use Docserver\models\DocserverTypeModel;
use Doctype\models\DoctypeModel;
use Entity\models\EntityModel;
use Entity\models\ListInstanceModel;
use Folder\models\FolderModel;
use History\models\HistoryModel;
use IndexingModel\models\IndexingModelFieldModel;
use MessageExchange\models\MessageExchangeModel;
use Note\controllers\NoteController;
use Parameter\models\ParameterModel;
use Resource\controllers\StoreController;
use Resource\controllers\SummarySheetController;
use Resource\models\ResModel;
use setasign\Fpdi\Tcpdf\Fpdi;
use SrcCore\models\CoreConfigModel;
use SrcCore\models\CurlModel;
use SrcCore\models\ValidatorModel;
use User\models\UserModel;

trait ExportSEDATrait
{
    public static function sendToRecordManagement(array $args)
    {
        ValidatorModel::notEmpty($args, ['resId']);
        ValidatorModel::intVal($args, ['resId']);

        $attachments = AttachmentModel::get([
            'select' => ['res_id'],
            'where'  => ['res_id_master = ?', 'attachment_type in (?)', 'status = ?'],
            'data'   => [$args['resId'], ['acknowledgement_record_management', 'reply_record_management'], 'TRA']
        ]);
        if (!empty($attachments)) {
            return ['errors' => ['acknowledgement or reply already exists']];
        }

        $resource = ResModel::getById(['resId' => $args['resId'], 'select' => ['res_id', 'destination', 'type_id', 'subject', 'linked_resources', 'alt_identifier', 'admission_date', 'creation_date', 'modification_date', 'doc_date', 'retention_frozen', 'binding']]);
        if (empty($resource)) {
            return ['errors' => ['resource does not exists']];
        } elseif (empty($resource['destination'])) {
            return ['errors' => ['resource has no destination']];
        } elseif ($resource['retention_frozen']) {
            return ['errors' => ['retention rule is frozen']];
        }

        $doctype = DoctypeModel::getById(['id' => $resource['type_id'], 'select' => ['description', 'duration_current_use', 'action_current_use', 'retention_rule', 'retention_final_disposition']]);
        if (empty($doctype['retention_rule']) || empty($doctype['retention_final_disposition'])) {
            return ['errors' => ['retention_rule or retention_final_disposition is empty for doctype']];
        } else {
            $bindingDocument    = ParameterModel::getById(['select' => ['param_value_string'], 'id' => 'bindingDocumentFinalAction']);
            $nonBindingDocument = ParameterModel::getById(['select' => ['param_value_string'], 'id' => 'nonBindingDocumentFinalAction']);
            if ($resource['binding'] === null && !in_array($doctype['action_current_use'], ['transfer', 'copy'])) {
                return ['errors' => 'action_current_use is not transfer or copy'];
            } elseif ($resource['binding'] === true && !in_array($bindingDocument['param_value_string'], ['transfer', 'copy'])) {
                return ['errors' => 'binding document is not transfer or copy'];
            } elseif ($resource['binding'] === false && !in_array($nonBindingDocument['param_value_string'], ['transfer', 'copy'])) {
                return ['errors' => 'no binding document is not transfer or copy'];
            }
            $date = new \DateTime($resource['creation_date']);
            $date->add(new \DateInterval("P{$doctype['duration_current_use']}D"));
            if (strtotime($date->format('Y-m-d')) >= time()) {
                return ['errors' => 'duration current use is not exceeded'];
            }
        }

        $entity = EntityModel::getByEntityId(['entityId' => $resource['destination'], 'select' => ['producer_service', 'entity_label']]);
        if (empty($entity['producer_service'])) {
            return ['errors' => ['producer_service is empty for this entity']];
        }

        $config = CoreConfigModel::getJsonLoaded(['path' => 'apps/maarch_entreprise/xml/config.json']);
        if (empty($config['exportSeda']['senderOrgRegNumber'])) {
            return ['errors' => ['No senderOrgRegNumber found in config.json']];
        }

        foreach (['packageName', 'archivalAgreement', 'slipId', 'entityArchiveRecipient', 'archiveDescriptionLevel'] as $value) {
            if (empty($args['data'][$value])) {
                return ['errors' => [$value . ' is empty']];
            }
        }

        foreach ($args['data']['archives'] as $archiveUnit) {
            if (empty($archiveUnit['id']) or empty($archiveUnit['descriptionLevel'])) {
                return ['errors' => ['Missing id or descriptionLevel for an archiveUnit']];
            }
        }

        $initData = SedaController::initArchivalData([
            'resource'           => $resource,
            'senderOrgRegNumber' => $config['exportSeda']['senderOrgRegNumber'],
            'entity'             => $entity,
            'doctype'            => $doctype,
            'getFile'            => true
        ]);

        if (!empty($initData['errors'])) {
            return ['errors' => $initData['errors']];
        } else {
            $initData = $initData['archivalData'];
        }

        $archivesAttachment = [];
        $initialArchiveUnits = $initData['archiveUnits'];
        foreach ($initialArchiveUnits as $archiveUnit) {
            $archiveFound = false;
            foreach ($args['data']['archives'] as $userArchiveUnit) {
                if ($userArchiveUnit['id'] == $archiveUnit['id']) {
                    $archiveUnit['descriptionLevel'] = $userArchiveUnit['descriptionLevel'];
                    $archivesAttachment[]             = $archiveUnit;
                    $archiveFound                    = true;
                    break;
                }
            }
            if (!$archiveFound) {
                $archiveUnit['descriptionLevel'] = 'Item';
                $archivesAttachment = $archiveUnit;
            }
        }

        $history = ExportSEDATrait::getHistory(['resId' => $resource['res_id']]);
        $folder  = ExportSEDATrait::getFolderPath(['selectedFolder' => $args['data']['folder'], 'folders' => $initData['additionalData']['folders']]);

        $dataObjectPackage = [
            'archiveId'                 => $initData['data']['slipInfo']['archiveId'],
            'chrono'                    => $resource['alt_identifier'],
            'originatorAgency'          => [
                'id'    => $entity['producer_service'],
                'label' => $entity['entity_label']
            ],
            'descriptionLevel'          => $args['data']['archiveDescriptionLevel'],
            'receivedDate'              => $resource['admission_date'],
            'documentDate'              => $resource['doc_date'],
            'creationDate'              => $resource['creation_date'],
            'modificationDate'          => $resource['modification_date'],
            'retentionRule'             => $initData['data']['doctype']['retentionRule'],
            'retentionFinalDisposition' => $initData['data']['doctype']['retentionFinalDisposition'],
            'accessRuleCode'            => $config['exportSeda']['accessRuleCode'],
            'history'                   => $history['history'],
            'contacts'                  => [
                'senders'    => ContactController::getParsedContacts(['resId' => $resource['res_id'], 'mode' => 'sender']),
                'recipients' => ContactController::getParsedContacts(['resId' => $resource['res_id'], 'mode' => 'recipient'])
            ],
            'attachments'               => $archivesAttachment,
            'folders'                   => $folder['folderPath'],
            'links'                     => $initData['additionalData']['linkedResources']
        ];

        $data = [
            'type' => 'ArchiveTransfer',
            'messageObject' => [
                'messageName '       => $args['data']['packageName'],
                'messageIdentifier'  => $initData['data']['slipInfo']['slipId'],
                'archivalAgreement'  => $args['data']['archivalAgreement'],
                'dataObjectPackage'  => $dataObjectPackage,
                'archivalAgency'     => $args['data']['entityArchiveRecipient'],
                'transferringAgency' => $initData['data']['entity']['senderArchiveEntity']
            ]
        ];

        $sedaPackage = ExportSEDATrait::generateSEDAPackage(['data' => $data]);
        if (!empty($sedaPackage['errors'])) {
            return ['errors' => [$sedaPackage['errors']]];
        }

        $messageSaved = ExportSEDATrait::saveMessage(['messageObject' => $sedaPackage['messageObject']]);
        MessageExchangeModel::insertUnitIdentifier([
            'messageId' => $messageSaved['messageId'],
            'tableName' => 'res_letterbox',
            'resId'     => $resource['res_id']
        ]);

        ExportSEDATrait::cleanTmpDocument(['archiveUnits' => $initData['archiveUnits']]);

        if ($args['data']['actionMode'] == 'download') {
            $encodedContent = base64_encode(file_get_contents($sedaPackage['encodedFilePath']));
            unlink($sedaPackage['encodedFilePath']);
            return ['data' => ['encodedFile' => $encodedContent]];
        } else {
            $elementSend  = ExportSEDATrait::sendSedaPackage([
                'messageId'       => $messageSaved['messageId'],
                'config'          => $config,
                'encodedFilePath' => $sedaPackage['encodedFilePath'],
                'messageFilename' => $sedaPackage['messageFilename'],
                'resId'           => $resource['res_id'],
                'reference'       => $data['messageObject']['messageIdentifier']
            ]);
            unlink($sedaPackage['encodedFilePath']);
            if (!empty($elementSend['errors'])) {
                return ['errors' => [$elementSend['errors']]];
            }
    
            return true;
        }
    }

    public static function sendSedaPackage($args = [])
    {
        $bodyData = [
            'messageFile' => base64_encode(file_get_contents($args['encodedFilePath'])),
            'filename'    => $args['messageFilename'],
            'schema'      => 'seda2'
        ];
        $curlResponse = CurlModel::execSimple([
            'url'     => rtrim($args['config']['exportSeda']['urlSAEService'], '/') . '/medona/archiveTransfer',
            'method'  => 'POST',
            'cookie'  => 'LAABS-AUTH=' . urlencode($args['config']['exportSeda']['token']),
            'headers' => [
                'Accept: application/json',
                'Content-Type: application/json',
                'User-Agent: ' . $args['config']['exportSeda']['userAgent']
            ],
            'body'   => json_encode($bodyData)
        ]);

        if (!empty($curlResponse['errors'])) {
            return ['errors' => 'Error returned by the route /medona/create : ' . $curlResponse['errors']];
        } elseif ($curlResponse['code'] != 200) {
            return ['errors' => 'Error returned by the route /medona/create : ' . $curlResponse['response']['message']];
        }

        $acknowledgement = ExportSEDATrait::getAcknowledgement([
            'config'    => $args['config'],
            'reference' => $args['reference']
        ]);
        if (!empty($acknowledgement['errors'])) {
            return ['errors' => 'Error returned in getAcknowledgement process : ' . $acknowledgement['errors']];
        }

        $id = StoreController::storeAttachment([
            'encodedFile'   => $acknowledgement['encodedAcknowledgement'],
            'type'          => 'acknowledgement_record_management',
            'resIdMaster'   => $args['resId'],
            'title'         => 'Accusé de réception',
            'format'        => 'xml',
            'status'        => 'TRA'
        ]);
        ConvertPdfController::convert([
            'resId'  => $id,
            'collId' => 'attachments_coll'
        ]);
        if (empty($id) || !empty($id['errors'])) {
            return ['errors' => ['[storeAttachment] ' . $id['errors']]];
        }

        return [];
    }

    public static function saveMessage($args = [])
    {
        $data = new \stdClass();

        $data->messageId                             = $args['messageObject']->MessageIdentifier->value;
        $data->date                                  = $args['messageObject']->Date;

        $data->MessageIdentifier                     = new \stdClass();
        $data->MessageIdentifier->value              = $args['messageObject']->MessageIdentifier->value;

        $data->TransferringAgency                    = new \stdClass();
        $data->TransferringAgency->Identifier        = new \stdClass();
        $data->TransferringAgency->Identifier->value = $args['messageObject']->TransferringAgency->Identifier->value;

        $data->ArchivalAgency                        = new \stdClass();
        $data->ArchivalAgency->Identifier            = new \stdClass();
        $data->ArchivalAgency->Identifier->value     = $args['messageObject']->ArchivalAgency->Identifier->value;

        $data->ArchivalAgreement                     = new \stdClass();
        $data->ArchivalAgreement->value              = $args['messageObject']->ArchivalAgreement->value;

        $data->ReplyCode                             = $args['messageObject']->ReplyCode;

        $dataExtension                      = [];
        $dataExtension['fullMessageObject'] = $args['messageObject'];
        $dataExtension['SenderOrgNAme']     = '';
        $dataExtension['RecipientOrgNAme']  = '';

        $message = MessageExchangeModel::insertMessage([
            'data'          => $data,
            'type'          => 'ArchiveTransfer',
            'dataExtension' => $dataExtension,
            'userId'        => $GLOBALS['id']
        ]);

        return ['messageId' => $message['messageId']];
    }

    public static function getFolderPath($args = [])
    {
        $folderPath = null;
        if (!empty($args['selectedFolder'])) {
            foreach ($args['folders'] as $folder) {
                if ($folder['id'] == $args['selectedFolder']) {
                    $folderId   = explode("_", $folder['id'])[1];
                    $folderPath = FolderModel::getFolderPath(['id' => $folderId]);
                    break;
                }
            }
        }

        return ['folderPath' => $folderPath];
    }

    public static function getHistory($args = [])
    {
        $history = HistoryModel::get([
            'select'  => ['event_date', 'user_id', 'info', 'remote_ip'],
            'where'   => ['table_name in (?)', 'record_id = ?', 'event_type like ?'],
            'data'    => [['res_letterbox', 'res_view_letterbox'], $args['resId'], 'ACTION#%'],
            'orderBy' => ['event_date DESC']
        ]);

        foreach ($history as $key => $value) {
            $history[$key]['userLabel'] = UserModel::getLabelledUserById(['id' => $value['user_id']]);
        }

        return ['history' => $history];
    }

    public static function getAttachmentFilePath($args = [])
    {
        $document['docserver_id'] = $args['data']['docserver_id'];
        $document['path']         = $args['data']['path'];
        $document['filename']     = $args['data']['filename'];
        $document['fingerprint']  = $args['data']['fingerprint'];

        $docserver = DocserverModel::getByDocserverId(['docserverId' => $document['docserver_id'], 'select' => ['path_template', 'docserver_type_id']]);
        if (empty($docserver['path_template']) || !file_exists($docserver['path_template'])) {
            return ['errors' => 'Docserver does not exist'];
        }

        $pathToDocument = $docserver['path_template'] . str_replace('#', DIRECTORY_SEPARATOR, $document['path']) . $document['filename'];

        if (!file_exists($pathToDocument)) {
            return ['errors' => 'Attachment not found on docserver'];
        }

        $docserverType = DocserverTypeModel::getById(['id' => $docserver['docserver_type_id'], 'select' => ['fingerprint_mode']]);
        $fingerprint   = StoreController::getFingerPrint(['filePath' => $pathToDocument, 'mode' => $docserverType['fingerprint_mode']]);
        if (empty($document['fingerprint'])) {
            AttachmentModel::update(['set' => ['fingerprint' => $fingerprint], 'where' => ['res_id = ?'], 'data' => [$args['resId']]]);
            $document['fingerprint'] = $fingerprint;
        }

        if (!empty($document['fingerprint']) && $document['fingerprint'] != $fingerprint) {
            return ['errors' => 'Fingerprints do not match'];
        }

        $fileContent = file_exists($pathToDocument);
        if ($fileContent === false) {
            return ['errors' => 'Document not found on docserver'];
        }

        return ['filePath' => $pathToDocument];
    }

    public static function getNoteFilePath($args = [])
    {
        $encodedDocument = NoteController::getEncodedPdfByIds(['ids' => [$args['id']]]);

        $tmpPath  = CoreConfigModel::getTmpPath();
        $filePath = $tmpPath . 'note_' . $args['id'] . '.pdf';
        file_put_contents($filePath, base64_decode($encodedDocument['encodedDocument']));

        return ['filePath' => $filePath];
    }

    public static function getEmailFilePath($args = [])
    {
        $body   = str_replace('###', ';', $args['data']['body']);
        $sender = json_decode($args['data']['sender'], true);
        $data   = "Courriel n°" . $args['data']['id'] . "\nDe : " . $sender['email'] . "\nPour : " . implode(", ", json_decode($args['data']['recipients'], true)) . "\nObjet : " . $args['data']['object'] . "\n\n" . strip_tags(html_entity_decode($body));

        $pdf = new Fpdi('P', 'pt');
        $pdf->setPrintHeader(false);
        $pdf->AddPage();
        $pdf->MultiCell(0, 10, $data, 0, 'L');

        $tmpPath  = CoreConfigModel::getTmpPath();
        $filePath = $tmpPath . 'email_' . $args['data']['id'] . '.pdf';
        $pdf->Output($filePath, "F");

        return ['filePath' => $filePath];
    }

    public static function getSummarySheetFilePath($args = [])
    {
        $units   = [];
        $units[] = ['unit' => 'primaryInformations'];
        $units[] = ['unit' => 'secondaryInformations',       'label' => _SECONDARY_INFORMATION];
        $units[] = ['unit' => 'senderRecipientInformations', 'label' => _DEST_INFORMATION];
        $units[] = ['unit' => 'diffusionList',               'label' => _DIFFUSION_LIST];
        $units[] = ['unit' => 'visaWorkflow',                'label' => _VISA_WORKFLOW];
        $units[] = ['unit' => 'opinionWorkflow',             'label' => _AVIS_WORKFLOW];
        
        $tmpIds = [$args['resId']];
        $data   = [];
        foreach ($units as $unit) {
            if ($unit['unit'] == 'opinionWorkflow') {
                $data['listInstancesOpinion'] = ListInstanceModel::get([
                    'select'    => ['item_id', 'process_date', 'res_id'],
                    'where'     => ['difflist_type = ?', 'res_id in (?)'],
                    'data'      => ['AVIS_CIRCUIT', $tmpIds],
                    'orderBy'   => ['listinstance_id']
                ]);
            } elseif ($unit['unit'] == 'visaWorkflow') {
                $data['listInstancesVisa'] = ListInstanceModel::get([
                    'select'    => ['item_id', 'requested_signature', 'process_date', 'res_id'],
                    'where'     => ['difflist_type = ?', 'res_id in (?)'],
                    'data'      => ['VISA_CIRCUIT', $tmpIds],
                    'orderBy'   => ['listinstance_id']
                ]);
            } elseif ($unit['unit'] == 'diffusionList') {
                $data['listInstances'] = ListInstanceModel::get([
                    'select'  => ['item_id', 'item_type', 'item_mode', 'res_id'],
                    'where'   => ['difflist_type = ?', 'res_id in (?)'],
                    'data'    => ['entity_id', $tmpIds],
                    'orderBy' => ['listinstance_id']
                ]);
            }
        }

        $mainResource = ResModel::getOnView([
            'select' => ['process_limit_date', 'status', 'alt_identifier', 'subject', 'priority', 'res_id', 'admission_date', 'creation_date', 'doc_date', 'initiator', 'typist', 'type_label', 'destination', 'filename'],
            'where'  => ['res_id = ?'],
            'data'   => [$args['resId']]
        ]);

        $modelId = ResModel::getById([
            'select' => ['model_id'],
            'resId'  => $args['resId']
        ]);
        $indexingFields = IndexingModelFieldModel::get([
            'select' => ['identifier', 'unit'],
            'where'  => ['model_id = ?'],
            'data'   => [$modelId['model_id']]
        ]);
        $fieldsIdentifier = array_column($indexingFields, 'identifier');

        $pdf = new Fpdi('P', 'pt');
        $pdf->setPrintHeader(false);

        SummarySheetController::createSummarySheet($pdf, [
            'resource'         => $mainResource[0],
            'units'            => $units,
            'login'            => $GLOBALS['login'],
            'data'             => $data,
            'fieldsIdentifier' => $fieldsIdentifier
        ]);

        $tmpPath = CoreConfigModel::getTmpPath();
        $summarySheetFilePath = $tmpPath . "summarySheet_".$args['resId'] . "_" . $GLOBALS['id'] . "_" . rand() . ".pdf";
        $pdf->Output($summarySheetFilePath, 'F');

        return ['filePath' => $summarySheetFilePath];
    }

    public static function cleanTmpDocument(array $args)
    {
        foreach ($args['archiveUnits'] as $archiveUnit) {
            if (in_array($archiveUnit['type'], ['note', 'email', 'summarySheet'])) {
                unlink($archiveUnit['filePath']);
            }
        }
    }

    public static function array2object($data)
    {
        if (!is_array($data)) {
            return $data;
        }
        $object = new \stdClass();
        foreach ($data as $name => $value) {
            if (isset($name)) {
                $object->{$name} = self::array2object($value);
            }
        }
        return $object;
    }

    public static function generateSEDAPackage(array $args)
    {
        $data = [];
        $data['messageObject'] = self::array2object($args["data"]["messageObject"]);
        $data['type'] = $args["data"]["type"];
        
        $informationsToSend = SendMessageController::generateSedaFile($data);
        return $informationsToSend;
    }

    public static function getAcknowledgement($args = [])
    {
        $curlResponse = CurlModel::execSimple([
            'url'     => rtrim($args['config']['exportSeda']['urlSAEService'], '/') . '/medona/message/reference?reference='.urlencode($args['reference']."_Ack"),
            'method'  => 'GET',
            'cookie'  => 'LAABS-AUTH=' . urlencode($args['config']['exportSeda']['token']),
            'headers' => [
                'Accept: application/json',
                'Content-Type: application/json',
                'User-Agent: ' . $args['config']['exportSeda']['userAgent']
            ]
        ]);

        if (!empty($curlResponse['errors'])) {
            return ['errors' => 'Error returned by the route /medona/message/reference : ' . $curlResponse['errors']];
        } elseif ($curlResponse['code'] != 200) {
            return ['errors' => 'Error returned by the route /medona/message/reference : ' . $curlResponse['response']['message']];
        }

        $messageId = $curlResponse['response']['messageId'];

        $curlResponse = CurlModel::execSimple([
            'url'     => rtrim($args['config']['exportSeda']['urlSAEService'], '/') . '/medona/message/'.urlencode($messageId).'/Export',
            'method'  => 'GET',
            'cookie'  => 'LAABS-AUTH=' . urlencode($args['config']['exportSeda']['token']),
            'headers' => [
                'Accept: application/zip',
                'Content-Type: application/json',
                'User-Agent: ' . $args['config']['exportSeda']['userAgent']
            ]
        ]);

        if (!empty($curlResponse['errors'])) {
            return ['errors' => 'Error returned by the route /medona/message/{messageId}/Export : ' . $curlResponse['errors']];
        } elseif ($curlResponse['code'] != 200) {
            return ['errors' => 'Error returned by the route /medona/message/{messageId}/Export : ' . $curlResponse['response']['message']];
        }

        $encodedAcknowledgement = ExportSEDATrait::getXmlFromZipMessage([
            'encodedZipDocument' => base64_encode($curlResponse['response']),
            'messageId'          => $messageId
        ]);
        if (!empty($encodedAcknowledgement['errors'])) {
            return ['errors' => 'Error during getXmlFromZipMessage process : ' . $encodedAcknowledgement['errors']];
        }

        return ['encodedAcknowledgement' => $encodedAcknowledgement['encodedDocument']];
    }

    public static function getXmlFromZipMessage(array $args)
    {
        $tmpPath = CoreConfigModel::getTmpPath();

        $zipDocumentOnTmp = $tmpPath . mt_rand() .'_' . $GLOBALS['id'] . '_acknowledgement.7z';
        file_put_contents($zipDocumentOnTmp, base64_decode($args['encodedZipDocument']));

        $path = $tmpPath. mt_rand() .'_' . $GLOBALS['id'];
        shell_exec("7z x $zipDocumentOnTmp -o$path");

        $fullFilePath = $path."/".$args['messageId'].".xml";
        if (!file_exists($fullFilePath)) {
            return ['errors' => "getDocumentFromEncodedZip : No document was found in Zip"];
        }
        
        $content = file_get_contents($fullFilePath);
        $xmlfile = simplexml_load_file($fullFilePath);
        unlink($zipDocumentOnTmp);
        unlink($fullFilePath);
        rmdir($path);

        return ['encodedDocument' => base64_encode($content), 'xmlContent' => $xmlfile];
    }

    public static function checkAcknowledgmentRecordManagement(array $args)
    {
        ValidatorModel::notEmpty($args, ['resId']);
        ValidatorModel::intVal($args, ['resId']);

        $acknowledgement = AttachmentModel::get([
            'select' => ['res_id_master', 'path', 'filename', 'docserver_id', 'fingerprint'],
            'where'  => ['res_id_master = ?', 'attachment_type = ?', 'status = ?'],
            'data'   => [$args['resId'], 'acknowledgement_record_management', 'TRA']
        ])[0];
        if (empty($acknowledgement)) {
            return ['errors' => ['No acknowledgement found']];
        }

        $docserver = DocserverModel::getByDocserverId(['docserverId' => $acknowledgement['docserver_id'], 'select' => ['path_template', 'docserver_type_id']]);
        if (empty($docserver['path_template']) || !file_exists($docserver['path_template'])) {
            return ['errors' => ['Docserver does not exists']];
        }

        $pathToDocument = $docserver['path_template'] . str_replace('#', DIRECTORY_SEPARATOR, $acknowledgement['path']) . $acknowledgement['filename'];
        if (!file_exists($pathToDocument)) {
            return ['errors' => ['File does not exists']];
        }

        $docserverType = DocserverTypeModel::getById(['id' => $docserver['docserver_type_id'], 'select' => ['fingerprint_mode']]);
        $fingerprint = StoreController::getFingerPrint(['filePath' => $pathToDocument, 'mode' => $docserverType['fingerprint_mode']]);
        if (!empty($acknowledgement['fingerprint']) && $acknowledgement['fingerprint'] != $fingerprint) {
            return ['errors' => ['Fingerprint does not match']];
        }
        
        $acknowledgementXml = @simplexml_load_file($pathToDocument);
        if (empty($acknowledgementXml)) {
            return ['errors' => ['Acknowledgement is not readable']];
        }

        $messageExchange = MessageExchangeModel::getMessageByReference(['select' => ['message_id'], 'reference' => (string)$acknowledgementXml->MessageReceivedIdentifier]);
        if (empty($messageExchange)) {
            return ['errors' => ['No acknowledgement found with this reference']];
        }

        $unitIdentifier = MessageExchangeModel::getUnitIdentifierByResId(['select' => ['message_id'], 'resId' => $args['resId']]);
        if ($unitIdentifier[0]['message_id'] != $messageExchange['message_id']) {
            return ['errors' => ['Wrong acknowledgement']];
        }

        return true;
    }

    public static function checkReplyRecordManagement(array $args)
    {
        ValidatorModel::notEmpty($args, ['resId']);
        ValidatorModel::intVal($args, ['resId']);

        $reply = AttachmentModel::get([
            'select' => ['res_id_master', 'path', 'filename', 'docserver_id', 'fingerprint'],
            'where'  => ['res_id_master = ?', 'attachment_type = ?', 'status = ?'],
            'data'   => [$args['resId'], 'reply_record_management', 'TRA']
        ])[0];
        if (empty($reply)) {
            return ['errors' => ['No reply found']];
        }

        $docserver = DocserverModel::getByDocserverId(['docserverId' => $reply['docserver_id'], 'select' => ['path_template', 'docserver_type_id']]);
        if (empty($docserver['path_template']) || !file_exists($docserver['path_template'])) {
            return ['errors' => ['Docserver does not exists']];
        }

        $pathToDocument = $docserver['path_template'] . str_replace('#', DIRECTORY_SEPARATOR, $reply['path']) . $reply['filename'];
        if (!file_exists($pathToDocument)) {
            return ['errors' => ['File does not exists']];
        }

        $docserverType = DocserverTypeModel::getById(['id' => $docserver['docserver_type_id'], 'select' => ['fingerprint_mode']]);
        $fingerprint = StoreController::getFingerPrint(['filePath' => $pathToDocument, 'mode' => $docserverType['fingerprint_mode']]);
        if (!empty($reply['fingerprint']) && $reply['fingerprint'] != $fingerprint) {
            return ['errors' => ['Fingerprint does not match']];
        }
        
        $replyXml = @simplexml_load_file($pathToDocument);
        if (empty($replyXml)) {
            return ['errors' => ['Reply is not readable']];
        }

        $messageExchange = MessageExchangeModel::getMessageByReference(['select' => ['message_id'], 'reference' => (string)$replyXml->MessageRequestIdentifier]);
        if (empty($messageExchange)) {
            return ['errors' => ['No reply found with this reference']];
        }

        $unitIdentifier = MessageExchangeModel::getUnitIdentifierByResId(['select' => ['message_id'], 'resId' => $args['resId']]);
        if ($unitIdentifier[0]['message_id'] != $messageExchange['message_id']) {
            return ['errors' => ['Wrong reply']];
        }

        if ($args['data']['resetAction']) {
            if (strpos((string)$replyXml->ReplyCode, '000') !== false) {
                return ['errors' => ['Mail already archived']];
            }

            AttachmentModel::update([
                'set'   => ['status' => 'DEL'],
                'where' => ['attachment_type in (?)', 'res_id_master = ?'],
                'data'  => [['acknowledgement_record_management', 'reply_record_management'], $args['resId']]
            ]);

            MessageExchangeModel::deleteUnitIdentifier(['where' => ['res_id = ?'], 'data' => [$args['resId']]]);
            MessageExchangeModel::delete(['where' => ['reference in (?)'], 'data' => [[(string) $replyXml->MessageRequestIdentifier, (string) $replyXml->MessageIdentifier]]]);
        } elseif (strpos((string)$replyXml->ReplyCode, '000') === false) {
            return ['errors' => ['Mail rejected from SAE']];
        }

        return true;
    }
}
