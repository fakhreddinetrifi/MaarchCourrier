<?php

/**
* Copyright Maarch since 2008 under licence GPLv3.
* See LICENCE.txt file at the root folder for more details.
* This file is part of Maarch software.

* @brief   Pre Process Action Controller
* @author  dev <dev@maarch.org>
* @ingroup core
*/

namespace Action\controllers;

use AcknowledgementReceipt\models\AcknowledgementReceiptModel;
use Action\models\ActionModel;
use Attachment\models\AttachmentModel;
use Basket\models\BasketModel;
use Basket\models\GroupBasketRedirectModel;
use Configuration\models\ConfigurationModel;
use Contact\controllers\ContactController;
use Contact\models\ContactModel;
use Convert\controllers\ConvertPdfController;
use CustomField\models\CustomFieldModel;
use Docserver\models\DocserverModel;
use Docserver\models\DocserverTypeModel;
use Doctype\models\DoctypeModel;
use Entity\models\EntityModel;
use Entity\models\ListInstanceModel;
use ExternalSignatoryBook\controllers\IxbusController;
use ExternalSignatoryBook\controllers\MaarchParapheurController;
use Group\models\GroupModel;
use IndexingModel\models\IndexingModelFieldModel;
use Note\models\NoteModel;
use Parameter\models\ParameterModel;
use RegisteredMail\models\RegisteredMailModel;
use Resource\controllers\ResController;
use Resource\controllers\ResourceListController;
use Resource\controllers\StoreController;
use Resource\models\ResModel;
use Resource\models\ResourceContactModel;
use Respect\Validation\Validator;
use Shipping\controllers\ShippingTemplateController;
use Shipping\models\ShippingTemplateModel;
use Slim\Http\Request;
use Slim\Http\Response;
use SrcCore\controllers\PreparedClauseController;
use SrcCore\models\CoreConfigModel;
use SrcCore\models\ValidatorModel;
use Template\models\TemplateModel;
use User\models\UserEntityModel;
use User\models\UserModel;
use ExportSeda\controllers\PreProcessActionSEDATrait;

class PreProcessActionController
{
    use PreProcessActionSEDATrait;

    public static function getRedirectInformations(Request $request, Response $response, array $args)
    {
        $errors = ResourceListController::listControl(['groupId' => $args['groupId'], 'userId' => $args['userId'], 'basketId' => $args['basketId'], 'currentUserId' => $GLOBALS['id']]);
        if (!empty($errors['errors'])) {
            return $response->withStatus($errors['code'])->withJson(['errors' => $errors['errors']]);
        }

        $basket = BasketModel::getById(['id' => $args['basketId'], 'select' => ['basket_id']]);
        $group = GroupModel::getById(['id' => $args['groupId'], 'select' => ['group_id']]);
        $user = UserModel::getById(['id' => $args['userId'], 'select' => ['user_id']]);

        $keywords = [
            'ALL_ENTITIES'          => '@all_entities',
            'ENTITIES_JUST_BELOW'   => '@immediate_children[@my_primary_entity]',
            'ENTITIES_BELOW'        => '@subentities[@my_entities]',
            'ALL_ENTITIES_BELOW'    => '@subentities[@my_primary_entity]',
            'ENTITIES_JUST_UP'      => '@parent_entity[@my_primary_entity]',
            'MY_ENTITIES'           => '@my_entities',
            'MY_PRIMARY_ENTITY'     => '@my_primary_entity',
            'SAME_LEVEL_ENTITIES'   => '@sisters_entities[@my_primary_entity]'
        ];

        $users = [];
        $allEntities = [];

        foreach (['ENTITY', 'USERS'] as $mode) {
            $entityRedirects = GroupBasketRedirectModel::get([
                'select'    => ['entity_id', 'keyword'],
                'where'     => ['basket_id = ?', 'group_id = ?', 'action_id = ?', 'redirect_mode = ?'],
                'data'      => [$basket['basket_id'], $group['group_id'], $args['actionId'], $mode]
            ]);

            $allowedEntities = [];
            $clauseToProcess = '';
            foreach ($entityRedirects as $entityRedirect) {
                if (!empty($entityRedirect['entity_id'])) {
                    $allowedEntities[] = $entityRedirect['entity_id'];
                } elseif (!empty($entityRedirect['keyword'])) {
                    if (!empty($keywords[$entityRedirect['keyword']])) {
                        if (!empty($clauseToProcess)) {
                            $clauseToProcess .= ', ';
                        }
                        $clauseToProcess .= $keywords[$entityRedirect['keyword']];
                    }
                }
            }

            if (!empty($clauseToProcess)) {
                $preparedClause = PreparedClauseController::getPreparedClause(['clause' => $clauseToProcess, 'login' => $user['user_id']]);
                $preparedEntities = EntityModel::get(['select' => ['entity_id'], 'where' => ['enabled = ?', "entity_id in {$preparedClause}"], 'data' => ['Y']]);
                foreach ($preparedEntities as $preparedEntity) {
                    $allowedEntities[] = $preparedEntity['entity_id'];
                }
            }

            $allowedEntities = array_unique($allowedEntities);

            if ($mode == 'USERS') {
                if (!empty($allowedEntities)) {
                    $users = UserEntityModel::getWithUsers([
                        'select'    => ['DISTINCT users.id', 'users.user_id', 'firstname', 'lastname'],
                        'where'     => ['users_entities.entity_id in (?)', 'status not in (?)'],
                        'data'      => [$allowedEntities, ['DEL', 'ABS']],
                        'orderBy'   => ['lastname', 'firstname']
                    ]);

                    foreach ($users as $key => $user) {
                        $users[$key]['labelToDisplay'] = "{$user['firstname']} {$user['lastname']}";
                        $users[$key]['descriptionToDisplay'] = UserModel::getPrimaryEntityById(['id' => $user['id'], 'select' => ['entities.entity_label']])['entity_label'];
                    }
                }
            } elseif ($mode == 'ENTITY') {
                $primaryEntity = UserModel::getPrimaryEntityById(['id' => $GLOBALS['id'], 'select' => ['entities.entity_id']]);

                $allEntities = EntityModel::get(['select' => ['id', 'entity_id', 'entity_label', 'parent_entity_id'], 'where' => ['enabled = ?'], 'data' => ['Y'], 'orderBy' => ['parent_entity_id']]);
                foreach ($allEntities as $key => $value) {
                    $allEntities[$key]['id'] = $value['entity_id'];
                    $allEntities[$key]['serialId'] = $value['id'];
                    if (empty($value['parent_entity_id'])) {
                        $allEntities[$key]['parent'] = '#';
                        $allEntities[$key]['icon'] = "fa fa-building";
                    } else {
                        $allEntities[$key]['parent'] = $value['parent_entity_id'];
                        $allEntities[$key]['icon'] = "fa fa-sitemap";
                    }
                    $allEntities[$key]['state']['opened'] = false;
                    if (in_array($value['entity_id'], $allowedEntities)) {
                        $allEntities[$key]['allowed'] = true;
                        if ($primaryEntity['entity_id'] == $value['entity_id']) {
                            $allEntities[$key]['state']['opened'] = true;
                            $allEntities[$key]['state']['selected'] = true;
                        }
                    } else {
                        $allEntities[$key]['allowed'] = false;
                        $allEntities[$key]['state']['disabled'] = true;
                    }
                    $allEntities[$key]['text'] = $value['entity_label'];
                }
            }
        }

        $parameter = ParameterModel::getById(['select' => ['param_value_int'], 'id' => 'keepDestForRedirection']);

        return $response->withJson(['entities' => $allEntities, 'users' => $users, 'keepDestForRedirection' => !empty($parameter['param_value_int'])]);
    }

    public function checkAcknowledgementReceipt(Request $request, Response $response, array $args)
    {
        $action = ActionModel::getById(['id' => $args['actionId'], 'select' => ['parameters']]);
        if (empty($action)) {
            return $response->withStatus(400)->withJson(['errors' => 'Action does not exist']);
        }
        $parameters = json_decode($action['parameters'], true);
        $mode       = $parameters['mode'] ?? 'auto';
        $data       = $request->getQueryParams();

        if (empty($data['mode']) && $mode == 'both') {
            $currentMode = 'auto';
        } elseif (empty($data['mode']) && $mode != 'both') {
            $currentMode = $mode;
        } elseif (!empty($data['mode'])) {
            $currentMode = $data['mode'];
        }
        if (!in_array($currentMode, ['manual', 'auto'])) {
            $currentMode = 'auto';
        }

        $errors = ResourceListController::listControl(['groupId' => $args['groupId'], 'userId' => $args['userId'], 'basketId' => $args['basketId'], 'currentUserId' => $GLOBALS['id']]);
        if (!empty($errors['errors'])) {
            return $response->withStatus($errors['code'])->withJson(['errors' => $errors['errors']]);
        }

        $data = $request->getParsedBody();

        if (!Validator::arrayType()->notEmpty()->validate($data['resources'])) {
            return $response->withStatus(400)->withJson(['errors' => 'Data resources is empty or not an array']);
        }

        $sendList = [];
        $sendEmail = 0;
        $sendPaper = 0;
        $noSendAR = [
            'number'    => 0,
            'list'      => [],
        ];
        $alreadyGenerated = [
            'number'    => 0,
            'list'      => [],
        ];
        $alreadySend = [
            'number'    => 0,
            'list'      => [],
        ];

        $data['resources'] = array_slice($data['resources'], 0, 500);

        $resources = ResModel::get([
            'select' => ['res_id', 'locker_user_id', 'locker_time'],
            'where'  => ['res_id in (?)'],
            'data'   => [$data['resources']]
        ]);

        $resourcesForProcess = [];
        foreach ($resources as $resource) {
            $lock = true;
            if (empty($resource['locker_user_id'] || empty($resource['locker_time']))) {
                $lock = false;
            } elseif ($resource['locker_user_id'] == $GLOBALS['id']) {
                $lock = false;
            } elseif (strtotime($resource['locker_time']) < time()) {
                $lock = false;
            }
            if (!$lock) {
                $resourcesForProcess[] = $resource['res_id'];
            }
        }
        $data['resources'] = $resourcesForProcess;

        foreach ($data['resources'] as $resId) {
            $resource = ResModel::getById(['select' => ['res_id', 'category_id', 'alt_identifier', 'type_id', 'destination'], 'resId' => $resId]);

            if (empty($resource)) {
                $noSendAR['number'] += 1;
                $noSendAR['list'][] = ['resId' => $resId, 'alt_identifier' => '', 'info' => _DOCUMENT_NOT_FOUND ];
                continue;
            }

            if (!ResController::hasRightByResId(['resId' => [$resId], 'userId' => $GLOBALS['id']])) {
                $noSendAR['number'] += 1;
                $noSendAR['list'][] = ['resId' => $resId, 'alt_identifier' => $resource['alt_identifier'], 'info' => _DOCUMENT_OUT_PERIMETER ];
                continue;
            }

            if ($resource['category_id'] != 'incoming') {
                $noSendAR['number'] += 1;
                $noSendAR['list'][] = ['resId' => $resId, 'alt_identifier' => $resource['alt_identifier'], 'info' => _NOT_INCOMING_CATEGORY ];
                continue;
            }

            $doctype = DoctypeModel::getById(['id' => $resource['type_id'], 'select' => ['process_mode']]);

            if (empty($resource['destination']) && $currentMode == 'auto') {
                $noSendAR['number'] += 1;
                $noSendAR['list'][] = ['resId' => $resId, 'alt_identifier' => $resource['alt_identifier'], 'info' => _NO_ENTITY];
                continue;
            }

            if ($doctype['process_mode'] == 'SVA') {
                $templateAttachmentType = 'sva';
            } elseif ($doctype['process_mode'] == 'SVR') {
                $templateAttachmentType = 'svr';
            } else {
                $templateAttachmentType = 'simple';
            }
            
            if ($currentMode == 'auto') {
                $entity   = EntityModel::getByEntityId(['select' => ['entity_label'], 'entityId' => $resource['destination']]);
                $template = TemplateModel::getWithAssociation([
                    'select'    => ['template_content', 'template_path', 'template_file_name'],
                    'where'     => ['template_target = ?', 'template_attachment_type = ?', 'value_field = ?'],
                    'data'      => ['acknowledgementReceipt', $templateAttachmentType, $resource['destination']]
                ]);
    
                if (empty($template[0])) {
                    $noSendAR['number'] += 1;
                    $noSendAR['list'][] = ['resId' => $resId, 'alt_identifier' => $resource['alt_identifier'], 'info' => _NO_TEMPLATE . ' \'' . $templateAttachmentType . '\' ' . _FOR_ENTITY . ' ' .$entity['entity_label'] ];
                    continue;
                }
    
                $docserver = DocserverModel::getByDocserverId(['docserverId' => 'TEMPLATES', 'select' => ['path_template']]);
                $pathToDocument = $docserver['path_template'] . str_replace('#', DIRECTORY_SEPARATOR, $template[0]['template_path']) . $template[0]['template_file_name'];
            }

            //Verify sending
            $acknowledgements = AcknowledgementReceiptModel::get([
                'select'    => ['res_id', 'type', 'format', 'creation_date', 'send_date'],
                'where'     => ['res_id = ?', 'type = ?'],
                'data'      => [$resId, $templateAttachmentType],
            ]);

            if (!empty($acknowledgements)) {
                $sent = 0;
                $generated = 0;

                foreach ($acknowledgements as $acknowledgement) {
                    if (!empty($acknowledgement['creation_date']) && !empty($acknowledgement['send_date'])) {
                        $sent += 1;
                    } elseif (!empty($acknowledgement['creation_date']) && empty($acknowledgement['send_date'])) {
                        $generated += 1;
                    }
                }

                if ($sent > 0) {
                    $alreadySend['number'] += $sent;
                    $alreadySend['list'][] = ['resId' => $resId, 'alt_identifier' => $resource['alt_identifier']];
                }

                if ($generated > 0) {
                    $alreadyGenerated['number'] += $generated;
                    $alreadyGenerated['list'][] = ['resId' => $resId, 'alt_identifier' => $resource['alt_identifier']];
                }
            }

            //Verify associated contact
            $contactsToProcess = ResourceContactModel::get([
                'select' => ['item_id'],
                'where' => ['res_id = ?', 'type = ?', 'mode = ?'],
                'data' => [$resId, 'contact', 'sender']
            ]);
            $contactsToProcess = array_column($contactsToProcess, 'item_id');

            //Verify contact informations
            $email = 0;
            $paper = 0;
            foreach ($contactsToProcess as $contactToProcess) {
                if (empty($contactToProcess)) {
                    $noSendAR['number'] += 1;
                    $noSendAR['list'][] = ['resId' => $resId, 'alt_identifier' => $resource['alt_identifier'], 'info' => _NO_CONTACT ];
                    continue 2;
                }

                $contact = ContactModel::getById(['select' => ['*'], 'id' => $contactToProcess]);

                if (empty($contact['email']) && (empty($contact['address_street']) || empty($contact['address_town']) || empty($contact['address_postcode']))) {
                    $noSendAR['number'] += 1;
                    $noSendAR['list'][] = ['resId' => $resId, 'alt_identifier' => $resource['alt_identifier'], 'info' => _USER_MISSING_INFORMATIONS ];
                    continue 2;
                }

                if (!empty($contact['email'])) {
                    if (empty($template[0]['template_content']) && $currentMode == 'auto') {
                        $noSendAR['number'] += 1;
                        $noSendAR['list'][] = ['resId' => $resId, 'alt_identifier' => $resource['alt_identifier'], 'info' => _NO_EMAIL_TEMPLATE . ' \'' . $templateAttachmentType . '\' ' . _FOR_ENTITY . ' ' . $entity['entity_label'] ];
                        continue 2;
                    } else {
                        $email += 1;
                    }
                } elseif (!empty($contact['address_street']) && !empty($contact['address_town']) && !empty($contact['address_postcode'])) {
                    if ((!file_exists($pathToDocument) || !is_file($pathToDocument)) && $currentMode == 'auto') {
                        $noSendAR['number'] += 1;
                        $noSendAR['list'][] = ['resId' => $resId, 'alt_identifier' => $resource['alt_identifier'], 'info' => _NO_PAPER_TEMPLATE . ' \'' . $templateAttachmentType . '\' ' . _FOR_ENTITY . ' ' . $entity['entity_label'] ];
                        continue 2;
                    } else {
                        $paper += 1;
                    }
                }
            }

            if ($email > 0) {
                $sendEmail += $email;
            }
            if ($paper > 0) {
                $sendPaper += $paper;
            }
            if ($email > 0 || $paper > 0) {
                $sendList[] = $resId;
            } else {
                $noSendAR['number'] += 1;
                $noSendAR['list'][] = ['resId' => $resId, 'alt_identifier' => $resource['alt_identifier'], 'info' => _NO_SENDERS ];
            }
        }

        return $response->withJson(['sendEmail' => $sendEmail, 'sendPaper' => $sendPaper, 'sendList' => $sendList,  'noSendAR' => $noSendAR, 'alreadySend' => $alreadySend, 'alreadyGenerated' => $alreadyGenerated, 'mode' => $mode]);
    }

    public function checkExternalSignatoryBook(Request $request, Response $response, array $aArgs)
    {
        $errors = ResourceListController::listControl(['groupId' => $aArgs['groupId'], 'userId' => $aArgs['userId'], 'basketId' => $aArgs['basketId'], 'currentUserId' => $GLOBALS['id']]);
        if (!empty($errors['errors'])) {
            return $response->withStatus($errors['code'])->withJson(['errors' => $errors['errors']]);
        }

        $data = $request->getParsedBody();

        $data['resources'] = array_slice($data['resources'], 0, 500);
        if (!ResController::hasRightByResId(['resId' => $data['resources'], 'userId' => $GLOBALS['id']])) {
            return $response->withStatus(403)->withJson(['errors' => 'Document out of perimeter']);
        }

        $data['resources'] = PreProcessActionController::getNonLockedResources(['resources' => $data['resources'], 'userId' => $GLOBALS['id']]);

        $loadedXml = CoreConfigModel::getXmlLoaded(['path' => 'modules/visa/xml/remoteSignatoryBooks.xml']);

        $errors = [];
        $signatureBookEnabled = '';
        if (!empty($loadedXml)) {
            $config['id'] = (string)$loadedXml->signatoryBookEnabled;
            foreach ($loadedXml->signatoryBook as $value) {
                if ($value->id == $config['id']) {
                    $config['data'] = (array)$value;
                    break;
                }
            }

            $signatureBookEnabled = $config['id'];
            $additionalsInfos = [
                'attachments'   => [],
                'noAttachment'  => []
            ];
            if ($signatureBookEnabled == 'ixbus') {
                $additionalsInfos['ixbus'] = IxbusController::getInitializeDatas($config);
            }
            if (in_array($signatureBookEnabled, ['maarchParapheur', 'fastParapheur', 'iParapheur', 'ixbus'])) {
                if (is_array($data['resources']) && count($data['resources']) == 1) {
                    $resDestination = ResModel::getById([
                        'select'   => ['destination'],
                        'resId'    => $data['resources'][0]
                    ]);
                    if (!empty($resDestination['destination'])) {
                        $destination = EntityModel::getByEntityId(['entityId' => $resDestination['destination'], 'select' => ['id']]);
                        $additionalsInfos['destinationId'] = $destination['id'];
                    }
                } else {
                    $additionalsInfos['destinationId'] = '';
                }

                foreach ($data['resources'] as $resId) {
                    $noAttachmentsResource = ResModel::getById(['resId' => $resId, 'select' => ['alt_identifier']]);
                    if (empty($noAttachmentsResource['alt_identifier'])) {
                        $noAttachmentsResource['alt_identifier'] = _UNDEFINED;
                    }

                    // Check attachments
                    $attachments = AttachmentModel::get([
                        'select'    => [
                            'res_id', 'title', 'identifier', 'attachment_type',
                            'status', 'typist', 'docserver_id', 'path', 'filename', 'creation_date',
                            'validation_date', 'relation', 'origin_id'
                        ],
                        'where'     => ["res_id_master = ?", "attachment_type not in (?)", "status not in ('DEL', 'OBS', 'FRZ', 'TMP')", "in_signature_book = 'true'"],
                        'data'      => [$resId, ['signed_response']]
                    ]);

                    $integratedResource = ResModel::get([
                        'select' => [1],
                        'where'  => ['integrations->>\'inSignatureBook\' = \'true\'', 'external_id->>\'signatureBookId\' is null', 'res_id = ?'],
                        'data'   => [$resId]
                    ]);

                    $attachmentTypes = AttachmentModel::getAttachmentsTypesByXML();
                    
                    if (empty($attachments) && empty($integratedResource)) {
                        $additionalsInfos['noAttachment'][] = ['alt_identifier' => $noAttachmentsResource['alt_identifier'], 'res_id' => $resId, 'reason' => 'noAttachmentInSignatoryBook'];
                    } else {
                        $hasSignableAttachment = false;
                        foreach ($attachments as $value) {
                            $resIdAttachment  = $value['res_id'];
                            $collId = 'attachments_coll';
                            
                            $adrInfo = ConvertPdfController::getConvertedPdfById(['resId' => $resIdAttachment, 'collId' => $collId]);
                            if (empty($adrInfo['docserver_id'])) {
                                $additionalsInfos['noAttachment'][] = ['alt_identifier' => $noAttachmentsResource['alt_identifier'], 'res_id' => $resIdAttachment, 'reason' => 'noAttachmentConversion'];
                                break;
                            }
                            $docserverInfo = DocserverModel::getByDocserverId(['docserverId' => $adrInfo['docserver_id']]);
                            if (empty($docserverInfo['path_template'])) {
                                $additionalsInfos['noAttachment'][] = ['alt_identifier' => $noAttachmentsResource['alt_identifier'], 'res_id' => $resIdAttachment, 'reason' => 'docserverDoesNotExists'];
                                break;
                            }
                            $filePath = $docserverInfo['path_template'] . str_replace('#', '/', $adrInfo['path']) . $adrInfo['filename'];
                            if (!is_file($filePath)) {
                                $additionalsInfos['noAttachment'][] = ['alt_identifier' => $noAttachmentsResource['alt_identifier'], 'res_id' => $resIdAttachment, 'reason' => 'fileDoesNotExists'];
                                break;
                            }
                            if ($attachmentTypes[$value['attachment_type']]['sign']) {
                                $hasSignableAttachment = true;
                            }
                        }
                        if (!empty($integratedResource)) {
                            $adrInfo = ConvertPdfController::getConvertedPdfById(['resId' => $resId, 'collId' => 'letterbox_coll']);
                            if (empty($adrInfo['docserver_id']) || strtolower(pathinfo($adrInfo['filename'], PATHINFO_EXTENSION)) != 'pdf') {
                                $additionalsInfos['noAttachment'][] = ['alt_identifier' => $noAttachmentsResource['alt_identifier'], 'res_id' => $resId, 'reason' => 'noMailConversion'];
                                break;
                            }
                            $docserverInfo = DocserverModel::getByDocserverId(['docserverId' => $adrInfo['docserver_id']]);
                            if (empty($docserverInfo['path_template'])) {
                                $additionalsInfos['noAttachment'][] = ['alt_identifier' => $noAttachmentsResource['alt_identifier'], 'res_id' => $resId, 'reason' => 'docserverDoesNotExists'];
                                break;
                            }
                            $filePath = $docserverInfo['path_template'] . str_replace('#', '/', $adrInfo['path']) . $adrInfo['filename'];
                            if (!is_file($filePath)) {
                                $additionalsInfos['noAttachment'][] = ['alt_identifier' => $noAttachmentsResource['alt_identifier'], 'res_id' => $resId, 'reason' => 'fileDoesNotExists'];
                                break;
                            }
                        }
                        if (!$hasSignableAttachment && empty($integratedResource)) {
                            $additionalsInfos['noAttachment'][] = ['alt_identifier' => $noAttachmentsResource['alt_identifier'], 'res_id' => $resId, 'reason' => 'noSignableAttachmentInSignatoryBook'];
                        } else {
                            $statuses = array_column($attachments, 'status');
                            $mailing = in_array('SEND_MASS', $statuses);

                            $additionalsInfos['attachments'][] = ['res_id' => $resId, 'alt_identifier' => $noAttachmentsResource['alt_identifier'], 'mailing' => $mailing];
                        }
                    }
                }
            } elseif ($signatureBookEnabled == 'xParaph') {
                $userInfos  = UserModel::getById(['id' => $GLOBALS['id'], 'select' => ['external_id']]);
                $externalId = json_decode($userInfos['external_id'], true);
                $additionalsInfos['accounts'] = [];
                if (!empty($externalId['xParaph'])) {
                    $additionalsInfos['accounts'] = $externalId['xParaph'];
                }

                foreach ($data['resources'] as $resId) {
                    $noAttachmentsResource = ResModel::getById(['resId' => $resId, 'select' => ['alt_identifier']]);
                    if (empty($noAttachmentsResource['alt_identifier'])) {
                        $noAttachmentsResource['alt_identifier'] = _UNDEFINED;
                    }

                    // Check attachments
                    $attachments = AttachmentModel::get([
                        'select'    => [
                            'res_id', 'title', 'identifier', 'attachment_type',
                            'status', 'typist', 'docserver_id', 'path', 'filename', 'creation_date',
                            'validation_date', 'relation', 'origin_id'
                        ],
                        'where'     => ["res_id_master = ?", "attachment_type not in (?)", "status not in ('DEL', 'OBS', 'FRZ', 'TMP')", "in_signature_book = 'true'"],
                        'data'      => [$resId, ['signed_response']]
                    ]);
                    
                    if (empty($attachments)) {
                        $additionalsInfos['noAttachment'][] = ['alt_identifier' => $noAttachmentsResource['alt_identifier'], 'res_id' => $resId, 'reason' => 'noAttachmentInSignatoryBook'];
                    } else {
                        foreach ($attachments as $value) {
                            $resIdAttachment = $value['res_id'];
                            $collId          = 'attachments_coll';
                            
                            $adrInfo = ConvertPdfController::getConvertedPdfById(['resId' => $resIdAttachment, 'collId' => $collId]);
                            if (empty($adrInfo['docserver_id'])) {
                                $additionalsInfos['noAttachment'][] = ['alt_identifier' => $noAttachmentsResource['alt_identifier'], 'res_id' => $resIdAttachment, 'reason' => 'noAttachmentConversion'];
                                break;
                            }
                            $docserverInfo = DocserverModel::getByDocserverId(['docserverId' => $adrInfo['docserver_id']]);
                            if (empty($docserverInfo['path_template'])) {
                                $additionalsInfos['noAttachment'][] = ['alt_identifier' => $noAttachmentsResource['alt_identifier'], 'res_id' => $resIdAttachment, 'reason' => 'docserverDoesNotExists'];
                                break;
                            }
                            $filePath = $docserverInfo['path_template'] . str_replace('#', '/', $adrInfo['path']) . $adrInfo['filename'];
                            if (!is_file($filePath)) {
                                $additionalsInfos['noAttachment'][] = ['alt_identifier' => $noAttachmentsResource['alt_identifier'], 'res_id' => $resIdAttachment, 'reason' => 'fileDoesNotExists'];
                                break;
                            }
                        }
                        $statuses = array_column($attachments, 'status');
                        $mailing = in_array('SEND_MASS', $statuses);

                        $additionalsInfos['attachments'][] = ['res_id' => $resId, 'alt_identifier' => $noAttachmentsResource['alt_identifier'], 'mailing' => $mailing];
                    }
                }
            }
        }

        return $response->withJson(['signatureBookEnabled' => $signatureBookEnabled, 'additionalsInfos' => $additionalsInfos, 'errors' => $errors]);
    }

    public function checkExternalNoteBook(Request $request, Response $response, array $aArgs)
    {
        $errors = ResourceListController::listControl(['groupId' => $aArgs['groupId'], 'userId' => $aArgs['userId'], 'basketId' => $aArgs['basketId'], 'currentUserId' => $GLOBALS['id']]);
        if (!empty($errors['errors'])) {
            return $response->withStatus($errors['code'])->withJson(['errors' => $errors['errors']]);
        }

        $data = $request->getParsedBody();

        $data['resources'] = array_slice($data['resources'], 0, 500);
        if (!ResController::hasRightByResId(['resId' => $data['resources'], 'userId' => $GLOBALS['id']])) {
            return $response->withStatus(403)->withJson(['errors' => 'Document out of perimeter']);
        }

        $data['resources'] = PreProcessActionController::getNonLockedResources(['resources' => $data['resources'], 'userId' => $GLOBALS['id']]);

        $loadedXml = CoreConfigModel::getXmlLoaded(['path' => 'modules/visa/xml/remoteSignatoryBooks.xml']);

        $errors = [];
        if (!empty($loadedXml)) {
            $config['id'] = 'maarchParapheur';
            foreach ($loadedXml->signatoryBook as $value) {
                if ($value->id == $config['id']) {
                    $config['data'] = (array)$value;
                    break;
                }
            }

            $additionalsInfos = [
                'mails'  => [],
                'noMail' => []
            ];

            $userList = MaarchParapheurController::getInitializeDatas(['config' => $config]);
            if (!empty($userList['users'])) {
                $additionalsInfos['users'] = $userList['users'];
            } else {
                $additionalsInfos['users'] = [];
            }
            if (!empty($userList['errors'])) {
                $errors[] = $userList['errors'];
            }

            foreach ($data['resources'] as $resId) {
                $noAttachmentsResource = ResModel::getById(['resId' => $resId, 'select' => ['alt_identifier', 'filename', 'external_id->>\'signatureBookId\' as signaturebookid']]);
                if (empty($noAttachmentsResource['alt_identifier'])) {
                    $noAttachmentsResource['alt_identifier'] = _UNDEFINED;
                }

                if (empty($noAttachmentsResource['filename'])) {
                    $additionalsInfos['noMail'][] = ['alt_identifier' => $noAttachmentsResource['alt_identifier'], 'res_id' => $resId, 'reason' => 'noDocumentToSend'];
                    continue;
                }
                $adrMainInfo = ConvertPdfController::getConvertedPdfById(['resId' => $resId, 'collId' => 'letterbox_coll']);
                if (empty($adrMainInfo['docserver_id'])) {
                    $additionalsInfos['noMail'][] = ['alt_identifier' => $noAttachmentsResource['alt_identifier'], 'res_id' => $resId, 'reason' => 'noMailConversion'];
                    continue;
                }
                $docserverMainInfo = DocserverModel::getByDocserverId(['docserverId' => $adrMainInfo['docserver_id']]);
                if (empty($docserverMainInfo['path_template'])) {
                    $additionalsInfos['noMail'][] = ['alt_identifier' => $noAttachmentsResource['alt_identifier'], 'res_id' => $resId, 'reason' => 'docserverDoesNotExists'];
                    continue;
                }
                $filePath = $docserverMainInfo['path_template'] . str_replace('#', '/', $adrMainInfo['path']) . $adrMainInfo['filename'];
                if (!is_file($filePath)) {
                    $additionalsInfos['noMail'][] = ['alt_identifier' => $noAttachmentsResource['alt_identifier'], 'res_id' => $resId, 'reason' => 'fileDoesNotExists'];
                    continue;
                }
                if (!empty($noAttachmentsResource['signaturebookid'])) {
                    $additionalsInfos['noMail'][] = ['alt_identifier' => $noAttachmentsResource['alt_identifier'], 'res_id' => $resId, 'reason' => 'fileAlreadySendToSignatureBook'];
                    continue;
                }
                $additionalsInfos['mails'][] = ['res_id' => $resId];
            }
        }

        return $response->withJson(['additionalsInfos' => $additionalsInfos, 'errors' => $errors]);
    }

    public function checkShippings(Request $request, Response $response)
    {
        $mailevaConfig = CoreConfigModel::getMailevaConfiguration();
        if (empty($mailevaConfig)) {
            return $response->withJson(['fatalError' => 'Maileva configuration does not exist', 'reason' => 'missingMailevaConfig']);
        } elseif (!$mailevaConfig['enabled']) {
            return $response->withJson(['fatalError' => 'Maileva configuration is disabled', 'reason' => 'disabledMailevaConfig']);
        }

        $data = $request->getParsedBody();

        if (!Validator::arrayType()->notEmpty()->validate($data['resources'])) {
            return $response->withStatus(400)->withJson(['errors' => 'Body resources is empty or not an array']);
        }

        $data['resources'] = array_slice($data['resources'], 0, 500);
        if (!ResController::hasRightByResId(['resId' => $data['resources'], 'userId' => $GLOBALS['id']])) {
            return $response->withStatus(403)->withJson(['errors' => 'Document out of perimeter']);
        }

        $data['resources'] = PreProcessActionController::getNonLockedResources(['resources' => $data['resources'], 'userId' => $GLOBALS['id']]);

        $aDestination = ResModel::get([
            'select' => ['distinct(destination)'],
            'where'  => ['res_id in (?)'],
            'data'   => [$data['resources']]
        ]);

        $aTemplates = [];
        $entities   = [];
        $resources  = [];
        $canNotSend = [];

        foreach ($aDestination as $values) {
            if (!empty($values['destination'])) {
                $entities[] = $values['destination'];
            }
        }

        $entitiesInfos = [];
        if (!empty($entities)) {
            $aEntities = EntityModel::get(['select' => ['id', 'entity_label'], 'where' => ['entity_id in (?)'], 'data' => [$entities]]);

            $entitiesId = [];
            foreach ($aEntities as $value) {
                $entitiesId[] = (string)$value['id'];
                $entitiesInfos[] = $value['entity_label'];
            }

            $aTemplates = ShippingTemplateModel::getByEntities([
                'select'   => ['id', 'label', 'description', 'options', 'fee'],
                'entities' => $entitiesId
            ]);
    
            foreach ($aTemplates as $key => $value) {
                $aTemplates[$key]['options']  = json_decode($value['options'], true);
                $aTemplates[$key]['fee']      = json_decode($value['fee'], true);
            }
        }

        if (!empty($aTemplates)) {
            $aAttachments = AttachmentModel::get([
                'select'    => ['max(relation) as relation', 'res_id_master', 'title', 'res_id', 'identifier as chrono', 'recipient_id', 'recipient_type'],
                'where'     => ['res_id_master in (?)', 'status not in (?)', 'in_send_attach = ?'],
                'data'      => [$data['resources'], ['OBS', 'DEL', 'TMP', 'FRZ'], true],
                'groupBy'   => ['res_id_master', 'title', 'res_id', 'chrono', 'recipient_id', 'recipient_type']
            ]);

            $resourcesInfo   = ResModel::get(['select' => ['alt_identifier', 'res_id'], 'where' => ['res_id in (?)'], 'data' => [$data['resources']]]);
            $resourcesChrono = array_column($resourcesInfo, 'alt_identifier', 'res_id');

            foreach ($data['resources'] as $valueResId) {
                $documentToSend = false;
                foreach ($aAttachments as $key => $attachment) {
                    if ($attachment['res_id_master'] == $valueResId) {
                        $documentToSend = true;
                        $attachmentId = $attachment['res_id'];
                        $convertedDocument = ConvertPdfController::getConvertedPdfById([
                            'resId'     => $attachmentId,
                            'collId'    => 'attachments_coll'
                        ]);
                        if (empty($convertedDocument['docserver_id']) || strtolower(pathinfo($convertedDocument['filename'], PATHINFO_EXTENSION)) != 'pdf') {
                            $canNotSend[] = ['resId' => $valueResId, 'chrono' => $resourcesChrono[$valueResId], 'reason' => 'noAttachmentConversion', 'attachmentIdentifier' => $attachment['chrono']];
                            unset($aAttachments[$key]);
                            break;
                        }
                        $docserver = DocserverModel::getByDocserverId(['docserverId' => $convertedDocument['docserver_id'], 'select' => ['path_template', 'docserver_type_id']]);
                        $pathToDocument = $docserver['path_template'] . str_replace('#', DIRECTORY_SEPARATOR, $convertedDocument['path']) . $convertedDocument['filename'];
                        if (!is_file($pathToDocument)) {
                            $canNotSend[] = ['resId' => $valueResId, 'chrono' => $resourcesChrono[$valueResId], 'reason' => 'noAttachmentConversion', 'attachmentIdentifier' => $attachment['chrono']];
                            unset($aAttachments[$key]);
                            break;
                        }
                        $docserverType = DocserverTypeModel::getById(['id' => $docserver['docserver_type_id'], 'select' => ['fingerprint_mode']]);
                        $fingerprint = StoreController::getFingerPrint(['filePath' => $pathToDocument, 'mode' => $docserverType['fingerprint_mode']]);
                        if ($convertedDocument['fingerprint'] != $fingerprint) {
                            $canNotSend[] = ['resId' => $valueResId, 'chrono' => $resourcesChrono[$valueResId], 'reason' => 'fingerprintsDoNotMatch', 'attachmentIdentifier' => $attachment['chrono']];
                            unset($aAttachments[$key]);
                            break;
                        }
                        if (empty($attachment['recipient_id']) || $attachment['recipient_type'] != 'contact') {
                            $canNotSend[] = ['resId' => $valueResId, 'chrono' => $resourcesChrono[$valueResId], 'reason' => 'noAttachmentContact', 'attachmentIdentifier' => $attachment['chrono']];
                            unset($aAttachments[$key]);
                            break;
                        }
                        $contact = ContactModel::getById(['select' => ['*'], 'id' => $attachment['recipient_id']]);
                        if (empty($contact)) {
                            $canNotSend[] = ['resId' => $valueResId, 'chrono' => $resourcesChrono[$valueResId], 'reason' => 'noAttachmentContact', 'attachmentIdentifier' => $attachment['chrono']];
                            unset($aAttachments[$key]);
                            break;
                        }
                        if (!empty($contact['address_country']) && strtoupper(trim($contact['address_country'])) != 'FRANCE') {
                            $canNotSend[] = ['resId' => $valueResId, 'chrono' => $resourcesChrono[$valueResId], 'reason' => 'noFranceContact', 'attachmentIdentifier' => $attachment['chrono']];
                            unset($aAttachments[$key]);
                            break;
                        }
                        $afnorAddress = ContactController::getContactAfnor($contact);
                        if ((empty($afnorAddress[1]) && empty($afnorAddress[2])) || empty($afnorAddress[6]) || !preg_match("/^\d{5}\s/", $afnorAddress[6])) {
                            $canNotSend[] = ['resId' => $valueResId, 'chrono' => $resourcesChrono[$valueResId], 'reason' => 'incompleteAddressForPostal', 'attachmentIdentifier' => $attachment['chrono']];
                            unset($aAttachments[$key]);
                            break;
                        }

                        $attachment['type'] = 'attachment';
                        $resources[] = $attachment;
                        unset($aAttachments[$key]);
                    }
                }

                $resInfo = ResModel::getById(['select' => ['alt_identifier as chrono', 'integrations', 'res_id', 'subject as title', 'docserver_id'], 'resId' => $valueResId]);
                $integrations = json_decode($resInfo['integrations'], true);
                if (!empty($integrations['inShipping']) && empty($resInfo['docserver_id'])) {
                    $documentToSend = true;
                    $canNotSend[] = [
                        'resId'  => $valueResId, 'chrono' => $resInfo['chrono'], 'reason' => 'noMailConversion'
                    ];
                } elseif (!empty($integrations['inShipping']) && !empty($resInfo['docserver_id'])) {
                    $documentToSend = true;

                    $convertedDocument = ConvertPdfController::getConvertedPdfById([
                        'resId'  => $valueResId,
                        'collId' => 'letterbox_coll'
                    ]);
                    if (empty($convertedDocument['docserver_id']) || strtolower(pathinfo($convertedDocument['filename'], PATHINFO_EXTENSION)) != 'pdf') {
                        $canNotSend[] = [
                            'resId'  => $valueResId, 'chrono' => $resInfo['chrono'], 'reason' => 'noMailConversion'
                        ];
                    } else {
                        $docserver = DocserverModel::getByDocserverId(['docserverId' => $convertedDocument['docserver_id'], 'select' => ['path_template', 'docserver_type_id']]);
                        $pathToDocument = $docserver['path_template'] . str_replace('#', DIRECTORY_SEPARATOR, $convertedDocument['path']) . $convertedDocument['filename'];
                        if (!is_file($pathToDocument)) {
                            $canNotSend[] = [
                                'resId'  => $valueResId, 'chrono' => $resInfo['chrono'], 'reason' => 'noMailConversion'
                            ];
                        } else {
                            $docserverType = DocserverTypeModel::getById(['id' => $docserver['docserver_type_id'], 'select' => ['fingerprint_mode']]);
                            $fingerprint = StoreController::getFingerPrint(['filePath' => $pathToDocument, 'mode' => $docserverType['fingerprint_mode']]);
                            if ($convertedDocument['fingerprint'] != $fingerprint) {
                                $canNotSend[] = [
                                    'resId'  => $valueResId, 'chrono' => $resInfo['chrono'], 'reason' => 'fingerprintsDoNotMatch'
                                ];
                            } else {
                                $resourceContacts = ResourceContactModel::get([
                                    'where' => ['res_id = ?', 'mode = ?', 'type = ?'],
                                    'data'  => [$valueResId, 'recipient', 'contact']
                                ]);
                                if (empty($resourceContacts)) {
                                    $canNotSend[] = [
                                        'resId'  => $valueResId, 'chrono' => $resInfo['chrono'], 'reason' => 'noMailContact'
                                    ];
                                } else {
                                    foreach ($resourceContacts as $resourceContact) {
                                        $contact = ContactModel::getById(['select' => ['*'], 'id' => $resourceContact['item_id']]);
                                        if (empty($contact)) {
                                            $canNotSend[] = [
                                                'resId'  => $valueResId, 'chrono' => $resInfo['chrono'], 'reason' => 'noMailContact'
                                            ];
                                        } elseif (!empty($contact['address_country']) && strtoupper(trim($contact['address_country'])) != 'FRANCE') {
                                            $canNotSend[] = [
                                                'resId'  => $valueResId, 'chrono' => $resInfo['chrono'], 'reason' => 'noFranceContact'
                                            ];
                                        } else {
                                            $afnorAddress = ContactController::getContactAfnor($contact);
                                            if ((empty($afnorAddress[1]) && empty($afnorAddress[2])) || empty($afnorAddress[6]) || !preg_match("/^\d{5}\s/", $afnorAddress[6])) {
                                                $canNotSend[] = [
                                                    'resId'  => $valueResId, 'chrono' => $resInfo['chrono'], 'reason' => 'incompleteAddressForPostal'
                                                ];
                                            }
                                        }
                                    }
                                }
                                $resInfo['type'] = 'mail';
                                $resources[] = $resInfo;
                            }
                        }
                    }
                }
                if (!$documentToSend) {
                    $canNotSend[] = ['resId' => $valueResId, 'chrono' => $resInfo['chrono'], 'reason' => 'noDocumentToSend'];
                }
            }
    
            foreach ($aTemplates as $key => $value) {
                if (!empty($resources)) {
                    $templateFee = ShippingTemplateController::calculShippingFee([
                        'fee'       => $value['fee'],
                        'resources' => $resources
                    ]);
                } else {
                    $templateFee = 0;
                }
                $aTemplates[$key]['fee'] = number_format($templateFee, 2, '.', '');
            }
        }

        return $response->withJson([
            'shippingTemplates' => $aTemplates,
            'entities'          => $entitiesInfos,
            'resources'         => $resources,
            'canNotSend'        => $canNotSend
        ]);
    }

    public function checkInitiatorEntity(Request $request, Response $response, array $args)
    {
        $errors = ResourceListController::listControl(['groupId' => $args['groupId'], 'userId' => $args['userId'], 'basketId' => $args['basketId'], 'currentUserId' => $GLOBALS['id']]);
        if (!empty($errors['errors'])) {
            return $response->withStatus($errors['code'])->withJson(['errors' => $errors['errors']]);
        }

        $data = $request->getParsedBody();

        if (!Validator::arrayType()->notEmpty()->validate($data['resources'])) {
            return $response->withStatus(400)->withJson(['errors' => 'Data resources is empty or not an array']);
        }
        $data['resources'] = PreProcessActionController::getNonLockedResources(['resources' => $data['resources'], 'userId' => $GLOBALS['id']]);

        $withEntity = [];
        $withoutEntity = [];

        $resources = ResModel::get([
            'select' => ['initiator', 'res_id', 'alt_identifier'],
            'where'  => ['res_id in (?)'],
            'data'   => [$data['resources']]
        ]);

        $resourcesInfoInitiator = array_column($resources, 'initiator', 'res_id');
        $resourcesInfoChrono = array_column($resources, 'alt_identifier', 'res_id');

        foreach ($data['resources'] as $valueResId) {
            if (!empty($resourcesInfoInitiator[$valueResId])) {
                $withEntity[] = $valueResId;
            } else {
                $withoutEntity[] = $resourcesInfoChrono[$valueResId] ?? _UNDEFINED;
            }
        }

        return $response->withJson(['withEntity' => $withEntity, 'withoutEntity' => $withoutEntity]);
    }

    public function checkAttachmentsAndNotes(Request $request, Response $response, array $args)
    {
        $errors = ResourceListController::listControl(['groupId' => $args['groupId'], 'userId' => $args['userId'], 'basketId' => $args['basketId'], 'currentUserId' => $GLOBALS['id']]);
        if (!empty($errors['errors'])) {
            return $response->withStatus($errors['code'])->withJson(['errors' => $errors['errors']]);
        }

        $data = $request->getParsedBody();

        if (!Validator::arrayType()->notEmpty()->validate($data['resources'])) {
            return $response->withStatus(400)->withJson(['errors' => 'Data resources is empty or not an array']);
        }
        $data['resources'] = PreProcessActionController::getNonLockedResources(['resources' => $data['resources'], 'userId' => $GLOBALS['id']]);

        $hasAttachmentsNotes = [];
        $noAttachmentsNotes = [];

        $attachments = AttachmentModel::get([
            'select' => ['count(1) as nb', 'res_id_master'],
            'where'  => ['res_id_master in (?)', 'status != ?'],
            'data'   => [$data['resources'], 'DEL'],
            'groupBy' => ['res_id_master']
        ]);

        $resources = ResModel::get([
            'select' => ['res_id', 'alt_identifier'],
            'where'  => ['res_id in (?)', 'status != ?'],
            'data'   => [$data['resources'], 'DEL']
        ]);

        $nbAttachments = array_column($attachments, 'nb', 'res_id_master');
        $resources = array_column($resources, 'alt_identifier', 'res_id');

        foreach ($data['resources'] as $resId) {
            $notes = NoteModel::getByUserIdForResource(['select' => ['user_id', 'id'], 'resId' => $resId, 'userId' => $GLOBALS['id']]);

            if (empty($notes) && empty($nbAttachments[$resId])) {
                $noAttachmentsNotes[] = $resources[$resId] ?? _UNDEFINED;
            } else {
                $hasAttachmentsNotes[] = $resId;
            }
        }

        return $response->withJson(['hasAttachmentsNotes' => $hasAttachmentsNotes, 'noAttachmentsNotes' => $noAttachmentsNotes]);
    }

    public function checkSignatureBook(Request $request, Response $response, array $args)
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

        $signableAttachmentsTypes = [];
        $attachmentsTypes = AttachmentModel::getAttachmentsTypesByXML();
        foreach ($attachmentsTypes as $key => $type) {
            if ($type['sign']) {
                $signableAttachmentsTypes[] = $key;
            }
        }

        $body['resources'] = PreProcessActionController::getNonLockedResources(['resources' => $body['resources'], 'userId' => $GLOBALS['id']]);

        $resourcesInformations = [];
        foreach ($body['resources'] as $resId) {
            $resource = ResModel::getById(['resId' => $resId, 'select' => ['alt_identifier', 'integrations']]);
            if (empty($resource['alt_identifier'])) {
                $resource['alt_identifier'] = _UNDEFINED;
            }

            $attachments = AttachmentModel::get([
                'select'    => ['status'],
                'where'     => ['res_id_master = ?', 'attachment_type in (?)', 'in_signature_book = ?', 'status not in (?)'],
                'data'      => [$resId, $signableAttachmentsTypes, true, ['OBS', 'DEL', 'FRZ']]
            ]);

            if (empty($attachments)) {
                $integrations = json_decode($resource['integrations'], true);
                if (!empty($integrations['inSignatureBook'])) {
                    $resourcesInformations['success'][] = ['res_id' => $resId, 'alt_identifier' => $resource['alt_identifier'], 'mailing' => false];
                } else {
                    $resourcesInformations['error'][] = ['alt_identifier' => $resource['alt_identifier'], 'res_id' => $resId, 'reason' => 'noAttachmentInSignatoryBook'];
                }
            } else {
                $statuses = array_column($attachments, 'status');
                $mailing = in_array('SEND_MASS', $statuses);
                $resourcesInformations['success'][] = ['res_id' => $resId, 'alt_identifier' => $resource['alt_identifier'], 'mailing' => $mailing];
            }
        }

        $minimumVisaRole = ParameterModel::getById(['select' => ['param_value_int'], 'id' => 'minimumVisaRole']);
        $maximumSignRole = ParameterModel::getById(['select' => ['param_value_int'], 'id' => 'maximumSignRole']);

        $minimumVisaRole = !empty($minimumVisaRole['param_value_int']) ? $minimumVisaRole['param_value_int'] : 0;
        $maximumSignRole = !empty($maximumSignRole['param_value_int']) ? $maximumSignRole['param_value_int'] : 0;

        return $response->withJson(['resourcesInformations' => $resourcesInformations, 'minimumVisaRole' => $minimumVisaRole, 'maximumSignRole' => $maximumSignRole]);
    }

    public function checkContinueVisaCircuit(Request $request, Response $response, array $args)
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
        $body['resources'] = PreProcessActionController::getNonLockedResources(['resources' => $body['resources'], 'userId' => $GLOBALS['id']]);

        $signableAttachmentsTypes = [];
        $attachmentsTypes = AttachmentModel::getAttachmentsTypesByXML();
        foreach ($attachmentsTypes as $key => $type) {
            if ($type['sign']) {
                $signableAttachmentsTypes[] = $key;
            }
        }

        $resourcesInformations = [];
        foreach ($body['resources'] as $resId) {
            $resource = ResModel::getById(['resId' => $resId, 'select' => ['alt_identifier']]);
            if (empty($resource['alt_identifier'])) {
                $resource['alt_identifier'] = _UNDEFINED;
            }

            $isSignatory = ListInstanceModel::get(['select' => ['signatory', 'requested_signature'], 'where' => ['res_id = ?', 'difflist_type = ?', 'process_date is null'], 'data' => [$resId, 'VISA_CIRCUIT'], 'orderBy' => ['listinstance_id'], 'limit' => 1]);
            if (empty($isSignatory[0])) {
                $hasCircuit = ListInstanceModel::get(['select' => [1], 'where' => ['res_id = ?', 'difflist_type = ?'], 'data' => [$resId, 'VISA_CIRCUIT']]);
                if (!empty($hasCircuit)) {
                    $resourcesInformations['error'][] = ['alt_identifier' => $resource['alt_identifier'], 'res_id' => $resId, 'reason' => 'endedCircuit'];
                } else {
                    $resourcesInformations['error'][] = ['alt_identifier' => $resource['alt_identifier'], 'res_id' => $resId, 'reason' => 'noCircuitAvailable'];
                }
            } elseif ($isSignatory[0]['requested_signature'] && !$isSignatory[0]['signatory']) {
                $resourcesInformations['warning'][] = ['alt_identifier' => $resource['alt_identifier'], 'res_id' => $resId, 'reason' => 'userHasntSigned'];
            } else {
                $attachments = AttachmentModel::get([
                    'select'    => ['status'],
                    'where'     => ['res_id_master = ?', 'attachment_type in (?)', 'in_signature_book = ?', 'status not in (?)'],
                    'data'      => [$resId, $signableAttachmentsTypes, true, ['OBS', 'DEL', 'FRZ']]
                ]);

                $mailing = false;
                if (!empty($attachments)) {
                    $statuses = array_column($attachments, 'status');
                    $mailing = in_array('SEND_MASS', $statuses);
                }
                $resourcesInformations['success'][] = ['res_id' => $resId, 'alt_identifier' => $resource['alt_identifier'], 'mailing' => $mailing];
            }
        }

        return $response->withJson(['resourcesInformations' => $resourcesInformations]);
    }

    public function checkRejectVisa(Request $request, Response $response, array $args)
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
        $body['resources'] = PreProcessActionController::getNonLockedResources(['resources' => $body['resources'], 'userId' => $GLOBALS['id']]);

        $signableAttachmentsTypes = [];
        $attachmentsTypes = AttachmentModel::getAttachmentsTypesByXML();
        foreach ($attachmentsTypes as $key => $type) {
            if ($type['sign']) {
                $signableAttachmentsTypes[] = $key;
            }
        }

        $resourcesInformation = [];
        foreach ($body['resources'] as $resId) {
            $resource = ResModel::getById(['resId' => $resId, 'select' => ['alt_identifier']]);
            if (empty($resource['alt_identifier'])) {
                $resource['alt_identifier'] = _UNDEFINED;
            }

            $isSignatory = ListInstanceModel::get(['select' => ['signatory', 'requested_signature'], 'where' => ['res_id = ?', 'difflist_type = ?', 'process_date is null'], 'data' => [$resId, 'VISA_CIRCUIT'], 'orderBy' => ['listinstance_id'], 'limit' => 1]);
            if (empty($isSignatory[0])) {
                $hasCircuit = ListInstanceModel::get(['select' => [1], 'where' => ['res_id = ?', 'difflist_type = ?'], 'data' => [$resId, 'VISA_CIRCUIT']]);
                if (!empty($hasCircuit)) {
                    $resourcesInformation['error'][] = ['alt_identifier' => $resource['alt_identifier'], 'res_id' => $resId, 'reason' => 'endedCircuit'];
                } else {
                    $resourcesInformation['error'][] = ['alt_identifier' => $resource['alt_identifier'], 'res_id' => $resId, 'reason' => 'noCircuitAvailable'];
                }
            } else {
                $hasPrevious = ListInstanceModel::get([
                    'select'  => ['listinstance_id', 'item_id'],
                    'where'   => ['res_id = ?', 'difflist_type = ?', 'process_date is not null'],
                    'data'    => [$resId, 'VISA_CIRCUIT'],
                    'orderBy' => ['listinstance_id desc'],
                ]);
                if (empty($hasPrevious)) {
                    $resourcesInformation['error'][] = ['alt_identifier' => $resource['alt_identifier'], 'res_id' => $resId, 'reason' => 'circuitNotStarted'];
                } else {
                    $validFound = false;
                    foreach ($hasPrevious as $previous) {
                        $user = UserModel::getById(['id' => $previous['item_id'], 'select' => ['status']]);
                        if (!empty($user) && !in_array($user['status'], ['SPD', 'DEL'])) {
                            $validFound = true;
                            break;
                        }
                    }
                    if (!$validFound) {
                        $resourcesInformation['error'][] = ['alt_identifier' => $resource['alt_identifier'], 'res_id' => $resId, 'reason' => 'noPreviousValid'];
                    } else {
                        $resourcesInformation['success'][] = ['alt_identifier' => $resource['alt_identifier'], 'res_id' => $resId];
                    }
                }
            }
        }

        return $response->withJson(['resourcesInformations' => $resourcesInformation]);
    }

    public function checkInterruptResetVisa(Request $request, Response $response, array $args)
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
        $body['resources'] = PreProcessActionController::getNonLockedResources(['resources' => $body['resources'], 'userId' => $GLOBALS['id']]);

        $signableAttachmentsTypes = [];
        $attachmentsTypes = AttachmentModel::getAttachmentsTypesByXML();
        foreach ($attachmentsTypes as $key => $type) {
            if ($type['sign']) {
                $signableAttachmentsTypes[] = $key;
            }
        }

        $resourcesInformation = [];
        foreach ($body['resources'] as $resId) {
            $resource = ResModel::getById(['resId' => $resId, 'select' => ['alt_identifier']]);
            if (empty($resource['alt_identifier'])) {
                $resource['alt_identifier'] = _UNDEFINED;
            }

            $isSignatory = ListInstanceModel::get(['select' => ['signatory', 'requested_signature'], 'where' => ['res_id = ?', 'difflist_type = ?', 'process_date is null'], 'data' => [$resId, 'VISA_CIRCUIT'], 'orderBy' => ['listinstance_id'], 'limit' => 1]);
            if (empty($isSignatory[0])) {
                $hasCircuit = ListInstanceModel::get(['select' => [1], 'where' => ['res_id = ?', 'difflist_type = ?'], 'data' => [$resId, 'VISA_CIRCUIT']]);
                if (!empty($hasCircuit)) {
                    $resourcesInformation['error'][] = ['alt_identifier' => $resource['alt_identifier'], 'res_id' => $resId, 'reason' => 'endedCircuit'];
                } else {
                    $resourcesInformation['error'][] = ['alt_identifier' => $resource['alt_identifier'], 'res_id' => $resId, 'reason' => 'noCircuitAvailable'];
                }
            } else {
                $resourcesInformation['success'][] = ['alt_identifier' => $resource['alt_identifier'], 'res_id' => $resId];
            }
        }

        return $response->withJson(['resourcesInformations' => $resourcesInformation]);
    }

    public function checkValidateParallelOpinion(Request $request, Response $response, array $args)
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
        $body['resources'] = PreProcessActionController::getNonLockedResources(['resources' => $body['resources'], 'userId' => $GLOBALS['id']]);

        $resourcesInformation = [];

        $resources = ResModel::get(['select' => ['opinion_limit_date', 'alt_identifier', 'res_id'], 'where' => ['res_id in (?)'], 'data' => [$body['resources']]]);
        foreach ($resources as $resource) {
            if (empty($resource['opinion_limit_date'])) {
                $resourcesInformation['error'][] = ['alt_identifier' => $resource['alt_identifier'], 'res_id' => $resource['res_id'], 'reason' => 'noOpinionLimitDate'];
                continue;
            }
            $opinionLimitDate = new \DateTime($resource['opinion_limit_date']);
            $today = new \DateTime('today');
            if ($opinionLimitDate < $today) {
                $resourcesInformation['error'][] = ['alt_identifier' => $resource['alt_identifier'], 'res_id' => $resource['res_id'], 'reason' => 'opinionLimitDateOutdated'];
                continue;
            }

            $opinionNote = NoteModel::get([
                'select'    => ['note_text', 'user_id', 'creation_date'],
                'where'     => ['identifier in (?)', 'note_text like (?)'],
                'data'      => [$resource['res_id'], '['._TO_AVIS.']%'],
                'order_by'  => ['creation_date desc'],
                'limit'     => 1
            ]);

            if (empty($opinionNote)) {
                $resourcesInformation['error'][] = ['alt_identifier' => $resource['alt_identifier'], 'res_id' => $resource['res_id'], 'reason' => 'noOpinionNote'];
                continue;
            }

            $note = str_replace('['._TO_AVIS.'] ', '', $opinionNote[0]['note_text']);
            $userInfo = UserModel::getLabelledUserById(['id' => $opinionNote[0]['user_id']]);
            $resourcesInformation['success'][] = ['alt_identifier' => $resource['alt_identifier'], 'res_id' => $resource['res_id'], 'avisUserAsk' => $userInfo, 'note' => $note, 'opinionLimitDate' => $resource['opinion_limit_date']];
        }

        return $response->withJson(['resourcesInformations' => $resourcesInformation]);
    }

    public function checkGiveParallelOpinion(Request $request, Response $response, array $args)
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
        $body['resources'] = PreProcessActionController::getNonLockedResources(['resources' => $body['resources'], 'userId' => $GLOBALS['id']]);

        $resourcesInformation = [];
        foreach ($body['resources'] as $resId) {
            $resource = ResModel::getById(['resId' => $resId, 'select' => ['alt_identifier', 'opinion_limit_date']]);

            if (empty($resource['alt_identifier'])) {
                $resource['alt_identifier'] = _UNDEFINED;
            }

            if (empty($resource['opinion_limit_date'])) {
                $resourcesInformation['error'][] = ['alt_identifier' => $resource['alt_identifier'], 'res_id' => $resId, 'reason' => 'noOpinionLimitDate'];
                continue;
            }
            $opinionLimitDate = new \DateTime($resource['opinion_limit_date']);
            $today = new \DateTime('today');
            if ($opinionLimitDate < $today) {
                $resourcesInformation['error'][] = ['alt_identifier' => $resource['alt_identifier'], 'res_id' => $resId, 'reason' => 'opinionLimitDateOutdated'];
                continue;
            }

            $opinionNote = NoteModel::get([
                'select'    => ['user_id', 'note_text'],
                'where'     => ['identifier = ?', "note_text like ?"],
                'data'      => [$resId, '['._TO_AVIS.']%'],
                'order_by'  => ['creation_date desc'],
                'limit'     => 1
            ]);

            if (empty($opinionNote)) {
                $resourcesInformation['error'][] = ['alt_identifier' => $resource['alt_identifier'], 'res_id' => $resId, 'reason' => 'noOpinionNote'];
                continue;
            }

            $isInCircuit = ListInstanceModel::get([
                'select'  => [1],
                'where'   => ['res_id = ?', 'difflist_type = ?', 'process_date is null', 'item_id = ?', 'item_mode = ?'],
                'data'    => [$resId, 'entity_id', $GLOBALS['id'], 'avis']
            ]);
            if (empty($isInCircuit)) {
                $resourcesInformation['error'][] = ['alt_identifier' => $resource['alt_identifier'], 'res_id' => $resId, 'reason' => 'userNotInDiffusionList'];
            } else {
                $userInfo = UserModel::getLabelledUserById(['id' => $opinionNote[0]['user_id']]);
                $resourcesInformation['success'][] = ['alt_identifier' => $resource['alt_identifier'], 'res_id' => $resId, 'avisUserAsk' => $userInfo, 'note' => $opinionNote[0]['note_text'], 'opinionLimitDate' => $resource['opinion_limit_date']];
            }
        }

        return $response->withJson(['resourcesInformations' => $resourcesInformation]);
    }

    public function checkContinueOpinionCircuit(Request $request, Response $response, array $args)
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
        $body['resources'] = PreProcessActionController::getNonLockedResources(['resources' => $body['resources'], 'userId' => $GLOBALS['id']]);

        $resourcesInformation = [];
        foreach ($body['resources'] as $resId) {
            $resource = ResModel::getById(['resId' => $resId, 'select' => ['alt_identifier', 'opinion_limit_date']]);

            if (empty($resource['alt_identifier'])) {
                $resource['alt_identifier'] = _UNDEFINED;
            }

            if (empty($resource['opinion_limit_date'])) {
                $resourcesInformation['error'][] = ['alt_identifier' => $resource['alt_identifier'], 'res_id' => $resId, 'reason' => 'noOpinionLimitDate'];
                continue;
            }
            $opinionLimitDate = new \DateTime($resource['opinion_limit_date']);
            $today = new \DateTime('today');
            if ($opinionLimitDate < $today) {
                $resourcesInformation['error'][] = ['alt_identifier' => $resource['alt_identifier'], 'res_id' => $resId, 'reason' => 'opinionLimitDateOutdated'];
                continue;
            }

            $opinionNote = NoteModel::get([
                'select'    => ['note_text', 'user_id'],
                'where'     => ['identifier = ?', "note_text like '[" . _TO_AVIS . "]%'"],
                'data'      => [$resId]
            ]);

            if (empty($opinionNote)) {
                $resourcesInformation['error'][] = ['alt_identifier' => $resource['alt_identifier'], 'res_id' => $resId, 'reason' => 'noOpinionNote'];
                continue;
            }

            $isInCircuit = ListInstanceModel::get([
                'select'  => [1],
                'where'   => ['res_id = ?', 'difflist_type = ?', 'process_date is null'],
                'data'    => [$resId, 'AVIS_CIRCUIT'],
                'orderBy' => ['listinstance_id'],
                'limit'   => 1
            ]);
            if (empty($isInCircuit[0])) {
                $hasCircuit = ListInstanceModel::get(['select' => [1], 'where' => ['res_id = ?', 'difflist_type = ?'], 'data' => [$resId, 'AVIS_CIRCUIT']]);
                if (!empty($hasCircuit)) {
                    $resourcesInformation['error'][] = ['alt_identifier' => $resource['alt_identifier'], 'res_id' => $resId, 'reason' => 'endedCircuit'];
                } else {
                    $resourcesInformation['error'][] = ['alt_identifier' => $resource['alt_identifier'], 'res_id' => $resId, 'reason' => 'noCircuitAvailable'];
                }
            } else {
                $userInfo = UserModel::getLabelledUserById(['id' => $opinionNote[0]['user_id']]);
                $resourcesInformation['success'][] = ['alt_identifier' => $resource['alt_identifier'], 'res_id' => $resId, 'avisUserAsk' => $userInfo, 'note' => $opinionNote[0]['note_text'], 'opinionLimitDate' => $resource['opinion_limit_date']];
            }
        }

        return $response->withJson(['resourcesInformations' => $resourcesInformation]);
    }

    public function isDestinationChanging(Request $request, Response $response, array $args)
    {
        if (!ResController::hasRightByResId(['resId' => [$args['resId']], 'userId' => $GLOBALS['id']])) {
            return $response->withStatus(403)->withJson(['errors' => 'Document out of perimeter']);
        }

        $user = UserModel::getById(['id' => $args['userId'], 'select' => ['user_id']]);
        if (empty($user)) {
            return $response->withStatus(400)->withJson(['errors' => 'User does not exist']);
        }

        $changeDestination = true;
        $entities = UserEntityModel::get(['select' => ['entity_id'], 'where' => ['user_id = ?'], 'data' => [$user['user_id']]]);
        $resource = ResModel::getById(['select' => ['destination'], 'resId' => $args['resId']]);
        foreach ($entities as $entity) {
            if ($entity['entity_id'] == $resource['destination']) {
                $changeDestination = false;
            }
        }

        return $response->withJson(['isDestinationChanging' => $changeDestination]);
    }

    public static function checkCloseWithFieldsAction(Request $request, Response $response, array $args)
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
        $body['resources'] = PreProcessActionController::getNonLockedResources(['resources' => $body['resources'], 'userId' => $GLOBALS['id']]);

        $action = ActionModel::getById(['id' => $args['actionId'], 'select' => ['parameters']]);
        if (empty($action)) {
            return $response->withStatus(400)->withJson(['errors' => 'Action does not exist']);
        }
        $parameters = json_decode($action['parameters'], true);
        $actionRequiredFields = $parameters['requiredFields'] ?? [];

        $canClose = [];
        $emptyFields = [];

        foreach ($body['resources'] as $resId) {
            if (!empty($actionRequiredFields)) {
                $resource = ResModel::getById(['resId' => $resId, 'select' => ['model_id', 'custom_fields', 'alt_identifier']]);
                $model = $resource['model_id'];
                $resourceCustomFields = json_decode($resource['custom_fields'], true);
                $modelFields = IndexingModelFieldModel::get([
                    'select' => ['identifier'],
                    'where'  => ['model_id = ?', "identifier LIKE 'indexingCustomField_%'"],
                    'data'   => [$model]
                ]);
                $modelFields = array_column($modelFields, 'identifier');

                $emptyList = [];

                foreach ($actionRequiredFields as $actionRequiredField) {
                    $idCustom = explode("_", $actionRequiredField)[1];
                    if (in_array($actionRequiredField, $modelFields) && empty($resourceCustomFields[$idCustom])) {
                        $emptyList[] = $idCustom;
                    }
                }

                if (!empty($emptyList)) {
                    $fieldsList = [];

                    $customs = CustomFieldModel::get([
                        'select' => ['label'],
                        'where'  => ['id in (?)'],
                        'data'   => [$emptyList]
                    ]);

                    foreach ($customs as $custom) {
                        $fieldsList[] = $custom['label'];
                    }

                    $emptyFields[] = [
                        'chrono' => $resource['alt_identifier'],
                        'fields' => !empty($fieldsList) ? implode(", ", $fieldsList) : ''
                    ];
                } else {
                    $canClose[] = $resId;
                }
            } else {
                $canClose[] = $resId;
            }
        }

        return $response->withJson(['errors' => $emptyFields, 'success' => $canClose]);
    }

    public function checkReconcile(Request $request, Response $response, array $args)
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
        $body['resources'] = PreProcessActionController::getNonLockedResources(['resources' => $body['resources'], 'userId' => $GLOBALS['id']]);

        $resourcesInformation = [];
        foreach ($body['resources'] as $resId) {
            $resource = ResModel::getById(['resId' => $resId, 'select' => ['alt_identifier', 'filename']]);

            if (empty($resource['alt_identifier'])) {
                $resource['alt_identifier'] = _UNDEFINED;
            }

            if (empty($resource['filename'])) {
                $resourcesInformation['error'][] = ['alt_identifier' => $resource['alt_identifier'], 'res_id' => $resId, 'reason' => 'noFile'];
                continue;
            }

            $resourcesInformation['success'][] = ['alt_identifier' => $resource['alt_identifier'], 'res_id' => $resId];
        }

        return $response->withJson(['resourcesInformations' => $resourcesInformation]);
    }

    public function checkSendAlfresco(Request $request, Response $response, array $args)
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
        $body['resources'] = PreProcessActionController::getNonLockedResources(['resources' => $body['resources'], 'userId' => $GLOBALS['id']]);

        $configuration = ConfigurationModel::getByPrivilege(['privilege' => 'admin_alfresco']);
        if (empty($configuration)) {
            return $response->withStatus(400)->withJson(['errors' => 'Alfresco configuration is not enabled']);
        }
        $configuration = json_decode($configuration['value'], true);
        if (empty($configuration['uri'])) {
            return $response->withStatus(400)->withJson(['errors' => 'Alfresco configuration URI is empty']);
        }

        $entity = UserModel::getPrimaryEntityById(['id' => $args['userId'], 'select' => ['entities.external_id']]);
        if (empty($entity)) {
            return $response->withStatus(400)->withJson(['errors' => 'User has no primary entity', 'lang' => 'userHasNoPrimaryEntity']);
        }
        $entityInformations = json_decode($entity['external_id'], true);
        if (empty($entityInformations['alfresco'])) {
            return $response->withStatus(400)->withJson(['errors' => 'User primary entity has not enough alfresco informations', 'lang' => 'notEnoughAlfrescoInformations']);
        }

        $resourcesInformations = [];
        foreach ($body['resources'] as $resId) {
            $resource = ResModel::getById(['select' => ['filename', 'alt_identifier', 'external_id'], 'resId' => $resId]);
            if (empty($resource['filename'])) {
                $resourcesInformations['error'][] = ['alt_identifier' => $resource['alt_identifier'], 'res_id' => $resId, 'reason' => 'noFile'];
                continue;
            }
            $externalId = json_decode($resource['external_id'], true);
            if (!empty($externalId['alfrescoId'])) {
                $resourcesInformations['error'][] = ['alt_identifier' => $resource['alt_identifier'], 'res_id' => $resId, 'reason' => 'alreadySentToAlfresco'];
                continue;
            }

            $resourcesInformations['success'][] = ['res_id' => $resId, 'alt_identifier' => $resource['alt_identifier']];
        }

        return $response->withJson(['resourcesInformations' => $resourcesInformations]);
    }

    public function checkPrintDepositList(Request $request, Response $response, array $args)
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
        $body['resources'] = PreProcessActionController::getNonLockedResources(['resources' => $body['resources'], 'userId' => $GLOBALS['id']]);

        $processedResources = [];
        $data = [
            '2D' => '',
            '2C' => '',
            'RW' => ''
        ];
        $cannotGenerate = [];
        $canGenerate = [];

        foreach ($body['resources'] as $resource) {
            if (in_array($resource, $processedResources)) {
                $canGenerate[] = $resource;
                continue;
            }

            $registeredMail = RegisteredMailModel::getWithResources([
                'select' => ['issuing_site', 'type', 'number', 'warranty', 'recipient', 'generated', 'departure_date', 'deposit_id', 'alt_identifier'],
                'where'  => ['res_letterbox.res_id = ?'],
                'data'   => [$resource]
            ]);
            if (empty($registeredMail[0])) {
                $mail = ResModel::getById(['resId' => $resource, 'select' => ['alt_identifier']]);
                if (empty($mail)) {
                    $cannotGenerate[] = _ID . ' ' . $resource . ' ' . _NOT_EXISTS;
                    continue;
                }
                $cannotGenerate[] = $mail['alt_identifier'] . ' - ' . _NOT_REGISTERED_MAIL;
                continue;
            }
            $registeredMail = $registeredMail[0];

            if (!$registeredMail['generated']) {
                $cannotGenerate[] = $registeredMail['alt_identifier'] . ' - ' . _NOT_GENERATED;
                continue;
            }

            if (empty($registeredMail['deposit_id'])) {
                $registeredMails = RegisteredMailModel::getWithResources([
                    'select' => ['alt_identifier', 'res_letterbox.res_id'],
                    'where'  => ['type = ?', 'issuing_site = ?', 'departure_date = ?', 'warranty = ?', 'generated = ?', 'deposit_id is null'],
                    'data'   => [$registeredMail['type'], $registeredMail['issuing_site'], $registeredMail['departure_date'], $registeredMail['warranty'], true]
                ]);
            } else {
                $registeredMails = RegisteredMailModel::getWithResources([
                    'select' => ['alt_identifier', 'res_letterbox.res_id'],
                    'where'  => ['deposit_id = ?'],
                    'data'   => [$registeredMail['deposit_id']]
                ]);
            }

            $resIds = array_column($registeredMails, 'res_id');

            $processedResources = array_merge($processedResources, $resIds);

            $registeredMailsNumbers = array_column($registeredMails, 'alt_identifier');

            $numbers = $data[$registeredMail['type']];
            if (!empty($numbers)) {
                $numbers = explode(', ', $numbers);
                $registeredMailsNumbers = array_merge($numbers, $registeredMailsNumbers);
            }

            $data[$registeredMail['type']] = implode(', ', $registeredMailsNumbers);
            $canGenerate[] = $resource;
        }

        return $response->withJson(['types' => $data, 'cannotGenerate' => $cannotGenerate, 'canGenerate' => $canGenerate]);
    }

    private static function getNonLockedResources(array $args)
    {
        ValidatorModel::notEmpty($args, ['resources', 'userId']);
        ValidatorModel::arrayType($args, ['resources']);
        ValidatorModel::intVal($args, ['userId']);

        $resources = ResModel::get([
            'select' => ['res_id', 'locker_user_id', 'locker_time'],
            'where'  => ['res_id in (?)'],
            'data'   => [$args['resources']]
        ]);

        $resourcesForProcess = [];
        foreach ($resources as $resource) {
            $lock = true;
            if (empty($resource['locker_user_id'] || empty($resource['locker_time']))) {
                $lock = false;
            } elseif ($resource['locker_user_id'] == $args['userId']) {
                $lock = false;
            } elseif (strtotime($resource['locker_time']) < time()) {
                $lock = false;
            }
            if (!$lock) {
                $resourcesForProcess[] = $resource['res_id'];
            }
        }

        return $resourcesForProcess;
    }
}
