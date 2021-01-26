<?php

require '../../vendor/autoload.php';

chdir('../..');

$customs =  scandir('custom');

foreach ($customs as $custom) {
    if (in_array($custom, ['custom.json', 'custom.xml', '.', '..'])) {
        continue;
    }

    $language = \SrcCore\models\CoreConfigModel::getLanguage();
    if (file_exists("custom/{$custom}/src/core/lang/lang-{$language}.php")) {
        require_once("custom/{$custom}/src/core/lang/lang-{$language}.php");
    }
    require_once("src/core/lang/lang-{$language}.php");

    \SrcCore\models\DatabasePDO::reset();
    new \SrcCore\models\DatabasePDO(['customId' => $custom]);

    $migrated = 0;

    $savedQueries = \SrcCore\models\DatabaseModel::select([
        'select'    => ['*'],
        'table'     => ['saved_queries']
    ]);

    $superadmin = \User\models\UserModel::getByLogin(['select' => ['id'], 'login' => 'superadmin']);
    if (empty($superadmin)) {
        $firstMan = \User\models\UserModel::get(['select' => ['id'], 'orderBy' => ['id'], 'limit' => 1]);
        $masterOwnerId = $firstMan[0]['id'];
    } else {
        $masterOwnerId = $superadmin['id'];
    }

    foreach ($savedQueries as $savedQuery) {
        $query = [];
        $savedQuery['query_txt'] = str_replace("'", '"', $savedQuery['query_txt']);
        $queryTxt = json_decode($savedQuery['query_txt'], true);

        if (!is_array($queryTxt)) {
            continue;
        }

        foreach ($queryTxt as $key => $value) {
            if ($key == 'subject') {
                $query[] = ['identifier' => 'subject', 'values' => $value['fields']['subject'][0]];
            } elseif ($key == 'chrono') {
                $query[] = ['identifier' => 'chrono', 'values' => $value['fields']['chrono'][0]];
            } elseif ($key == 'barcode') {
                $query[] = ['identifier' => 'barcode', 'values' => $value['fields']['barcode'][0]];
            } elseif ($key == 'sender' && !empty($value['fields']['sender_id'][0])) {
                $type = $value['fields']['sender_type'][0] == 'onlyContact' ? 'contact' : $value['fields']['sender_type'][0];
                $label = getContactLabel($type, $value['fields']['sender_id'][0]);
                if ($label == null) {
                    continue;
                }
                $query[] = ['identifier' => 'senders', 'values' => [['id' => $value['fields']['sender_id'][0], 'type' => $type, 'label' => $label]]];
            } elseif ($key == 'recipient' && !empty($value['fields']['recipient_id'][0])) {
                $type = $value['fields']['recipient_type'][0] == 'onlyContact' ? 'contact' : $value['fields']['recipient_type'][0];
                $label = getContactLabel($type, $value['fields']['recipient_id'][0]);
                if ($label == null) {
                    continue;
                }
                $query[] = ['identifier' => 'recipients', 'values' => [['id' => $value['fields']['recipient_id'][0], 'type' => $type, 'label' => $label]]];
            } elseif ($key == 'signatory_name' && !empty($value['fields']['signatory_name_id'][0])) {
                $user = \User\models\UserModel::getByLogin(['login' => $value['fields']['signatory_name_id'][0], 'select' => ['id', 'firstname', 'lastname']]);
                if (!empty($user)) {
                    $query[] = ['identifier' => 'role_sign', 'values' => [['id' => $user['id'], 'type' => 'user', 'label' => "{$user['firstname']} {$user['lastname']}"]]];
                }
            } elseif ($key == 'fulltext') {
                $query[] = ['identifier' => 'fulltext', 'values' => $value['fields']['fulltext'][0]];
            } elseif ($key == 'multifield') {
                $query[] = ['identifier' => 'searchTerm', 'values' => $value['fields']['multifield'][0]];
            } elseif ($key == 'destinataire') {
                $allUsers = [];
                if (!empty($value['fields']['destinataire_chosen']) && is_array($value['fields']['destinataire_chosen'])) {
                    foreach ($value['fields']['destinataire_chosen'] as $field) {
                        $user = \User\models\UserModel::getByLogin(['login' => $field, 'select' => ['id', 'firstname', 'lastname']]);
                        if (!empty($user)) {
                            $allUsers[] = ['id' => $user['id'], 'type' => 'user', 'label' => "{$user['firstname']} {$user['lastname']}"];
                        }
                    }
                }

                $query[] = ['identifier' => 'role_dest', 'values' => $allUsers];
            } elseif ($key == 'category') {
                $query[] = ['identifier' => 'category', 'values' => [['id' => $value['fields']['category'][0], 'label' => \Resource\models\ResModel::getCategoryLabel(['categoryId' => $value['fields']['category'][0]])]]];
            } elseif ($key == 'confidentiality') {
                $query[] = ['identifier' => 'confidentiality', 'values' => [['id' => $value['fields']['confidentiality'][0] == 'Y', 'label' => $value['fields']['confidentiality'][0] == 'Y' ? 'Oui' : 'Non']]];
            } elseif ($key == 'creation_date') {
                $query[] = ['identifier' => 'creationDate', 'values' => ['start' => getFormattedDate(['date' => $value['fields']['creation_date_from'][0]]), 'end' => getFormattedDate(['date' => $value['fields']['creation_date_to'][0]])]];
            } elseif ($key == 'doc_date') {
                $query[] = ['identifier' => 'documentDate', 'values' => ['start' => getFormattedDate(['date' => $value['fields']['doc_date_from'][0]]), 'end' => getFormattedDate(['date' => $value['fields']['doc_date_to'][0]])]];
            } elseif ($key == 'admission_date') {
                $query[] = ['identifier' => 'arrivalDate', 'values' => ['start' => getFormattedDate(['date' => $value['fields']['admission_date_from'][0]]), 'end' => getFormattedDate(['date' => $value['fields']['admission_date_to'][0]])]];
            } elseif ($key == 'exp_date') {
                $query[] = ['identifier' => 'departureDate', 'values' => ['start' => getFormattedDate(['date' => $value['fields']['exp_date_from'][0]]), 'end' => getFormattedDate(['date' => $value['fields']['exp_date_to'][0]])]];
            } elseif ($key == 'process_limit_date') {
                $query[] = ['identifier' => 'processLimitDate', 'values' => ['start' => getFormattedDate(['date' => $value['fields']['process_limit_date_from'][0]]), 'end' => getFormattedDate(['date' => $value['fields']['process_limit_date_to'][0]])]];
            } elseif ($key == 'destination_mu') {
                $allEntities = [];
                if (!empty($value['fields']['services_chosen']) && is_array($value['fields']['services_chosen'])) {
                    foreach ($value['fields']['services_chosen'] as $field) {
                        $entity = \Entity\models\EntityModel::getByEntityId(['entityId' => $field, 'select' => ['id', 'entity_label']]);
                        $allEntities[] = ['id' => $entity['id'], 'title' => $entity['entity_label'], 'label' => $entity['entity_label']];
                    }
                }
                $query[] = ['identifier' => 'destination', 'values' => $allEntities];
            } elseif ($key == 'initiator_mu') {
                $allEntities = [];
                if (!empty($value['fields']['initiatorServices_chosen']) && is_array($value['fields']['initiatorServices_chosen'])) {
                    foreach ($value['fields']['initiatorServices_chosen'] as $field) {
                        $entity = \Entity\models\EntityModel::getByEntityId(['entityId' => $field, 'select' => ['id', 'entity_label']]);
                        $allEntities[] = ['id' => $entity['id'], 'title' => $entity['entity_label'], 'label' => $entity['entity_label']];
                    }
                }
                $query[] = ['identifier' => 'initiator', 'values' => $allEntities];
            } elseif ($key == 'tag_mu') {
                $allTags = [];
                if (!empty($value['fields']['tags_chosen']) && is_array($value['fields']['tags_chosen'])) {
                    foreach ($value['fields']['tags_chosen'] as $field) {
                        $tag = \Tag\models\TagModel::getById(['id' => $field, 'select' => ['label', 'id']]);
                        if (!empty($tag)) {
                            $allTags[] = ['id' => $tag['id'], 'label' => $tag['label']];
                        }
                    }
                }
                $query[] = ['identifier' => 'tags', 'values' => $allTags];
            } elseif ($key == 'status') {
                $allStatuses = [];
                if (!empty($value['fields']['status_chosen']) && is_array($value['fields']['status_chosen'])) {
                    foreach ($value['fields']['status_chosen'] as $field) {
                        $status = \Status\models\StatusModel::getById(['select' => ['identifier', 'label_status'], 'id' => $field]);
                        if (!empty($status)) {
                            $allStatuses[] = ['id' => $status['identifier'], 'label' => $status['label_status']];
                        }
                    }
                }

                $query[] = ['identifier' => 'status', 'values' => $allStatuses];
            } elseif ($key == 'visa_user' && !empty($value['fields']['user_visa'][0])) {
                $user = \User\models\UserModel::getByLogin(['login' => $value['fields']['user_visa'][0], 'select' => ['id', 'firstname', 'lastname']]);
                if (!empty($user)) {
                    $query[] = ['identifier' => 'role_visa', 'values' => [['id' => $user['id'], 'type' => 'user', 'label' => "{$user['firstname']} {$user['lastname']}"]]];
                }
            } elseif ($key == 'numged') {
                $query[] = ['identifier' => 'resId', 'values' => ['start' => $value['fields']['numged'][0], 'end' => $value['fields']['numged'][0]]];
            } elseif ($key == 'doc_notes') {
                $query[] = ['identifier' => 'notes', 'values' => $value['fields']['doc_notes'][0]];
            } elseif ($key == 'signatory_group' && !empty($value['fields']['signatory_group'][0])) {
                $group = \Group\models\GroupModel::getByGroupId(['groupId' => $value['fields']['signatory_group'][0], 'select' => ['id', 'group_desc']]);
                if (!empty($group)) {
                    $query[] = ['identifier' => 'groupSign', 'values' => ['id' => $group['id'], 'label' => $group['group_desc'],'group' => '']];
                }
            } elseif ($key == 'closing_date') {
                $query[] = ['identifier' => 'closingDate', 'values' => ['start' => getFormattedDate(['date' => $value['fields']['closing_date_from'][0]]), 'end' => getFormattedDate(['date' => $value['fields']['closing_date_to'][0]])]];
            } elseif ($key == 'creation_date_pj') {
                $query[] = ['identifier' => 'attachment_creationDate', 'values' => ['start' => getFormattedDate(['date' => $value['fields']['creation_date_pj_from'][0]]), 'end' => getFormattedDate(['date' => $value['fields']['creation_date_pj_to'][0]])]];
            } elseif ($key == 'attachment_types') {
                $types = \Attachment\models\AttachmentModel::getAttachmentsTypesByXML();
                if (in_array($value['fields']['attachment_types'][0], array_keys($types))) {
                    $query[] = ['identifier' => 'attachment_type', 'values' => ['id' => $value['fields']['attachment_types'][0], 'label' => $types[$value['fields']['attachment_types'][0]],'group' => '']];
                }
            } elseif ($key == 'doctype') {
                $types = [];
                if (!empty($value['fields']['doctypes_chosen']) && is_array($value['fields']['doctypes_chosen'])) {
                    foreach ($value['fields']['doctypes_chosen'] as $docType) {
                        $type = \Doctype\models\DoctypeModel::getById(['id' => (int)$docType]);
                        if (!empty($type)) {
                            $types[] = ['id' => 101, 'label' => $type['description'], 'title' => $type['description'], 'disabled' => false, 'isTitle' => false, 'group' => ''];
                        }
                    }
                }

                $query[] = ['identifier' => 'doctype', 'values' => $types];
            } elseif ($key == 'department_number_mu') {
                $departments = [];
                if (!empty($value['fields']['department_number_chosen']) && is_array($value['fields']['department_number_chosen'])) {
                    foreach ($value['fields']['department_number_chosen'] as $department) {
                        $label = \Resource\controllers\DepartmentController::FRENCH_DEPARTMENTS[$department];
                        $departments[] = ['id' => $department, 'label' => "{$department} - {$label}"];
                    }
                }
                $query[] = ['identifier' => 'senderDepartment', 'values' => $departments];
            }
        }

        if (!empty($query)) {
            $user = \User\models\UserModel::getByLogin(['login' => $savedQuery['user_id'], 'select' => ['id']]);

            if (!empty($user['id'])) {
                \Search\models\SearchTemplateModel::create([
                    'user_id'       => $user['id'],
                    'label'         => $savedQuery['query_name'],
                    'query'         => json_encode($query)
                ]);

                $migrated++;
            }
        }
    }

    printf("Migration recherches sauvegardées (CUSTOM {$custom}) : {$migrated} sauvegarde(s) trouvée(s) et migrée(s).\n");
}

function getFormattedDate(array $args)
{
    \SrcCore\models\ValidatorModel::stringType($args, ['date']);

    if (empty($args['date'])) {
        return null;
    }
    $date = new \DateTime($args['date']);
    $date->setTime(00, 00, 00);

    return $date->format('Y-m-d');
}

function getContactLabel(string $type, int $id) {
    if ($type == 'contact') {
        $contact = \Contact\models\ContactModel::getById(['id' => $id, 'select' => ['firstname', 'lastname', 'company']]);
        if (empty($contact)) {
            return null;
        }
        $formattedContact = \Contact\controllers\ContactController::getFormattedOnlyContact([
            'contact' => [
                'firstname' => $contact['firstname'],
                'lastname'  => $contact['lastname'],
                'company'   => $contact['company']
            ]
        ]);
        return $formattedContact['idToDisplay'];
    } elseif ($type == 'user') {
        $user = \User\models\UserModel::getById(['id' => $id, 'select' => ['firstname', 'lastname']]);
        if (empty($user)) {
            return null;
        }
        return $user['firstname'] . ' ' . $user['lastname'];
    } elseif ($type == 'entity') {
        $entity = \Entity\models\EntityModel::getById(['id' => $id, 'select' => ['entity_label']]);
        if (empty($entity)) {
            return null;
        }
        return $entity['entity_label'];
    }
    return null;
}
