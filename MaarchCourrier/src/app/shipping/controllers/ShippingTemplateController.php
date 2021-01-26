<?php

/**
* Copyright Maarch since 2008 under licence GPLv3.
* See LICENCE.txt file at the root folder for more details.
* This file is part of Maarch software.

* @brief   ShippingTemplateController
* @author  dev <dev@maarch.org>
* @ingroup core
*/

namespace Shipping\controllers;

use Convert\controllers\ConvertPdfController;
use Docserver\models\DocserverModel;
use Entity\models\EntityModel;
use Group\controllers\PrivilegeController;
use History\controllers\HistoryController;
use Respect\Validation\Validator;
use setasign\Fpdi\Tcpdf\Fpdi;
use Shipping\models\ShippingTemplateModel;
use Slim\Http\Request;
use Slim\Http\Response;
use SrcCore\models\PasswordModel;

class ShippingTemplateController
{
    public function get(Request $request, Response $response)
    {
        if (!PrivilegeController::hasPrivilege(['privilegeId' => 'admin_shippings', 'userId' => $GLOBALS['id']])) {
            return $response->withStatus(403)->withJson(['errors' => 'Service forbidden']);
        }

        return $response->withJson(['shippings' => ShippingTemplateModel::get(['select' => ['id', 'label', 'description', 'options', 'fee', 'entities', "account->>'id' as accountid"]])]);
    }

    public function getById(Request $request, Response $response, array $aArgs)
    {
        if (!PrivilegeController::hasPrivilege(['privilegeId' => 'admin_shippings', 'userId' => $GLOBALS['id']])) {
            return $response->withStatus(403)->withJson(['errors' => 'Service forbidden']);
        }

        if (!Validator::intVal()->validate($aArgs['id'])) {
            return $response->withStatus(400)->withJson(['errors' => 'id is not an integer']);
        }

        $shippingInfo = ShippingTemplateModel::getById(['id' => $aArgs['id']]);
        if (empty($shippingInfo)) {
            return $response->withStatus(400)->withJson(['errors' => 'Shipping does not exist']);
        }
        
        $shippingInfo['account'] = json_decode($shippingInfo['account'], true);
        $shippingInfo['account']['password'] = '';
        $shippingInfo['options']  = json_decode($shippingInfo['options'], true);
        $shippingInfo['fee']      = json_decode($shippingInfo['fee'], true);
        $shippingInfo['entities'] = json_decode($shippingInfo['entities'], true);

        $allEntities = EntityModel::get([
            'select'    => ['e1.id', 'e1.entity_id', 'e1.entity_label', 'e2.id as parent_id'],
            'table'     => ['entities e1', 'entities e2'],
            'left_join' => ['e1.parent_entity_id = e2.entity_id'],
            'where'     => ['e1.enabled = ?'],
            'data'      => ['Y']
        ]);

        foreach ($allEntities as $key => $value) {
            $allEntities[$key]['id'] = $value['id'];
            if (empty($value['parent_id'])) {
                $allEntities[$key]['parent'] = '#';
                $allEntities[$key]['icon']   = "fa fa-building";
            } else {
                $allEntities[$key]['parent'] = $value['parent_id'];
                $allEntities[$key]['icon']   = "fa fa-sitemap";
            }
            $allEntities[$key]['state']['opened'] = false;
            $allEntities[$key]['allowed']         = true;
            if (in_array($value['id'], $shippingInfo['entities'])) {
                $allEntities[$key]['state']['opened']   = true;
                $allEntities[$key]['state']['selected'] = true;
            }

            $allEntities[$key]['text'] = $value['entity_label'];
        }

        return $response->withJson(['shipping' => $shippingInfo, 'entities' => $allEntities]);
    }

    public function create(Request $request, Response $response)
    {
        if (!PrivilegeController::hasPrivilege(['privilegeId' => 'admin_shippings', 'userId' => $GLOBALS['id']])) {
            return $response->withStatus(403)->withJson(['errors' => 'Service forbidden']);
        }

        $body = $request->getParsedBody();
        
        $errors = ShippingTemplateController::checkData($body, 'create');
        if (!empty($errors)) {
            return $response->withStatus(400)->withJson(['errors' => $errors]);
        }

        if (!empty($body['account']['password'])) {
            $body['account']['password'] = PasswordModel::encrypt(['password' => $body['account']['password']]);
        }

        $body['options']  = json_encode($body['options']);
        $body['fee']      = json_encode($body['fee']);
        $body['entities'] = json_encode($body['entities']);
        $body['account']  = json_encode($body['account']);

        $id = ShippingTemplateModel::create([
            'label'       => $body['label'],
            'description' => $body['description'],
            'options'     => $body['options'],
            'fee'         => $body['fee'],
            'entities'    => $body['entities'],
            'account'     => $body['account']
        ]);

        HistoryController::add([
            'tableName' => 'shipping_templates',
            'recordId'  => $id,
            'eventType' => 'ADD',
            'eventId'   => 'shippingadd',
            'info'      => _MAILEVA_ADDED . ' : ' . $body['label']
        ]);

        return $response->withJson(['shippingId' => $id]);
    }

    public function update(Request $request, Response $response, array $aArgs)
    {
        if (!PrivilegeController::hasPrivilege(['privilegeId' => 'admin_shippings', 'userId' => $GLOBALS['id']])) {
            return $response->withStatus(403)->withJson(['errors' => 'Service forbidden']);
        }

        $body = $request->getParsedBody();
        $body['id'] = $aArgs['id'];

        $errors = ShippingTemplateController::checkData($body, 'update');
        if (!empty($errors)) {
            return $response->withStatus(500)->withJson(['errors' => $errors]);
        }

        if (!empty($body['account']['password'])) {
            $body['account']['password'] = PasswordModel::encrypt(['password' => $body['account']['password']]);
        } else {
            $shippingInfo = ShippingTemplateModel::getById(['id' => $aArgs['id'], 'select' => ['account']]);
            $shippingInfo['account'] = json_decode($shippingInfo['account'], true);
            $body['account']['password'] = $shippingInfo['account']['password'];
        }

        $body['options']  = json_encode($body['options']);
        $body['fee']      = json_encode($body['fee']);
        foreach ($body['entities'] as $key => $entity) {
            $body['entities'][$key] = (string)$entity;
        }
        $body['entities'] = json_encode($body['entities']);
        $body['account']  = json_encode($body['account']);

        ShippingTemplateModel::update($body);

        HistoryController::add([
            'tableName' => 'shipping_templates',
            'recordId'  => $aArgs['id'],
            'eventType' => 'UP',
            'eventId'   => 'shippingup',
            'info'      => _MAILEVA_UPDATED. ' : ' . $body['label']
        ]);

        return $response->withJson(['success' => 'success']);
    }

    public function delete(Request $request, Response $response, array $aArgs)
    {
        if (!PrivilegeController::hasPrivilege(['privilegeId' => 'admin_shippings', 'userId' => $GLOBALS['id']])) {
            return $response->withStatus(403)->withJson(['errors' => 'Service forbidden']);
        }

        if (!Validator::intVal()->validate($aArgs['id'])) {
            return $response->withStatus(400)->withJson(['errors' => 'id is not an integer']);
        }

        $shippingInfo = ShippingTemplateModel::getById(['id' => $aArgs['id'], 'select' => ['label']]);
        if (empty($shippingInfo)) {
            return $response->withStatus(400)->withJson(['errors' => 'Shipping does not exist']);
        }

        ShippingTemplateModel::delete(['id' => $aArgs['id']]);

        HistoryController::add([
            'tableName' => 'shipping_templates',
            'recordId'  => $aArgs['id'],
            'eventType' => 'DEL',
            'eventId'   => 'shippingdel',
            'info'      => _MAILEVA_DELETED. ' : ' . $shippingInfo['label']
        ]);

        $shippings = ShippingTemplateModel::get(['select' => ['id', 'label', 'description', 'options', 'fee', 'entities']]);
        return $response->withJson(['shippings' => $shippings]);
    }

    protected function checkData($aArgs, $mode)
    {
        $errors = [];

        if ($mode == 'update') {
            if (!Validator::intVal()->validate($aArgs['id'])) {
                $errors[] = 'Id is not a numeric';
            } else {
                $shippingInfo = ShippingTemplateModel::getById(['id' => $aArgs['id']]);
            }
            if (empty($shippingInfo)) {
                $errors[] = 'Shipping does not exist';
            }
        } else {
            if (!empty($aArgs['account'])) {
                if (!Validator::notEmpty()->validate($aArgs['account']['id']) || !Validator::notEmpty()->validate($aArgs['account']['password'])) {
                    $errors[] = 'account id or password is empty';
                }
            }
        }
           
        if (!Validator::notEmpty()->validate($aArgs['label']) ||
            !Validator::length(1, 64)->validate($aArgs['label'])) {
            $errors[] = 'label is empty or too long';
        }
        if (!Validator::notEmpty()->validate($aArgs['description']) ||
            !Validator::length(1, 255)->validate($aArgs['description'])) {
            $errors[] = 'description is empty or too long';
        }

        if (!empty($aArgs['entities'])) {
            if (!Validator::arrayType()->validate($aArgs['entities'])) {
                $errors[] = 'entities must be an array';
            }
            foreach ($aArgs['entities'] as $entity) {
                $info = EntityModel::getById(['id' => $entity, 'select' => ['id']]);
                if (empty($info)) {
                    $errors[] = $entity . ' does not exists';
                }
            }
        }

        if (!empty($aArgs['fee'])) {
            foreach ($aArgs['fee'] as $value) {
                if (!empty($value) && !Validator::floatVal()->positive()->validate($value)) {
                    $errors[] = 'fee must be an array with positive values';
                }
            }
        }

        return $errors;
    }

    public function initShipping(Request $request, Response $response)
    {
        if (!PrivilegeController::hasPrivilege(['privilegeId' => 'admin_shippings', 'userId' => $GLOBALS['id']])) {
            return $response->withStatus(403)->withJson(['errors' => 'Service forbidden']);
        }

        $allEntities = EntityModel::get([
            'select'    => ['e1.id', 'e1.entity_id', 'e1.entity_label', 'e2.id as parent_id'],
            'table'     => ['entities e1', 'entities e2'],
            'left_join' => ['e1.parent_entity_id = e2.entity_id'],
            'where'     => ['e1.enabled = ?'],
            'data'      => ['Y']
        ]);

        foreach ($allEntities as $key => $value) {
            $allEntities[$key]['id'] = (string)$value['id'];
            if (empty($value['parent_id'])) {
                $allEntities[$key]['parent'] = '#';
                $allEntities[$key]['icon']   = "fa fa-building";
            } else {
                $allEntities[$key]['parent'] = (string)$value['parent_id'];
                $allEntities[$key]['icon']   = "fa fa-sitemap";
            }

            $allEntities[$key]['allowed']           = true;
            $allEntities[$key]['state']['opened']   = true;

            $allEntities[$key]['text'] = $value['entity_label'];
        }

        return $response->withJson([
            'entities' => $allEntities,
        ]);
    }

    public static function calculShippingFee(array $aArgs)
    {
        $fee = 0;
        foreach ($aArgs['resources'] as $value) {
            $resourceId = $value['res_id'];

            $collId = $value['type'] == 'attachment' ? 'attachments_coll' : 'letterbox_coll';

            $convertedResource = ConvertPdfController::getConvertedPdfById(['resId' => $resourceId, 'collId' => $collId]);
            $docserver         = DocserverModel::getByDocserverId(['docserverId' => $convertedResource['docserver_id'], 'select' => ['path_template']]);
            $pathToDocument    = $docserver['path_template'] . str_replace('#', DIRECTORY_SEPARATOR, $convertedResource['path']) . $convertedResource['filename'];

            $img = new \Imagick();
            $img->pingImage($pathToDocument);
            $pageCount = $img->getNumberImages();

            $attachmentFee = ($pageCount > 1) ? ($pageCount - 1) * $aArgs['fee']['nextPagePrice'] : 0 ;
            $fee = $fee + $attachmentFee + $aArgs['fee']['firstPagePrice'] + $aArgs['fee']['postagePrice'];
        }

        return $fee;
    }
}
