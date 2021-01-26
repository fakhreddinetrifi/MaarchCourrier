<?php

/**
 * Copyright Maarch since 2008 under licence GPLv3.
 * See LICENCE.txt file at the root folder for more details.
 * This file is part of Maarch software.
 *
 */

/**
 * @brief Contact Group Controller
 * @author dev@maarch.org
 */

namespace Contact\controllers;

use Contact\models\ContactGroupModel;
use Contact\models\ContactModel;
use Group\controllers\PrivilegeController;
use History\controllers\HistoryController;
use Respect\Validation\Validator;
use Slim\Http\Request;
use Slim\Http\Response;
use User\models\UserModel;

class ContactGroupController
{
    public function get(Request $request, Response $response)
    {
        $hasService = PrivilegeController::hasPrivilege(['privilegeId' => 'admin_contacts', 'userId' => $GLOBALS['id']]);

        $contactsGroups = ContactGroupModel::get();
        foreach ($contactsGroups as $key => $contactsGroup) {
            if (!$contactsGroup['public'] && $GLOBALS['id'] != $contactsGroup['owner'] && !$hasService) {
                unset($contactsGroups[$key]);
                continue;
            }
            $contactsGroups[$key]['position']      = $key;
            $contactsGroups[$key]['labelledOwner'] = UserModel::getLabelledUserById(['id' => $contactsGroup['owner']]);
            $contactsGroups[$key]['nbContacts']    = ContactGroupModel::getListById(['id' => $contactsGroup['id'], 'select' => ['COUNT(1)']])[0]['count'];
        }
        
        return $response->withJson(['contactsGroups' => array_values($contactsGroups)]);
    }

    public function getById(Request $request, Response $response, array $aArgs)
    {
        $contactsGroup = ContactGroupModel::getById(['id' => $aArgs['id']]);
        if (empty($contactsGroup)) {
            return $response->withStatus(400)->withJson(['errors' => 'Contacts group not found']);
        } elseif (!$contactsGroup['public'] && $contactsGroup['owner'] != $GLOBALS['id']) {
            return $response->withStatus(403)->withJson(['errors' => 'Contacts group out of perimeter']);
        }

        $contactsGroup['labelledOwner'] = UserModel::getLabelledUserById(['id' => $contactsGroup['owner']]);
        $contactsGroup['contacts']      = ContactGroupController::getFormattedListById(['id' => $aArgs['id']])['list'];
        $contactsGroup['nbContacts']    = count($contactsGroup['contacts']);

        return $response->withJson(['contactsGroup' => $contactsGroup]);
    }

    public function create(Request $request, Response $response)
    {
        $data = $request->getParams();
        $check = Validator::stringType()->notEmpty()->validate($data['label']);
        $check = $check && Validator::stringType()->notEmpty()->validate($data['description']);
        $check = $check && Validator::boolType()->validate($data['public']);
        if (!$check) {
            return $response->withStatus(400)->withJson(['errors' => 'Bad Request']);
        }

        $existingGroup = ContactGroupModel::get(['select' => [1], 'where' => ['label = ?', 'owner = ?'], 'data' => [$data['label'], $GLOBALS['id']]]);
        if (!empty($existingGroup)) {
            return $response->withStatus(400)->withJson(['errors' => _CONTACTS_GROUP_LABEL_ALREADY_EXISTS]);
        }

        $data['public']       = $data['public'] ? 'true' : 'false';
        $data['owner']        = $GLOBALS['id'];

        $id = ContactGroupModel::create($data);

        HistoryController::add([
            'tableName' => 'contacts_groups',
            'recordId'  => $id,
            'eventType' => 'ADD',
            'info'      => _CONTACTS_GROUP_ADDED . " : {$data['label']}",
            'moduleId'  => 'contact',
            'eventId'   => 'contactsGroupCreation',
        ]);

        return $response->withJson(['contactsGroup' => $id]);
    }

    public function update(Request $request, Response $response, array $aArgs)
    {
        $contactsGroup = ContactGroupModel::getById(['select' => ['owner'], 'id' => $aArgs['id']]);
        if (empty($contactsGroup)) {
            return $response->withStatus(400)->withJson(['errors' => 'Contacts Group does not exist']);
        }

        if ($contactsGroup['owner'] != $GLOBALS['id'] && !PrivilegeController::hasPrivilege(['privilegeId' => 'admin_contacts', 'userId' => $GLOBALS['id']])) {
            return $response->withStatus(403)->withJson(['errors' => 'Service forbidden']);
        }

        $data = $request->getParams();
        $check = Validator::stringType()->notEmpty()->validate($data['label']);
        $check = $check && Validator::stringType()->notEmpty()->validate($data['description']);
        $check = $check && Validator::boolType()->validate($data['public']);
        if (!$check) {
            return $response->withStatus(400)->withJson(['errors' => 'Bad Request']);
        }

        $existingGroup = ContactGroupModel::get(['select' => [1], 'where' => ['label = ?', 'owner = ?', 'id != ?'], 'data' => [$data['label'], $GLOBALS['id'], $aArgs['id']]]);
        if (!empty($existingGroup)) {
            return $response->withStatus(400)->withJson(['errors' => _CONTACTS_GROUP_LABEL_ALREADY_EXISTS]);
        }

        $data['id'] = $aArgs['id'];
        $data['public'] = $data['public'] ? 'true' : 'false';

        ContactGroupModel::update($data);

        HistoryController::add([
            'tableName' => 'contacts_groups',
            'recordId'  => $aArgs['id'],
            'eventType' => 'UP',
            'info'      => _CONTACTS_GROUP_UPDATED . " : {$data['label']}",
            'moduleId'  => 'contact',
            'eventId'   => 'contactsGroupModification',
        ]);

        return $response->withJson(['success' => 'success']);
    }

    public function delete(Request $request, Response $response, array $aArgs)
    {
        $contactsGroup = ContactGroupModel::getById(['select' => ['owner'], 'id' => $aArgs['id']]);
        if (empty($contactsGroup)) {
            return $response->withStatus(400)->withJson(['errors' => 'Contacts Group does not exist']);
        }

        if ($contactsGroup['owner'] != $GLOBALS['id'] && !PrivilegeController::hasPrivilege(['privilegeId' => 'admin_contacts', 'userId' => $GLOBALS['id']])) {
            return $response->withStatus(403)->withJson(['errors' => 'Service forbidden']);
        }

        ContactGroupModel::delete(['id' => $aArgs['id']]);

        HistoryController::add([
            'tableName' => 'contacts_groups',
            'recordId'  => $aArgs['id'],
            'eventType' => 'DEL',
            'info'      => _CONTACTS_GROUP_DELETED . " : {$aArgs['id']}",
            'moduleId'  => 'contact',
            'eventId'   => 'contactsGroupSuppression',
        ]);

        $contactsGroups = ContactGroupModel::get();
        foreach ($contactsGroups as $key => $contactsGroup) {
            $contactsGroups[$key]['labelledOwner'] = UserModel::getLabelledUserById(['id' => $contactsGroup['owner']]);
        }

        return $response->withJson(['success' => 'success']);
    }

    public function addContacts(Request $request, Response $response, array $aArgs)
    {
        $contactsGroup = ContactGroupModel::getById(['id' => $aArgs['id']]);
        if (empty($contactsGroup)) {
            return $response->withStatus(400)->withJson(['errors' => 'Contacts Group does not exist']);
        }

        if ($contactsGroup['owner'] != $GLOBALS['id'] && !PrivilegeController::hasPrivilege(['privilegeId' => 'admin_contacts', 'userId' => $GLOBALS['id']])) {
            return $response->withStatus(403)->withJson(['errors' => 'Service forbidden']);
        }

        $data = $request->getParams();
        $check = Validator::arrayType()->notEmpty()->validate($data['contacts']);
        if (!$check) {
            return $response->withStatus(400)->withJson(['errors' => 'Bad Request']);
        }

        $rawList = ContactGroupModel::getListById(['select' => ['contact_id'], 'id' => $aArgs['id']]);
        $list = array_column($rawList, 'contact_id');

        foreach ($data['contacts'] as $contactId) {
            if (!in_array($contactId, $list)) {
                ContactGroupModel::addContact(['id' => $aArgs['id'], 'contactId' => $contactId]);
            }
        }

        HistoryController::add([
            'tableName' => 'contacts_groups_lists',
            'recordId'  => $aArgs['id'],
            'eventType' => 'ADD',
            'info'      => _CONTACTS_GROUP_LIST_ADDED . " : {$contactsGroup['label']}",
            'moduleId'  => 'contact',
            'eventId'   => 'contactsGroupListCreation',
        ]);

        $contactsGroup['labelledOwner'] = UserModel::getLabelledUserById(['id' => $contactsGroup['owner']]);
        $contactsGroup['contacts'] = ContactGroupController::getFormattedListById(['id' => $aArgs['id']])['list'];

        return $response->withJson(['contactsGroup' => $contactsGroup]);
    }

    public function deleteContact(Request $request, Response $response, array $aArgs)
    {
        $contactsGroup = ContactGroupModel::getById(['select' => ['owner', 'label'], 'id' => $aArgs['id']]);
        if (empty($contactsGroup)) {
            return $response->withStatus(400)->withJson(['errors' => 'Contacts Group does not exist']);
        }

        if ($contactsGroup['owner'] != $GLOBALS['id'] && !PrivilegeController::hasPrivilege(['privilegeId' => 'admin_contacts', 'userId' => $GLOBALS['id']])) {
            return $response->withStatus(403)->withJson(['errors' => 'Service forbidden']);
        }

        ContactGroupModel::deleteContact(['id' => $aArgs['id'], 'contactId' => $aArgs['contactId']]);

        HistoryController::add([
            'tableName' => 'contacts_groups_lists',
            'recordId'  => $aArgs['id'],
            'eventType' => 'DEL',
            'info'      => _CONTACTS_GROUP_LIST_DELETED . " : {$contactsGroup['label']}",
            'moduleId'  => 'contact',
            'eventId'   => 'contactsGroupListSuppression',
        ]);

        return $response->withJson(['success' => 'success']);
    }

    public static function getFormattedListById(array $aArgs)
    {
        $list = ContactGroupModel::getListById(['select' => ['contact_id'], 'id' => $aArgs['id']]);

        $contacts = [];
        $position = 0;
        foreach ($list as $listItem) {
            $contact = ContactModel::getById([
                'select'    => ['id', 'firstname', 'lastname', 'email', 'company', 'address_number', 'address_street', 'address_town', 'address_postcode', 'enabled'],
                'id'        => $listItem['contact_id']
            ]);

            if (!empty($contact) && $contact['enabled']) {
                $email = $contact['email'];
                $contact = ContactController::getFormattedContactWithAddress(['contact' => $contact, 'position' => $position, 'color' => true])['contact'];
                $contact['email'] = $email;
                $contact['position'] = !empty($position) ? $position : 0;
                $contacts[] = $contact;
                ++$position;
            }
        }

        return ['list' => $contacts];
    }
}
