<?php

/**
* Copyright Maarch since 2008 under licence GPLv3.
* See LICENCE.txt file at the root folder for more details.
* This file is part of Maarch software.
*
*/

/**
* @brief Auto Complete Controller
* @author dev@maarch.org
*/

namespace SrcCore\controllers;

use Contact\controllers\ContactController;
use Contact\models\ContactGroupModel;
use Contact\models\ContactModel;
use Contact\models\ContactParameterModel;
use Entity\models\EntityModel;
use Respect\Validation\Validator;
use Slim\Http\Request;
use Slim\Http\Response;
use SrcCore\models\CoreConfigModel;
use SrcCore\models\CurlModel;
use SrcCore\models\DatabaseModel;
use SrcCore\models\TextFormatModel;
use SrcCore\models\ValidatorModel;
use Status\models\StatusModel;
use Tag\models\TagModel;
use User\controllers\UserController;
use User\models\UserModel;
use Folder\models\FolderModel;
use Folder\controllers\FolderController;
use MessageExchange\controllers\AnnuaryController;

class AutoCompleteController
{
    const LIMIT = 50;
    const TINY_LIMIT = 10;

    public static function getUsers(Request $request, Response $response)
    {
        $queryParams = $request->getQueryParams();
        $check = Validator::stringType()->notEmpty()->validate($queryParams['search']);
        if (!$check) {
            return $response->withStatus(400)->withJson(['errors' => 'Bad Request']);
        }

        $fields = ['firstname', 'lastname'];
        $fields = AutoCompleteController::getInsensitiveFieldsForRequest(['fields' => $fields]);

        $requestData = AutoCompleteController::getDataForRequest([
            'search'        => $queryParams['search'],
            'fields'        => $fields,
            'where'         => ['status not in (?)', 'mode not in (?)'],
            'data'          => [['DEL', 'SPD'], ['root_invisible', 'rest']],
            'fieldsNumber'  => 2,
        ]);

        $users = UserModel::get([
            'select'    => ['id', 'user_id', 'firstname', 'lastname'],
            'where'     => $requestData['where'],
            'data'      => $requestData['data'],
            'orderBy'   => ['lastname'],
            'limit'     => self::LIMIT
        ]);

        $data = [];
        foreach ($users as $value) {
            $primaryEntity = UserModel::getPrimaryEntityById(['id' => $value['id'], 'select' => ['entities.entity_label']]);
            $data[] = [
                'type'                  => 'user',
                'id'                    => empty($queryParams['serial']) ? $value['user_id'] : $value['id'],
                'serialId'              => $value['id'],
                'idToDisplay'           => "{$value['firstname']} {$value['lastname']}",
                'descriptionToDisplay'  => empty($primaryEntity) ? '' : $primaryEntity['entity_label'],
                'otherInfo'             => ''
            ];
        }

        return $response->withJson($data);
    }

    public static function getMaarchParapheurUsers(Request $request, Response $response)
    {
        $data = $request->getQueryParams();
        $check = Validator::stringType()->notEmpty()->validate($data['search']);
        if (!$check) {
            return $response->withStatus(400)->withJson(['errors' => 'search is empty']);
        }

        if (!empty($data['exludeAlreadyConnected'])) {
            $usersAlreadyConnected = UserModel::get([
                'select' => ['external_id->>\'maarchParapheur\' as external_id'],
                'where' => ['external_id->>\'maarchParapheur\' is not null']
            ]);
            $excludedUsers = array_column($usersAlreadyConnected, 'external_id');
        }

        $loadedXml = CoreConfigModel::getXmlLoaded(['path' => 'modules/visa/xml/remoteSignatoryBooks.xml']);

        if ($loadedXml->signatoryBookEnabled == 'maarchParapheur') {
            foreach ($loadedXml->signatoryBook as $value) {
                if ($value->id == "maarchParapheur") {
                    $url      = $value->url;
                    $userId   = $value->userId;
                    $password = $value->password;
                    break;
                }
            }

            $curlResponse = CurlModel::execSimple([
                'url'           => rtrim($url, '/') . '/rest/autocomplete/users?search='.urlencode($data['search']),
                'basicAuth'     => ['user' => $userId, 'password' => $password],
                'headers'       => ['content-type:application/json'],
                'method'        => 'GET'
            ]);

            if ($curlResponse['code'] != '200') {
                if (!empty($curlResponse['response']['errors'])) {
                    $errors =  $curlResponse['response']['errors'];
                } else {
                    $errors =  $curlResponse['errors'];
                }
                if (empty($errors)) {
                    $errors = 'An error occured. Please check your configuration file.';
                }
                return $response->withStatus(400)->withJson(['errors' => $errors]);
            }

            foreach ($curlResponse['response'] as $key => $value) {
                if (!empty($data['exludeAlreadyConnected']) && in_array($value['id'], $excludedUsers)) {
                    unset($curlResponse['response'][$key]);
                    continue;
                }
                $curlResponse['response'][$key]['idToDisplay'] = $value['firstname'] . ' ' . $value['lastname'];
                $curlResponse['response'][$key]['externalId']['maarchParapheur'] = $value['id'];
            }
            return $response->withJson($curlResponse['response']);
        } else {
            return $response->withStatus(403)->withJson(['errors' => 'maarchParapheur is not enabled']);
        }
    }

    public static function getCorrespondents(Request $request, Response $response)
    {
        $queryParams = $request->getQueryParams();

        if (!Validator::stringType()->notEmpty()->validate($queryParams['search'])) {
            return $response->withStatus(400)->withJson(['errors' => 'Query params search is empty']);
        }

        $searchOnEmails = !empty($queryParams['searchEmails']);

        //Contacts
        $autocompleteContacts = [];
        if (empty($queryParams['noContacts'])) {
            $searchableParameters = ContactParameterModel::get(['select' => ['identifier'], 'where' => ['searchable = ?'], 'data' => [true]]);

            $fields = [];
            foreach ($searchableParameters as $searchableParameter) {
                if (strpos($searchableParameter['identifier'], 'contactCustomField_') !== false) {
                    $customFieldId = explode('_', $searchableParameter['identifier'])[1];
                    $fields[] = "custom_fields->>'{$customFieldId}'";
                } else {
                    $fields[] = ContactController::MAPPING_FIELDS[$searchableParameter['identifier']];
                }
            }

            if ($searchOnEmails && !in_array('email', $fields)) {
                $fields[] = 'email';
            }

            $fieldsNumber = count($fields);
            $fields = AutoCompleteController::getInsensitiveFieldsForRequest(['fields' => $fields]);

            $requestData = AutoCompleteController::getDataForRequest([
                'search'        => $queryParams['search'],
                'fields'        => $fields,
                'where'         => ['enabled = ?'],
                'data'          => [true],
                'fieldsNumber'  => $fieldsNumber
            ]);

            $contacts = ContactModel::get([
                'select'    => ['id', 'email'],
                'where'     => $requestData['where'],
                'data'      => $requestData['data'],
                'orderBy'   => ['company', 'lastname NULLS FIRST'],
                'limit'     => self::TINY_LIMIT
            ]);

            foreach ($contacts as $contact) {
                $autoContact = ContactController::getAutocompleteFormat(['id' => $contact['id']]);

                if ($searchOnEmails && empty($autoContact['email'])) {
                    $autoContact['email'] = $contact['email'];
                }

                $autocompleteContacts[] = $autoContact;
            }
        }

        //Users
        $autocompleteUsers = [];
        if (empty($queryParams['noUsers'])) {
            $fields = ['firstname', 'lastname'];

            if ($searchOnEmails) {
                $fields[] = 'mail';
            }

            $nbFields = count($fields);

            $fields = AutoCompleteController::getInsensitiveFieldsForRequest(['fields' => $fields]);
            $requestData = AutoCompleteController::getDataForRequest([
                'search'        => $queryParams['search'],
                'fields'        => $fields,
                'where'         => ['status not in (?)', 'mode not in (?)'],
                'data'          => [['DEL', 'SPD'], ['root_invisible', 'rest']],
                'fieldsNumber'  => $nbFields,
            ]);

            $users = UserModel::get([
                'select'    => ['id', 'firstname', 'lastname', 'mail'],
                'where'     => $requestData['where'],
                'data'      => $requestData['data'],
                'orderBy'   => ['lastname'],
                'limit'     => self::TINY_LIMIT
            ]);

            foreach ($users as $user) {
                $autoUser = [
                    'type'          => 'user',
                    'id'            => $user['id'],
                    'firstname'     => $user['firstname'],
                    'lastname'      => $user['lastname']
                ];

                if ($searchOnEmails) {
                    $autoUser['email'] = $user['mail'];
                }

                $autocompleteUsers[] = $autoUser;
            }
        }

        //Entities
        $autocompleteEntities = [];
        if (empty($queryParams['noEntities'])) {
            $fields = ['entity_label'];

            if ($searchOnEmails) {
                $fields[] = 'email';
            }

            $nbFields = count($fields);

            $fields = AutoCompleteController::getInsensitiveFieldsForRequest(['fields' => $fields]);
            $requestData = AutoCompleteController::getDataForRequest([
                'search'        => $queryParams['search'],
                'fields'        => $fields,
                'where'         => ['enabled = ?'],
                'data'          => ['Y'],
                'fieldsNumber'  => $nbFields,
            ]);

            $entities = EntityModel::get([
                'select'    => ['id', 'entity_id', 'entity_label', 'short_label', 'email'],
                'where'     => $requestData['where'],
                'data'      => $requestData['data'],
                'orderBy'   => ['entity_label'],
                'limit'     => self::TINY_LIMIT
            ]);

            foreach ($entities as $value) {
                $entity = [
                    'type'          => 'entity',
                    'id'            => $value['id'],
                    'lastname'      => $value['entity_label'],
                    'firstname'     => ''
                ];

                if ($searchOnEmails) {
                    $entity['email'] = $value['email'];
                }

                $autocompleteEntities[] = $entity;
            }
        }

        //Contacts Groups
        $autocompleteContactsGroups = [];
        if (empty($queryParams['noContactsGroups'])) {
            $fields = ['label'];
            $fields = AutoCompleteController::getInsensitiveFieldsForRequest(['fields' => $fields]);
            $requestData = AutoCompleteController::getDataForRequest([
                'search'        => $queryParams['search'],
                'fields'        => $fields,
                'where'         => ['(public = ? OR owner = ?)'],
                'data'          => [true, $GLOBALS['id']],
                'fieldsNumber'  => 1,
            ]);

            $contactsGroups = ContactGroupModel::get([
                'select'    => ['id', 'label'],
                'where'     => $requestData['where'],
                'data'      => $requestData['data'],
                'orderBy'   => ['label'],
                'limit'     => self::TINY_LIMIT
            ]);

            foreach ($contactsGroups as $value) {
                $autocompleteContactsGroups[] = [
                    'type'          => 'contactGroup',
                    'id'            => $value['id'],
                    'lastname'      => $value['label'],
                    'firstname'     => ''
                ];
            }
        }

        $total = count($autocompleteContacts) + count($autocompleteUsers) + count($autocompleteEntities) + count($autocompleteContactsGroups);
        if ($total > self::TINY_LIMIT) {
            $divider = $total / self::TINY_LIMIT;
            $autocompleteContacts       = array_slice($autocompleteContacts, 0, round(count($autocompleteContacts) / $divider));
            $autocompleteUsers          = array_slice($autocompleteUsers, 0, round(count($autocompleteUsers) / $divider));
            $autocompleteEntities       = array_slice($autocompleteEntities, 0, round(count($autocompleteEntities) / $divider));
            $autocompleteContactsGroups = array_slice($autocompleteContactsGroups, 0, round(count($autocompleteContactsGroups) / $divider));
        }
        $autocompleteData = array_merge($autocompleteContacts, $autocompleteUsers, $autocompleteEntities, $autocompleteContactsGroups);

        return $response->withJson($autocompleteData);
    }

    public static function getUsersForAdministration(Request $request, Response $response)
    {
        $data = $request->getQueryParams();
        $check = Validator::stringType()->notEmpty()->validate($data['search']);
        if (!$check) {
            return $response->withStatus(400)->withJson(['errors' => 'Bad Request']);
        }

        if (!UserController::isRoot(['id' => $GLOBALS['id']])) {
            $entities = EntityModel::getAllEntitiesByUserId(['userId' => $GLOBALS['id']]);

            $fields = ['users.firstname', 'users.lastname'];
            $fields = AutoCompleteController::getInsensitiveFieldsForRequest(['fields' => $fields]);

            $requestData = AutoCompleteController::getDataForRequest([
                'search'        => $data['search'],
                'fields'        => $fields,
                'where'         => [
                    'users.id = users_entities.user_id',
                    'users_entities.entity_id in (?)',
                    'users.status not in (?)'
                ],
                'data'          => [$entities, ['DEL', 'SPD']],
                'fieldsNumber'  => 2,
            ]);

            $users = DatabaseModel::select([
                'select'    => ['DISTINCT users.user_id', 'users.id', 'users.firstname', 'users.lastname'],
                'table'     => ['users, users_entities'],
                'where'     => $requestData['where'],
                'data'      => $requestData['data'],
                'limit'     => self::LIMIT
            ]);

            if (count($users) < self::LIMIT) {
                $fields = ['users.firstname', 'users.lastname'];
                $fields = AutoCompleteController::getInsensitiveFieldsForRequest(['fields' => $fields]);

                $requestData = AutoCompleteController::getDataForRequest([
                    'search'        => $data['search'],
                    'fields'        => $fields,
                    'where'         => [
                        'users_entities IS NULL',
                        'users.mode not in (?)',
                        'users.status not in (?)'
                    ],
                    'data'          => [['root_invisible'], ['DEL', 'SPD']],
                    'fieldsNumber'  => 2,
                ]);

                $usersNoEntities = DatabaseModel::select([
                    'select'    => ['users.id', 'users.user_id', 'users.firstname', 'users.lastname'],
                    'table'     => ['users', 'users_entities'],
                    'left_join' => ['users.id = users_entities.user_id'],
                    'where'     => $requestData['where'],
                    'data'      => $requestData['data'],
                    'limit'     => (self::LIMIT - count($users))
                ]);

                $users = array_merge($users, $usersNoEntities);
            }
        } else {
            $requestData = AutoCompleteController::getDataForRequest([
                'search'        => $data['search'],
                'fields'        => '(firstname ilike ? OR lastname ilike ?)',
                'where'         => ['status not in (?)', 'mode not in (?)'],
                'data'          => [['DEL', 'SPD'], ['root_invisible']],
                'fieldsNumber'  => 2,
            ]);

            $users = UserModel::get([
                'select'    => ['id', 'user_id', 'firstname', 'lastname'],
                'where'     => $requestData['where'],
                'data'      => $requestData['data'],
                'orderBy'   => ['lastname'],
                'limit'     => self::LIMIT
            ]);
        }

        $data = [];
        foreach ($users as $value) {
            $data[] = [
                'type'          => 'user',
                'id'            => $value['id'],
                'idToDisplay'   => "{$value['firstname']} {$value['lastname']}",
                'otherInfo'     => $value['user_id']
            ];
        }

        return $response->withJson($data);
    }

    public static function getUsersForCircuit(Request $request, Response $response)
    {
        $queryParams = $request->getQueryParams();

        $services = ['visa_documents', 'sign_document'];
        if (!empty($queryParams['circuit']) && $queryParams['circuit'] == 'opinion') {
            $services = ['avis_documents'];
        }

        $requestData['where'] = [
            'usergroups.group_id = usergroups_services.group_id',
            'usergroups.id = usergroup_content.group_id',
            'usergroup_content.user_id = users.id',
            'usergroups_services.service_id in (?)',
            'users.mode not in (?)',
            'users.status not in (?)'
        ];
        $requestData['data'] = [$services, ['root_invisible', 'rest'], ['DEL', 'SPD']];

        if (!empty($queryParams['search'])) {
            $fields = ['users.firstname', 'users.lastname'];
            $fields = AutoCompleteController::getInsensitiveFieldsForRequest(['fields' => $fields]);

            $requestData = AutoCompleteController::getDataForRequest([
                'search'        => $queryParams['search'],
                'fields'        => $fields,
                'where'         => $requestData['where'],
                'data'          => $requestData['data'],
                'fieldsNumber'  => 2,
            ]);
        }

        $users = DatabaseModel::select([
            'select'    => ['DISTINCT users.id', 'users.firstname', 'users.lastname'],
            'table'     => ['users, usergroup_content, usergroups, usergroups_services'],
            'where'     => $requestData['where'],
            'data'      => $requestData['data'],
            'order_by'  => ['users.lastname']
        ]);

        $data = [];
        foreach ($users as $value) {
            $entity = UserModel::getPrimaryEntityById(['id' => $value['id'], 'select' => ['entities.short_label']]);
            $data[] = [
                'type'          => 'user',
                'id'            => $value['id'],
                'idToDisplay'   => "{$value['firstname']} {$value['lastname']}",
                'otherInfo'     => $entity['short_label']
            ];
        }

        return $response->withJson($data);
    }

    public static function getEntities(Request $request, Response $response)
    {
        $queryParams = $request->getQueryParams();
        if (!Validator::stringType()->notEmpty()->validate($queryParams['search'])) {
            return $response->withStatus(400)->withJson(['errors' => 'Bad Request']);
        }

        $fields = ['entity_label'];
        $fields = AutoCompleteController::getInsensitiveFieldsForRequest(['fields' => $fields]);

        $requestData = AutoCompleteController::getDataForRequest([
            'search'        => $queryParams['search'],
            'fields'        => $fields,
            'where'         => ['enabled = ?'],
            'data'          => ['Y'],
            'fieldsNumber'  => 1,
        ]);

        $entities = EntityModel::get([
            'select'    => ['id', 'entity_id', 'entity_label', 'short_label'],
            'where'     => $requestData['where'],
            'data'      => $requestData['data'],
            'orderBy'   => ['entity_label'],
            'limit'     => self::LIMIT
        ]);

        $data = [];
        foreach ($entities as $value) {
            $data[] = [
                'type'          => 'entity',
                'id'            => empty($queryParams['serial']) ? $value['entity_id'] : $value['id'],
                'serialId'      => $value['id'],
                'idToDisplay'   => $value['entity_label'],
                'otherInfo'     => $value['short_label']
            ];
        }

        return $response->withJson($data);
    }

    public static function getStatuses(Request $request, Response $response)
    {
        $statuses = StatusModel::get(['select' => ['id', 'label_status', 'img_filename']]);

        $data = [];
        foreach ($statuses as $value) {
            $data[] = [
                'type'          => 'status',
                'id'            => $value['id'],
                'idToDisplay'   => $value['label_status'],
                'otherInfo'     => $value['img_filename']
            ];
        }

        return $response->withJson($data);
    }

    public static function getContacts(Request $request, Response $response)
    {
        $data = $request->getQueryParams();

        $check = Validator::stringType()->notEmpty()->validate($data['search']);
        if (!$check) {
            return $response->withStatus(400)->withJson(['errors' => 'Bad Request']);
        }

        $searchableParameters = ContactParameterModel::get(['select' => ['identifier'], 'where' => ['searchable = ?'], 'data' => [true]]);

        $fields = [];
        foreach ($searchableParameters as $searchableParameter) {
            if (strpos($searchableParameter['identifier'], 'contactCustomField_') !== false) {
                $customFieldId = explode('_', $searchableParameter['identifier'])[1];
                $fields[] = "custom_fields->>'{$customFieldId}'";
            } else {
                $fields[] = ContactController::MAPPING_FIELDS[$searchableParameter['identifier']];
            }
        }

        $fieldsNumber = count($fields);
        $fields = AutoCompleteController::getInsensitiveFieldsForRequest(['fields' => $fields]);

        $requestData = AutoCompleteController::getDataForRequest([
            'search'        => $data['search'],
            'fields'        => $fields,
            'where'         => ['enabled = ?'],
            'data'          => [true],
            'fieldsNumber'  => $fieldsNumber
        ]);

        $contacts = ContactModel::get([
            'select'    => ['id', 'firstname', 'lastname', 'company', 'address_number', 'address_street', 'address_town', 'address_postcode'],
            'where'     => $requestData['where'],
            'data'      => $requestData['data'],
            'orderBy'   => ['company', 'lastname NULLS FIRST'],
            'limit'     => 1000
        ]);

        $data = [];
        foreach ($contacts as $contact) {
            $data[] = ContactController::getFormattedContactWithAddress(['contact' => $contact])['contact'];
        }

        return $response->withJson($data);
    }

    public static function getContactsCompany(Request $request, Response $response)
    {
        $queryParams = $request->getQueryParams();

        if (!Validator::stringType()->notEmpty()->validate($queryParams['search'])) {
            return $response->withStatus(400)->withJson(['errors' => 'Query params search is empty']);
        }

        $fields = AutoCompleteController::getInsensitiveFieldsForRequest(['fields' => ['company']]);
        $contacts = ContactModel::get([
            'select'    => [
                'id', 'company', 'address_number as "addressNumber"', 'address_street as "addressStreet"',
                'address_additional1 as "addressAdditional1"', 'address_additional2 as "addressAdditional2"', 'address_postcode as "addressPostcode"',
                'address_town as "addressTown"', 'address_country as "addressCountry"'
            ],
            'where'     => ['enabled = ?', $fields],
            'data'      => [true, $queryParams['search'] . '%'],
            'orderBy'   => ['company', 'lastname'],
            'limit'     => 1
        ]);

        return $response->withJson($contacts);
    }

    public static function getBanAddresses(Request $request, Response $response)
    {
        $data = $request->getQueryParams();

        $check = Validator::stringType()->notEmpty()->validate($data['address']);
        $check = $check && Validator::stringType()->notEmpty()->validate($data['department']);
        if (!$check) {
            return $response->withStatus(400)->withJson(['errors' => 'Bad Request']);
        }
        $customId = CoreConfigModel::getCustomId();

        if (is_dir("custom/{$customId}/referential/ban/indexes/{$data['department']}")) {
            $path = "custom/{$customId}/referential/ban/indexes/{$data['department']}";
        } elseif (is_dir('referential/ban/indexes/' . $data['department'])) {
            $path = 'referential/ban/indexes/' . $data['department'];
        } else {
            return $response->withStatus(400)->withJson(['errors' => 'Department indexes do not exist']);
        }

        \Zend_Search_Lucene_Analysis_Analyzer::setDefault(new \Zend_Search_Lucene_Analysis_Analyzer_Common_Utf8Num_CaseInsensitive());
        \Zend_Search_Lucene_Search_QueryParser::setDefaultOperator(\Zend_Search_Lucene_Search_QueryParser::B_AND);
        \Zend_Search_Lucene_Search_QueryParser::setDefaultEncoding('utf-8');

        $index = \Zend_Search_Lucene::open($path);
        \Zend_Search_Lucene::setResultSetLimit(100);

        $data['address'] = str_replace(['*', '~', '-', '\''], ' ', $data['address']);
        $aAddress = explode(' ', $data['address']);
        foreach ($aAddress as $key => $value) {
            if (mb_strlen($value) <= 2 && !is_numeric($value)) {
                unset($aAddress[$key]);
                continue;
            }
            if (mb_strlen($value) >= 3 && $value != 'rue' && $value != 'avenue' && $value != 'boulevard') {
                $aAddress[$key] .= '*';
            }
        }
        $data['address'] = implode(' ', $aAddress);
        if (empty($data['address'])) {
            return $response->withJson([]);
        }

        $hits = $index->find(TextFormatModel::normalize(['string' => $data['address']]));

        $addresses = [];
        foreach ($hits as $key => $hit) {
            $addresses[] = [
                'banId'         => $hit->banId,
                'lon'           => $hit->lon,
                'lat'           => $hit->lat,
                'number'        => $hit->streetNumber,
                'afnorName'     => $hit->afnorName,
                'postalCode'    => $hit->postalCode,
                'city'          => $hit->city,
                'address'       => "{$hit->streetNumber} {$hit->afnorName}, {$hit->city} ({$hit->postalCode})"
            ];
        }

        return $response->withJson($addresses);
    }

    public static function getOuM2MAnnuary(Request $request, Response $response)
    {
        $data = $request->getQueryParams();

        $check = Validator::stringType()->notEmpty()->validate($data['company']);
        if (!$check) {
            return $response->withStatus(400)->withJson(['errors' => 'Query company is empty']);
        }

        $control = AnnuaryController::getAnnuaries();
        if (!isset($control['annuaries'])) {
            if (isset($control['errors'])) {
                return $response->withStatus(400)->withJson(['errors' => $control['errors']]);
            } elseif (isset($control['success'])) {
                return $response->withJson([]);
            }
        }

        $unitOrganizations = [];
        if (!empty($control['annuaries'])) {
            foreach ($control['annuaries'] as $annuary) {
                $ldap = @ldap_connect($annuary['uri']);
                if ($ldap === false) {
                    continue;
                }
                ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
                ldap_set_option($ldap, LDAP_OPT_REFERRALS, 0);
                ldap_set_option($ldap, LDAP_OPT_NETWORK_TIMEOUT, 5);
    
                $search = @ldap_search($ldap, $annuary['baseDN'], "(ou=*{$data['company']}*)", ['ou', 'postOfficeBox', 'destinationIndicator', 'labeledURI']);
                if ($search === false) {
                    continue;
                }
                $entries = ldap_get_entries($ldap, $search);
    
                foreach ($entries as $key => $value) {
                    if (!is_numeric($key)) {
                        continue;
                    }
                    if (!empty($value['postofficebox'])) {
                        $unitOrganizations[] = [
                            'communicationValue' => $value['postofficebox'][0],
                            'businessIdValue'    => $value['destinationindicator'][0],
                            'unitOrganization'   => "{$value['ou'][0]} ({$value['postofficebox'][0]})"
                        ];
                    }
                    if (!empty($value['labeleduri'])) {
                        $unitOrganizations[] = [
                            'communicationValue' => $value['labeleduri'][0],
                            'businessIdValue'    => $value['destinationindicator'][0],
                            'unitOrganization'   => "{$value['ou'][0]} ({$value['labeleduri'][0]})"
                        ];
                    }
                }
    
                break;
            }
        }
        
        return $response->withJson($unitOrganizations);
    }

    public static function getAvailableContactsForM2M(Request $request, Response $response)
    {
        $queryParams = $request->getQueryParams();
        if (!Validator::stringType()->notEmpty()->validate($queryParams['search'])) {
            return $response->withStatus(400)->withJson(['errors' => 'Query params search is empty']);
        }

        $autocompleteData = [];
        $searchableParameters = ContactParameterModel::get(['select' => ['identifier'], 'where' => ['searchable = ?'], 'data' => [true]]);

        $fields = [];
        foreach ($searchableParameters as $searchableParameter) {
            if (strpos($searchableParameter['identifier'], 'contactCustomField_') !== false) {
                $customFieldId = explode('_', $searchableParameter['identifier'])[1];
                $fields[] = "custom_fields->>'{$customFieldId}'";
            } else {
                $fields[] = ContactController::MAPPING_FIELDS[$searchableParameter['identifier']];
            }
        }

        $fieldsNumber = count($fields);
        $fields = AutoCompleteController::getInsensitiveFieldsForRequest(['fields' => $fields]);

        $requestData = AutoCompleteController::getDataForRequest([
            'search'        => $queryParams['search'],
            'fields'        => $fields,
            'where'         => ['enabled = ?', "external_id->>'m2m' is not null", "external_id->>'m2m' != ''", "(communication_means->>'url' is not null OR communication_means->>'email' is not null)"],
            'data'          => [true],
            'fieldsNumber'  => $fieldsNumber
        ]);

        $contacts = ContactModel::get([
            'select'    => ['id', 'communication_means', 'external_id'],
            'where'     => $requestData['where'],
            'data'      => $requestData['data'],
            'orderBy'   => ['company', 'lastname NULLS FIRST'],
            'limit'     => self::TINY_LIMIT
        ]);

        foreach ($contacts as $contact) {
            $autoContact = ContactController::getAutocompleteFormat(['id' => $contact['id']]);

            $externalId = json_decode($contact['external_id'], true);
            $communicationMeans = json_decode($contact['communication_means'], true);
            $autoContact['m2m'] = $externalId['m2m'];
            $autoContact['communicationMeans'] = $communicationMeans['url'] ?? $communicationMeans['email'];
            $autocompleteData[] = $autoContact;
        }

        return $response->withJson($autocompleteData);
    }

    public static function getBusinessIdM2MAnnuary(Request $request, Response $response)
    {
        $data = $request->getQueryParams();

        $check = Validator::stringType()->notEmpty()->validate($data['communicationValue']);
        if (!$check) {
            return $response->withStatus(400)->withJson(['errors' => 'Query communicationValue is empty']);
        }

        $control = AnnuaryController::getAnnuaries();
        if (!isset($control['annuaries'])) {
            if (isset($control['errors'])) {
                return $response->withStatus(400)->withJson(['errors' => $control['errors']]);
            } elseif (isset($control['success'])) {
                return $response->withJson([]);
            }
        }

        $unitOrganizations = [];
        foreach ($control['annuaries'] as $annuary) {
            $ldap = @ldap_connect($annuary['uri']);
            if ($ldap === false) {
                $error = 'Ldap connect failed : uri is maybe wrong';
                continue;
            }
            ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
            ldap_set_option($ldap, LDAP_OPT_REFERRALS, 0);
            ldap_set_option($ldap, LDAP_OPT_NETWORK_TIMEOUT, 5);

            if (filter_var($data['communicationValue'], FILTER_VALIDATE_EMAIL)) {
                $search = @ldap_search($ldap, $annuary['baseDN'], "(postofficebox={$data['communicationValue']})", ['destinationIndicator']);
            } else {
                $search = @ldap_search($ldap, $annuary['baseDN'], "(labeleduri={$data['communicationValue']})", ['destinationIndicator']);
            }
            if ($search === false) {
                $error = 'Ldap search failed : baseDN is maybe wrong => ' . ldap_error($ldap);
                continue;
            }
            $entriesOu = ldap_get_entries($ldap, $search);
            foreach ($entriesOu as $keyOu => $valueOu) {
                if (!is_numeric($keyOu)) {
                    continue;
                }
                $siret   = $valueOu['destinationindicator'][0];
                $search  = @ldap_search($ldap, $valueOu['dn'], "(cn=*)", ['cn', 'initials', 'entryUUID']);
                $entries = ldap_get_entries($ldap, $search);

                foreach ($entries as $key => $value) {
                    if (!is_numeric($key)) {
                        continue;
                    }
                    $unitOrganizations[] = [
                        'entryuuid'        => $value['entryuuid'][0],
                        'businessIdValue'  => $siret . '/' . $value['initials'][0],
                        'unitOrganization' => "{$value['cn'][0]} - {$siret}/{$value['initials'][0]}"
                    ];
                }
            }

            return $response->withJson($unitOrganizations);
        }
    }

    public static function getFolders(Request $request, Response $response)
    {
        $data = $request->getQueryParams();

        if (!Validator::stringType()->notEmpty()->validate($data['search'])) {
            return $response->withStatus(400)->withJson(['errors' => 'Query params search is empty']);
        }

        $scopedFolders = FolderController::getScopeFolders(['login' => $GLOBALS['login']]);
        if (empty($scopedFolders)) {
            return $response->withJson([]);
        }

        $arrScopedFoldersIds = array_column($scopedFolders, 'id');

        $fields = AutoCompleteController::getInsensitiveFieldsForRequest(['fields' => ['label']]);

        $selectedFolders = FolderModel::get([
            'where'    => ["{$fields} AND id in (?)"],
            'data'     => [ '%'.$data['search'].'%', $arrScopedFoldersIds],
            'orderBy'  => ['label']
        ]);

        $data = [];
        foreach ($selectedFolders as $value) {
            $data[] = [
                'id'            => $value['id'],
                'idToDisplay'   => $value['label'],
                'isPublic'      => $value['public'],
                'otherInfo'     => ''
            ];
        }

        return $response->withJson($data);
    }

    public static function getTags(Request $request, Response $response)
    {
        $data = $request->getQueryParams();

        if (!Validator::stringType()->notEmpty()->validate($data['search'])) {
            return $response->withStatus(400)->withJson(['errors' => 'Query params search is empty']);
        }

        $fields = ['label'];
        $fields = AutoCompleteController::getInsensitiveFieldsForRequest(['fields' => $fields]);

        $requestData = AutoCompleteController::getDataForRequest([
            'search'        => $data['search'],
            'fields'        => $fields,
            'where'         => ['1 = ?'],
            'data'          => ['1'],
            'fieldsNumber'  => 1,
        ]);

        $tags = TagModel::get([
            'select'    => ['id', 'label'],
            'where'     => $requestData['where'],
            'data'      => $requestData['data'],
            'orderBy'   => ['label'],
            'limit'     => self::LIMIT
        ]);

        $data = [];
        foreach ($tags as $value) {
            $data[] = [
                'id'            => $value['id'],
                'idToDisplay'   => $value['label']
            ];
        }

        return $response->withJson($data);
    }

    public static function getDataForRequest(array $args)
    {
        ValidatorModel::notEmpty($args, ['search', 'fields', 'fieldsNumber']);
        ValidatorModel::stringType($args, ['search', 'fields']);
        ValidatorModel::arrayType($args, ['where', 'data']);
        ValidatorModel::intType($args, ['fieldsNumber']);

        $searchItems = explode(' ', $args['search']);

        foreach ($searchItems as $keyItem => $item) {
            if (strlen($item) >= 2) {
                $args['where'][] = $args['fields'];

                $isIncluded = false;
                foreach ($searchItems as $key => $value) {
                    if ($keyItem == $key) {
                        continue;
                    }
                    if (strpos($value, $item) === 0) {
                        $isIncluded = true;
                    }
                }
                for ($i = 0; $i < $args['fieldsNumber']; $i++) {
                    if (!empty($args['longField'])) {
                        $args['data'][] = ($isIncluded ? "%{$item} %" : "%{$item}%");
                    } else {
                        $args['data'][] = ($isIncluded ? "%{$item}" : "%{$item}%");
                    }
                }
            }
        }

        return ['where' => $args['where'], 'data' => $args['data']];
    }

    public static function getInsensitiveFieldsForRequest(array $args)
    {
        ValidatorModel::notEmpty($args, ['fields']);
        ValidatorModel::arrayType($args, ['fields']);

        $fields = [];
        foreach ($args['fields'] as $key => $field) {
            $fields[$key] = "unaccent({$field}::text)";
            $fields[$key] .= " ilike unaccent(?::text)";
        }
        $fields = implode(' OR ', $fields);
        $fields = "({$fields})";

        return $fields;
    }
}
