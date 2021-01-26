<?php

/**
 * Copyright Maarch since 2008 under licence GPLv3.
 * See LICENCE.txt file at the root folder for more details.
 * This file is part of Maarch software.
 *
 */

/**
 * @brief Configuration Controller
 * @author dev@maarch.org
 */

namespace Configuration\controllers;

use Configuration\models\ConfigurationModel;
use Group\controllers\PrivilegeController;
use History\controllers\HistoryController;
use Respect\Validation\Validator;
use Slim\Http\Request;
use Slim\Http\Response;
use SrcCore\models\PasswordModel;

class ConfigurationController
{
    public function getByPrivilege(Request $request, Response $response, array $args)
    {
        if (in_array($args['privilege'], ['admin_sso'])) {
            if (!PrivilegeController::hasPrivilege(['privilegeId' => 'admin_connections', 'userId' => $GLOBALS['id']])) {
                return $response->withStatus(403)->withJson(['errors' => 'Service forbidden']);
            }
        } elseif (!PrivilegeController::hasPrivilege(['privilegeId' => $args['privilege'], 'userId' => $GLOBALS['id']])) {
            return $response->withStatus(403)->withJson(['errors' => 'Service forbidden']);
        }

        $configuration = ConfigurationModel::getByPrivilege(['privilege' => $args['privilege']]);
        $configuration['value'] = json_decode($configuration['value'], true);
        if ($args['privilege'] == 'admin_email_server') {
            if (!empty($configuration['value']['password'])) {
                $configuration['value']['password'] = '';
                $configuration['value']['passwordAlreadyExists'] = true;
            } else {
                $configuration['value']['passwordAlreadyExists'] = false;
            }
        }

        return $response->withJson(['configuration' => $configuration]);
    }

    public function update(Request $request, Response $response, array $args)
    {
        if (in_array($args['privilege'], ['admin_sso'])) {
            if (!PrivilegeController::hasPrivilege(['privilegeId' => 'admin_connections', 'userId' => $GLOBALS['id']])) {
                return $response->withStatus(403)->withJson(['errors' => 'Service forbidden']);
            }
        } elseif (!PrivilegeController::hasPrivilege(['privilegeId' => $args['privilege'], 'userId' => $GLOBALS['id']])) {
            return $response->withStatus(403)->withJson(['errors' => 'Service forbidden']);
        }

        if (empty(ConfigurationModel::getByPrivilege(['privilege' => $args['privilege'], 'select' => [1]]))) {
            return $response->withStatus(400)->withJson(['errors' => 'Privilege configuration does not exist']);
        }

        $data = $request->getParams();

        if ($args['privilege'] == 'admin_email_server') {
            if ($data['auth'] && empty($data['password'])) {
                $configuration = ConfigurationModel::getByPrivilege(['privilege' => $args['privilege']]);
                $configuration['value'] = json_decode($configuration['value'], true);
                if (!empty($configuration['value']['password'])) {
                    $data['password'] = $configuration['value']['password'];
                }
            } elseif ($data['auth'] && !empty($data['password'])) {
                $data['password'] = PasswordModel::encrypt(['password' => $data['password']]);
            }
            $check = ConfigurationController::checkMailer($data);
            if (!empty($check['errors'])) {
                return $response->withStatus($check['code'])->withJson(['errors' => $check['errors']]);
            }
            $data['charset'] = empty($data['charset']) ? 'utf-8' : $data['charset'];
            unset($data['passwordAlreadyExists']);
        } elseif ($args['privilege'] == 'admin_search') {
            if (!Validator::notEmpty()->arrayType()->validate($data['listDisplay'])) {
                return $response->withStatus(400)->withJson(['errors' => 'Body listDisplay is empty or not an array']);
            }
            if (isset($data['listDisplay']['subInfos']) && !Validator::arrayType()->validate($data['listDisplay']['subInfos'])) {
                return $response->withStatus(400)->withJson(['errors' => 'Body listDisplay[subInfos] is not set or not an array']);
            }
            if (!Validator::intVal()->validate($data['listDisplay']['templateColumns'])) {
                return $response->withStatus(400)->withJson(['errors' => 'Body listDisplay[templateColumns] is not set or not an array']);
            }
            foreach ($data['listDisplay']['subInfos'] as $value) {
                if (!Validator::stringType()->notEmpty()->validate($value['value'])) {
                    return $response->withStatus(400)->withJson(['errors' => 'Body listDisplay[subInfos][value] is empty or not a string']);
                } elseif (!isset($value['cssClasses']) || !is_array($value['cssClasses'])) {
                    return $response->withStatus(400)->withJson(['errors' => 'Body listDisplay[subInfos][cssClasses] is not set or not an array']);
                }
            }

            if (empty($data['listEvent']['defaultTab'])) {
                $data['listEvent']['defaultTab'] = 'dashboard';
            }

            $data = ['listDisplay' => $data['listDisplay'], 'listEvent' => $data['listEvent']];
        } elseif ($args['privilege'] == 'admin_sso') {
            if (!empty($data['url']) && !Validator::stringType()->validate($data['url'])) {
                return $response->withStatus(400)->withJson(['errors' => 'Body url is empty or not a string']);
            }
            if (!Validator::notEmpty()->arrayType()->validate($data['mapping'])) {
                return $response->withStatus(400)->withJson(['errors' => 'Body mapping is empty or not an array']);
            }
            foreach ($data['mapping'] as $key => $mapping) {
                if (!Validator::notEmpty()->stringType()->validate($mapping['ssoId'])) {
                    return $response->withStatus(400)->withJson(['errors' => "Body mapping[$key]['ssoId'] is empty or not a string"]);
                }
                if (!Validator::notEmpty()->stringType()->validate($mapping['maarchId'])) {
                    return $response->withStatus(400)->withJson(['errors' => "Body mapping[$key]['maarchId'] is empty or not a string"]);
                }
            }
        }

        $data = json_encode($data, JSON_UNESCAPED_SLASHES);
        ConfigurationModel::update(['set' => ['value' => $data], 'where' => ['privilege = ?'], 'data' => [$args['privilege']]]);

        HistoryController::add([
            'tableName' => 'configurations',
            'recordId'  => $args['privilege'],
            'eventType' => 'UP',
            'eventId'   => 'configurationUp',
            'info'       => _CONFIGURATION_UPDATED . ' : ' . $args['privilege']
        ]);

        return $response->withJson(['success' => 'success']);
    }

    private static function checkMailer(array $args)
    {
        if (!Validator::stringType()->notEmpty()->validate($args['type'])) {
            return ['errors' => 'Configuration type is missing', 'code' => 400];
        }
        if (!Validator::email()->notEmpty()->validate($args['from'])) {
            return ['errors' => 'Configuration from is missing or not well formatted', 'code' => 400];
        }
        
        if (in_array($args['type'], ['smtp', 'mail'])) {
            $check = Validator::stringType()->notEmpty()->validate($args['host']);
            $check = $check && Validator::intVal()->notEmpty()->validate($args['port']);
            $check = $check && Validator::boolType()->validate($args['auth']);
            if ($args['auth']) {
                $check = $check && Validator::stringType()->notEmpty()->validate($args['user']);
                $check = $check && Validator::stringType()->notEmpty()->validate($args['password']);
            }
            $check = $check && Validator::stringType()->validate($args['secure']);
            if (!$check) {
                return ['errors' => "Configuration data is missing or not well formatted", 'code' => 400];
            }
        }

        return ['success' => 'success'];
    }
}
