<?php

/**
* Copyright Maarch since 2008 under licence GPLv3.
* See LICENCE.txt file at the root folder for more details.
* This file is part of Maarch software.
*
*/

/**
* @brief Seda Controller
* @author dev@maarch.org
*/

namespace ExportSeda\controllers;

use Attachment\models\AttachmentModel;
use Convert\models\AdrModel;
use Docserver\models\DocserverModel;
use Docserver\models\DocserverTypeModel;
use Doctype\models\DoctypeModel;
use Email\models\EmailModel;
use Entity\models\EntityModel;
use ExportSeda\controllers\ExportSEDATrait;
use Folder\models\FolderModel;
use Group\controllers\PrivilegeController;
use History\controllers\HistoryController;
use MessageExchange\models\MessageExchangeModel;
use Note\models\NoteModel;
use Parameter\models\ParameterModel;
use Resource\controllers\ResController;
use Resource\controllers\ResourceListController;
use Resource\controllers\StoreController;
use Resource\models\ResModel;
use Respect\Validation\Validator;
use Slim\Http\Request;
use Slim\Http\Response;
use SrcCore\models\CoreConfigModel;
use SrcCore\models\CurlModel;
use User\models\UserModel;

class SedaController
{
    public function checkSendToRecordManagement(Request $request, Response $response, array $aArgs)
    {
        $body = $request->getParsedBody();

        if (!Validator::arrayType()->notEmpty()->validate($body['resources'])) {
            return $response->withStatus(400)->withJson(['errors' => 'Body resources is empty or not an array']);
        }

        $errors = ResourceListController::listControl(['groupId' => $aArgs['groupId'], 'userId' => $aArgs['userId'], 'basketId' => $aArgs['basketId'], 'currentUserId' => $GLOBALS['id']]);
        if (!empty($errors['errors'])) {
            return $response->withStatus($errors['code'])->withJson(['errors' => $errors['errors']]);
        }

        $body['resources'] = array_slice($body['resources'], 0, 500);
        if (!ResController::hasRightByResId(['resId' => $body['resources'], 'userId' => $GLOBALS['id']])) {
            return $response->withStatus(403)->withJson(['errors' => 'Document out of perimeter']);
        }

        $firstResource = $body['resources'][0];

        $resource = ResModel::getById(['resId' => $firstResource, 'select' => ['res_id', 'destination', 'type_id', 'subject', 'linked_resources', 'retention_frozen', 'binding', 'creation_date']]);
        if (empty($resource)) {
            return $response->withStatus(400)->withJson(['errors' => 'resource does not exists']);
        } elseif (empty($resource['destination'])) {
            return $response->withStatus(400)->withJson(['errors' => 'resource has no destination', 'lang' => 'noDestination']);
        } elseif ($resource['retention_frozen'] === true) {
            return $response->withStatus(400)->withJson(['errors' => 'retention rule is frozen', 'lang' => 'retentionRuleFrozen']);
        }

        $attachments = AttachmentModel::get([
            'select' => ['res_id'],
            'where'  => ['res_id_master = ?', 'attachment_type in (?)', 'status = ?'],
            'data'   => [$firstResource, ['acknowledgement_record_management', 'reply_record_management'], 'TRA']
        ]);
        if (!empty($attachments)) {
            return $response->withStatus(400)->withJson(['errors' => 'acknowledgement or reply already exists', 'lang' => 'recordManagement_alreadySent']);
        }

        $doctype = DoctypeModel::getById(['id' => $resource['type_id'], 'select' => ['description', 'duration_current_use', 'retention_rule', 'action_current_use', 'retention_final_disposition']]);
        if (empty($doctype['retention_rule']) || empty($doctype['retention_final_disposition']) || empty($doctype['duration_current_use'])) {
            return $response->withStatus(400)->withJson(['errors' => 'retention_rule, retention_final_disposition or duration_current_use is empty for doctype', 'lang' => 'noRetentionInfo']);
        } else {
            $bindingDocument    = ParameterModel::getById(['select' => ['param_value_string'], 'id' => 'bindingDocumentFinalAction']);
            $nonBindingDocument = ParameterModel::getById(['select' => ['param_value_string'], 'id' => 'nonBindingDocumentFinalAction']);
            if ($resource['binding'] === null && !in_array($doctype['action_current_use'], ['transfer', 'copy'])) {
                return $response->withStatus(400)->withJson(['errors' => 'action_current_use is not transfer or copy', 'lang' => 'noTransferCopyRecordManagement']);
            } elseif ($resource['binding'] === true && !in_array($bindingDocument['param_value_string'], ['transfer', 'copy'])) {
                return $response->withStatus(400)->withJson(['errors' => 'binding document is not transfer or copy', 'lang' => 'noTransferCopyBindingRecordManagement']);
            } elseif ($resource['binding'] === false && !in_array($nonBindingDocument['param_value_string'], ['transfer', 'copy'])) {
                return $response->withStatus(400)->withJson(['errors' => 'no binding document is not transfer or copy', 'lang' => 'noTransferCopyNoBindingRecordManagement']);
            }
            $date = new \DateTime($resource['creation_date']);
            $date->add(new \DateInterval("P{$doctype['duration_current_use']}D"));
            if (strtotime($date->format('Y-m-d')) >= time()) {
                return $response->withStatus(400)->withJson(['errors' => 'duration current use is not exceeded', 'lang' => 'durationCurrentUseNotExceeded']);
            }
        }
        $entity = EntityModel::getByEntityId(['entityId' => $resource['destination'], 'select' => ['producer_service', 'entity_label']]);
        if (empty($entity['producer_service'])) {
            return $response->withStatus(400)->withJson(['errors' => 'producer_service is empty for this entity', 'lang' => 'noProducerService']);
        }

        $config = CoreConfigModel::getJsonLoaded(['path' => 'apps/maarch_entreprise/xml/config.json']);
        if (empty($config['exportSeda']['senderOrgRegNumber'])) {
            return $response->withStatus(400)->withJson(['errors' => 'No senderOrgRegNumber found in config.json', 'lang' => 'noSenderOrgRegNumber']);
        }
        if (empty($config['exportSeda']['accessRuleCode'])) {
            return $response->withStatus(400)->withJson(['errors' => 'No accessRuleCode found in config.json', 'lang' => 'noAccessRuleCode']);
        }

        $return = SedaController::initArchivalData([
            'resource'           => $resource,
            'senderOrgRegNumber' => $config['exportSeda']['senderOrgRegNumber'],
            'entity'             => $entity,
            'doctype'            => $doctype
        ]);

        if (!empty($return['errors'])) {
            return $response->withStatus(400)->withJson(['errors' => $return['errors']]);
        } else {
            $return = $return['archivalData'];
        }

        $archivalAgreements = SedaController::getArchivalAgreements([
            'config'              => $config,
            'senderArchiveEntity' => $config['exportSeda']['senderOrgRegNumber'],
            'producerService'     => $entity['producer_service']
        ]);
        if (!empty($archivalAgreements['errors'])) {
            return $response->withStatus(400)->withJson($archivalAgreements);
        }
        $recipientArchiveEntities = SedaController::getRecipientArchiveEntities(['config' => $config, 'archivalAgreements' => $archivalAgreements['archivalAgreements']]);
        if (!empty($recipientArchiveEntities['errors'])) {
            return $response->withStatus(400)->withJson($recipientArchiveEntities);
        }

        $return['archivalAgreements']       = $archivalAgreements['archivalAgreements'];
        $return['recipientArchiveEntities'] = $recipientArchiveEntities['archiveEntities'];

        $unitIdentifier = MessageExchangeModel::getUnitIdentifierByResId(['select' => ['message_id'], 'resId' => (string)$firstResource]);
        if (!empty($unitIdentifier[0]['message_id'])) {
            MessageExchangeModel::delete(['where' => ['message_id = ?'], 'data' => [$unitIdentifier[0]['message_id']]]);
        }
        MessageExchangeModel::deleteUnitIdentifier(['where' => ['res_id = ?'], 'data' => [$firstResource]]);
        
        return $response->withJson($return);
    }

    public static function initArchivalData($args = [])
    {
        $date = new \DateTime();

        $return = [
            'data' => [
                'entity' => [
                    'label'               => $args['entity']['entity_label'],
                    'producerService'     => $args['entity']['producer_service'],
                    'senderArchiveEntity' => $args['senderOrgRegNumber'],
                ],
                'doctype' => [
                    'label'                     => $args['doctype']['description'],
                    'retentionRule'             => $args['doctype']['retention_rule'],
                    'retentionFinalDisposition' => $args['doctype']['retention_final_disposition']
                ],
                'slipInfo' => [
                    'slipId'    => $GLOBALS['login'] . '-' . $date->format('Ymd-His'),
                    'archiveId' => 'archive_' . $args['resource']['res_id']
                ]
            ],
            'archiveUnits'   => [],
            'additionalData' => ['folders' => [], 'linkedResources' => []]
        ];

        $document = ResModel::getById(['select' => ['docserver_id', 'path', 'filename', 'version', 'fingerprint'], 'resId' => $args['resource']['res_id']]);
        if (!empty($document['docserver_id']) && !empty($document['filename'])) {
            $convertedDocument = AdrModel::getDocuments([
                'select'    => ['docserver_id', 'path', 'filename', 'fingerprint'],
                'where'     => ['res_id = ?', 'type = ?', 'version = ?'],
                'data'      => [$args['resource']['res_id'], 'SIGN', $document['version']],
                'limit'     => 1
            ]);
            $document = $convertedDocument[0] ?? $document;
    
            $docserver = DocserverModel::getByDocserverId(['docserverId' => $document['docserver_id'], 'select' => ['path_template', 'docserver_type_id']]);
            if (empty($docserver['path_template']) || !file_exists($docserver['path_template'])) {
                return ['errors' => 'Docserver does not exist'];
            }
    
            $pathToDocument = $docserver['path_template'] . str_replace('#', DIRECTORY_SEPARATOR, $document['path']) . $document['filename'];
            if (!file_exists($pathToDocument)) {
                return ['errors' => 'Document not found on docserver'];
            }
    
            $docserverType = DocserverTypeModel::getById(['id' => $docserver['docserver_type_id'], 'select' => ['fingerprint_mode']]);
            $fingerprint = StoreController::getFingerPrint(['filePath' => $pathToDocument, 'mode' => $docserverType['fingerprint_mode']]);
            if (empty($convertedDocument) && empty($document['fingerprint'])) {
                ResModel::update(['set' => ['fingerprint' => $fingerprint], 'where' => ['res_id = ?'], 'data' => [$args['resource']['res_id']]]);
                $document['fingerprint'] = $fingerprint;
            }
    
            if ($document['fingerprint'] != $fingerprint) {
                return ['errors' => 'Fingerprints do not match'];
            }
    
            $fileContent = file_exists($pathToDocument);
            if ($fileContent === false) {
                return ['errors' => 'Document not found on docserver'];
            }

            $return['archiveUnits'][0] = [
                'id'               => 'letterbox_' . $args['resource']['res_id'],
                'label'            => $args['resource']['subject'],
                'type'             => 'mainDocument',
                'descriptionLevel' => 'Item'
            ];

            if ($args['getFile']) {
                $return['archiveUnits'][0]['filePath'] = $pathToDocument;
            }
        }
        
        $attachments = AttachmentModel::get([
            'select'  => ['res_id', 'title', 'docserver_id', 'path', 'filename', 'res_id_master', 'fingerprint', 'creation_date'],
            'where'   => ['res_id_master = ?', 'status in (?)'],
            'data'    => [$args['resource']['res_id'], ['A_TRA', 'TRA']],
            'orderBy' => ['modification_date DESC']
        ]);
        foreach ($attachments as $attachment) {
            $tmpAttachment = [
                'id'               => 'attachment_' . $attachment['res_id'],
                'label'            => $attachment['title'],
                'type'             => 'attachment',
                'descriptionLevel' => 'Item',
                'creationDate'     => $attachment['creation_date']
            ];
            if ($args['getFile']) {
                $attachment = ExportSEDATrait::getAttachmentFilePath(['data' => $attachment]);
                $tmpAttachment['filePath'] = $attachment['filePath'];
            }
            $return['archiveUnits'][] = $tmpAttachment;
        }

        $notes = NoteModel::get(['select' => ['note_text', 'id', 'creation_date'], 'where' => ['identifier = ?'], 'data' => [$args['resource']['res_id']]]);
        foreach ($notes as $note) {
            $tmpNote = [
                'id'               => 'note_' . $note['id'],
                'label'            => $note['note_text'],
                'type'             => 'note',
                'descriptionLevel' => 'Item',
                'creationDate'     => $note['creation_date']
            ];
            if ($args['getFile']) {
                $note = ExportSEDATrait::getNoteFilePath(['id' => $note['id']]);
                $tmpNote['filePath'] = $note['filePath'];
            }
            $return['archiveUnits'][] = $tmpNote;
        }

        $emails = EmailModel::get([
            'select'  => ['object', 'id', 'body', 'sender', 'recipients', 'creation_date'],
            'where'   => ['document->>\'id\' = ?', 'status = ?'],
            'data'    => [$args['resource']['res_id'], 'SENT'],
            'orderBy' => ['send_date desc']
        ]);
        foreach ($emails as $email) {
            $tmpEmail = [
                'id'               => 'email_' . $email['id'],
                'label'            => $email['object'],
                'type'             => 'email',
                'descriptionLevel' => 'Item',
                'creationDate'     => $email['creation_date']
            ];
            if ($args['getFile']) {
                $email = ExportSEDATrait::getEmailFilePath(['data' => $email]);
                $tmpEmail['filePath'] = $email['filePath'];
            }
            $return['archiveUnits'][] = $tmpEmail;
        }

        $tmpSummarySheet = [
            'id'               => 'summarySheet_' . $args['resource']['res_id'],
            'label'            => 'Fiche de liaison',
            'type'             => 'summarySheet',
            'descriptionLevel' => 'Item',
            'creationDate'     => $date->format('Y-m-d H:i:s')
        ];
        if ($args['getFile']) {
            $summarySheet = ExportSEDATrait::getSummarySheetFilePath(['resId' => $args['resource']['res_id']]);
            $tmpSummarySheet['filePath'] = $summarySheet['filePath'];
        }
        $return['archiveUnits'][] = $tmpSummarySheet;

        $linkedResourcesIds = json_decode($args['resource']['linked_resources'], true);
        if (!empty($linkedResourcesIds)) {
            $linkedResources = [];
            $linkedResources = ResModel::get([
                'select' => ['subject as object', 'alt_identifier as chrono'],
                'where'  => ['res_id in (?)'],
                'data'   => [$linkedResourcesIds]
            ]);
            $return['additionalData']['linkedResources'] = $linkedResources;
        }

        $entities = UserModel::getEntitiesById(['id' => $GLOBALS['id'], 'select' => ['entities.id']]);
        $entities = array_column($entities, 'id');

        if (empty($entities)) {
            $entities = [0];
        }

        $folders = FolderModel::getWithEntitiesAndResources([
            'select' => ['DISTINCT(folders.id)', 'folders.label'],
            'where'  => ['res_id = ?', '(entity_id in (?) OR keyword = ?)', 'folders.public = TRUE'],
            'data'   => [$args['resource']['res_id'], $entities, 'ALL_ENTITIES']
        ]);
        foreach ($folders as $folder) {
            $return['additionalData']['folders'][] = [
                'id'    => 'folder_' . $folder['id'],
                'label' => $folder['label']
            ];
        }

        return ['archivalData' => $return];
    }

    public function getRecipientArchiveEntities($args = [])
    {
        $archiveEntities = [];
        if (strtolower($args['config']['exportSeda']['sae']) == 'maarchrm') {
            $curlResponse = CurlModel::execSimple([
                'url'     => rtrim($args['config']['exportSeda']['urlSAEService'], '/') . '/organization/organization/Byrole/archiver',
                'method'  => 'GET',
                'cookie'  => 'LAABS-AUTH=' . urlencode($args['config']['exportSeda']['token']),
                'headers' => [
                    'Accept: application/json',
                    'Content-Type: application/json',
                    'User-Agent: ' . $args['config']['exportSeda']['userAgent']
                ]
            ]);

            if (!empty($curlResponse['errors'])) {
                return ['errors' => 'Error returned by the route /organization/organization/Byrole/archiver : ' . $curlResponse['errors']];
            } elseif ($curlResponse['code'] != 200) {
                return ['errors' => 'Error returned by the route /organization/organization/Byrole/archiver : ' . $curlResponse['response']['message']];
            }

            $archiveEntitiesAllowed = array_column($args['archivalAgreements'], 'archiveEntityRegNumber');

            $archiveEntities[] = [
                'id'    => "",
                'label' => null
            ];
            foreach ($curlResponse['response'] as $retentionRule) {
                if (in_array($retentionRule['registrationNumber'], $archiveEntitiesAllowed)) {
                    $archiveEntities[] = [
                        'id'    => $retentionRule['registrationNumber'],
                        'label' => $retentionRule['displayName']
                    ];
                }
            }
        } else {
            if (is_array($args['config']['exportSeda']['externalSAE']['archiveEntities'])) {
                foreach ($args['config']['exportSeda']['externalSAE']['archiveEntities'] as $archiveEntity) {
                    $archiveEntities[] = [
                            'id'    => $archiveEntity['id'],
                            'label' => $archiveEntity['label']
                        ];
                }
            }
        }

        return ['archiveEntities' => $archiveEntities];
    }

    public function getArchivalAgreements($args = [])
    {
        $archivalAgreements = [];
        if (strtolower($args['config']['exportSeda']['sae']) == 'maarchrm') {
            $curlResponse = CurlModel::execSimple([
                'url'     => rtrim($args['config']['exportSeda']['urlSAEService'], '/') . '/medona/archivalAgreement/Index',
                'method'  => 'GET',
                'cookie'  => 'LAABS-AUTH=' . urlencode($args['config']['exportSeda']['token']),
                'headers' => [
                    'Accept: application/json',
                    'Content-Type: application/json',
                    'User-Agent: ' . $args['config']['exportSeda']['userAgent']
                ]
            ]);

            if (!empty($curlResponse['errors'])) {
                return ['errors' => 'Error returned by the route /medona/archivalAgreement/Index : ' . $curlResponse['errors']];
            } elseif ($curlResponse['code'] != 200) {
                return ['errors' => 'Error returned by the route /medona/archivalAgreement/Index : ' . $curlResponse['response']['message']];
            }

            $producerService = SedaController::getProducerServiceInfo(['config' => $args['config'], 'producerServiceName' => $args['producerService']]);
            if (!empty($producerService['errors'])) {
                return ['errors' => $curlResponse['errors']];
            } elseif (empty($producerService['producerServiceInfo'])) {
                return ['errors' => 'ProducerService does not exists in MaarchRM', 'lang' => 'producerServiceDoesNotExists'];
            }

            $archivalAgreements[] = [
                'id'    => "",
                'label' => null
            ];
            foreach ($curlResponse['response'] as $retentionRule) {
                if ($retentionRule['depositorOrgRegNumber'] == $args['senderArchiveEntity'] && in_array($producerService['producerServiceInfo']['orgId'], $retentionRule['originatorOrgIds'])) {
                    $archivalAgreements[] = [
                        'id'            => $retentionRule['reference'],
                        'label'         => $retentionRule['name'],
                        'archiveEntityRegNumber' => $retentionRule['archiverOrgRegNumber']
                    ];
                }
            }
        } else {
            if (is_array($args['config']['exportSeda']['externalSAE']['archivalAgreements'])) {
                foreach ($args['config']['exportSeda']['externalSAE']['archivalAgreements'] as $archivalAgreement) {
                    $archivalAgreements[] = [
                        'id'    => $archivalAgreement['id'],
                        'label' => $archivalAgreement['label']
                    ];
                }
            }
        }

        return ['archivalAgreements' => $archivalAgreements];
    }

    public function getProducerServiceInfo($args = [])
    {
        $curlResponse = CurlModel::execSimple([
            'url'     => rtrim($args['config']['exportSeda']['urlSAEService'], '/') . '/organization/organization/Search?term=' . $args['producerServiceName'],
            'method'  => 'GET',
            'cookie'  => 'LAABS-AUTH=' . urlencode($args['config']['exportSeda']['token']),
            'headers' => [
                'Accept: application/json',
                'Content-Type: application/json',
                'User-Agent: ' . $args['config']['exportSeda']['userAgent']
            ]
        ]);

        if (!empty($curlResponse['errors'])) {
            return ['errors' => 'Error returned by the route /organization/organization/Search : ' . $curlResponse['errors']];
        } elseif ($curlResponse['code'] != 200) {
            return ['errors' => 'Error returned by the route /organization/organization/Search : ' . $curlResponse['response']['message']];
        }

        foreach ($curlResponse['response'] as $organization) {
            if ($organization['registrationNumber'] == $args['producerServiceName']) {
                return ['producerServiceInfo' => $organization];
            }
        }

        return ['producerServiceInfo' => null];
    }

    public function getRetentionRules(Request $request, Response $response)
    {
        if (!PrivilegeController::hasPrivilege(['privilegeId' => 'admin_architecture', 'userId' => $GLOBALS['id']])) {
            return $response->withStatus(403)->withJson(['errors' => 'Service forbidden']);
        }

        $retentionRules = [];

        $config = CoreConfigModel::getJsonLoaded(['path' => 'apps/maarch_entreprise/xml/config.json']);
        if (empty($config['exportSeda']['sae'])) {
            return $response->withJson(['retentionRules' => $retentionRules]);
        }
        
        if (strtolower($config['exportSeda']['sae']) == 'maarchrm') {
            $curlResponse = CurlModel::execSimple([
                'url'     => rtrim($config['exportSeda']['urlSAEService'], '/') . '/recordsManagement/retentionRule/Index',
                'method'  => 'GET',
                'cookie'  => 'LAABS-AUTH=' . urlencode($config['exportSeda']['token']),
                'headers' => [
                    'Accept: application/json',
                    'Content-Type: application/json',
                    'User-Agent: ' . $config['exportSeda']['userAgent']
                ]
            ]);

            if (!empty($curlResponse['errors'])) {
                return $response->withStatus(400)->withJson(['errors' => 'Error returned by the route /recordsManagement/retentionRule/Index : ' . $curlResponse['errors']]);
            } elseif ($curlResponse['code'] != 200) {
                return $response->withStatus(400)->withJson(['errors' => 'Error returned by the route /recordsManagement/retentionRule/Index : ' . $curlResponse['response']['message']]);
            }

            $retentionRules[] = [
                'id'    => "",
                'label' => null
            ];
            foreach ($curlResponse['response'] as $retentionRule) {
                $retentionRules[] = [
                    'id'    => $retentionRule['code'],
                    'label' => $retentionRule['label']
                ];
            }
        } else {
            if (is_array($config['exportSeda']['externalSAE']['retentionRules'])) {
                foreach ($config['exportSeda']['externalSAE']['retentionRules'] as $rule) {
                    $retentionRules[] = [
                        'id'    => $rule['id'],
                        'label' => $rule['label']
                    ];
                }
            }
        }

        return $response->withJson(['retentionRules' => $retentionRules]);
    }

    public function setBindingDocument(Request $request, Response $response)
    {
        if (!PrivilegeController::hasPrivilege(['privilegeId' => 'set_binding_document', 'userId' => $GLOBALS['id']])) {
            return $response->withStatus(403)->withJson(['errors' => 'Service forbidden']);
        }

        $body = $request->getParsedBody();

        if (!Validator::arrayType()->notEmpty()->validate($body['resources'])) {
            return $response->withStatus(400)->withJson(['errors' => 'Body resources is empty or not an array']);
        }
        if ($body['binding'] !== null && !Validator::boolType()->validate($body['binding'])) {
            return $response->withStatus(400)->withJson(['errors' => 'Body binding is not a boolean']);
        }

        $body['resources'] = array_slice($body['resources'], 0, 500);
        if (!ResController::hasRightByResId(['resId' => $body['resources'], 'userId' => $GLOBALS['id']])) {
            return $response->withStatus(403)->withJson(['errors' => 'Document out of perimeter']);
        }

        $documents = ResModel::get([
            'select' => ['alt_identifier', 'res_id'],
            'where'  => ['res_id in (?)'],
            'data'   => [$body['resources']]
        ]);
        $documents = array_column($documents, 'alt_identifier', 'res_id');

        if ($body['binding'] === null) {
            $binding = null;
            $info    = _RESET_BINDING_DOCUMENT;
        } else {
            $binding = $body['binding'] ? 'true' : 'false';
            $info    = $body['binding'] ? _SET_BINDING_DOCUMENT : _SET_NON_BINDING_DOCUMENT;
        }

        ResModel::update([
            'set'   => [
                'binding' => $binding,
            ],
            'where' => ['res_id in (?)'],
            'data'  => [$body['resources']]
        ]);

        foreach ($body['resources'] as $resId) {
            HistoryController::add([
                'tableName' => 'res_letterbox',
                'recordId'  => $resId,
                'eventType' => 'UP',
                'info'      => $info . " : " . $documents[$resId],
                'moduleId'  => 'resource',
                'eventId'   => 'resourceModification',
            ]);
        }
        
        return $response->withStatus(204);
    }

    public function freezeRetentionRule(Request $request, Response $response)
    {
        if (!PrivilegeController::hasPrivilege(['privilegeId' => 'freeze_retention_rule', 'userId' => $GLOBALS['id']])) {
            return $response->withStatus(403)->withJson(['errors' => 'Service forbidden']);
        }

        $body = $request->getParsedBody();

        if (!Validator::arrayType()->notEmpty()->validate($body['resources'])) {
            return $response->withStatus(400)->withJson(['errors' => 'Body resources is empty or not an array']);
        }
        if (!Validator::boolType()->validate($body['freeze'])) {
            return $response->withStatus(400)->withJson(['errors' => 'Body freeze is not a boolean']);
        }

        $body['resources'] = array_slice($body['resources'], 0, 500);
        if (!ResController::hasRightByResId(['resId' => $body['resources'], 'userId' => $GLOBALS['id']])) {
            return $response->withStatus(403)->withJson(['errors' => 'Document out of perimeter']);
        }

        $documents = ResModel::get([
            'select' => ['alt_identifier', 'res_id'],
            'where'  => ['res_id in (?)'],
            'data'   => [$body['resources']]
        ]);
        $documents = array_column($documents, 'alt_identifier', 'res_id');

        $freeze = $body['freeze'] ? 'true' : 'false';
        $info   = $body['freeze'] ? _FREEZE_RETENTION_RULE : _UNFREEZE_RETENTION_RULE;
        
        ResModel::update([
            'set'   => [
                'retention_frozen' => $freeze,
            ],
            'where' => ['res_id in (?)'],
            'data'  => [$body['resources']]
        ]);

        foreach ($body['resources'] as $resId) {
            HistoryController::add([
                'tableName' => 'res_letterbox',
                'recordId'  => $resId,
                'eventType' => 'UP',
                'info'      => $info . " : " . $documents[$resId],
                'moduleId'  => 'resource',
                'eventId'   => 'resourceModification',
            ]);
        }
        
        return $response->withStatus(204);
    }
}
