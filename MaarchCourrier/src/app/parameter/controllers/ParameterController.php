<?php

/**
* Copyright Maarch since 2008 under licence GPLv3.
* See LICENCE.txt file at the root folder for more details.
* This file is part of Maarch software.

* @brief   ParametersController
* @author  dev <dev@maarch.org>
* @ingroup core
*/

/**
 * @brief Parameter Controller
 * @author dev@maarch.org
 */

namespace Parameter\controllers;

use Group\controllers\PrivilegeController;
use History\controllers\HistoryController;
use Parameter\models\ParameterModel;
use Respect\Validation\Validator;
use Slim\Http\Request;
use Slim\Http\Response;
use SrcCore\models\CoreConfigModel;

class ParameterController
{
    public function get(Request $request, Response $response)
    {
        if (!PrivilegeController::hasPrivilege(['privilegeId' => 'admin_parameters', 'userId' => $GLOBALS['id']])) {
            return $response->withStatus(403)->withJson(['errors' => 'Service forbidden']);
        }

        $parameters = ParameterModel::get();

        foreach ($parameters as $key => $parameter) {
            if (!empty($parameter['param_value_string'])) {
                $parameters[$key]['value'] = $parameter['param_value_string'];
            } elseif (is_int($parameter['param_value_int'])) {
                $parameters[$key]['value'] = $parameter['param_value_int'];
            } elseif (!empty($parameter['param_value_date'])) {
                $parameters[$key]['value'] = $parameter['param_value_date'];
            }
        }

        return $response->withJson(['parameters' => $parameters]);
    }

    public function getById(Request $request, Response $response, array $aArgs)
    {
        if (!PrivilegeController::hasPrivilege(['privilegeId' => 'admin_parameters', 'userId' => $GLOBALS['id']])) {
            return $response->withStatus(403)->withJson(['errors' => 'Service forbidden']);
        }

        $parameter = ParameterModel::getById(['id' => $aArgs['id']]);

        if (empty($parameter)) {
            return $response->withStatus(400)->withJson(['errors' => 'Parameter not found']);
        }

        return $response->withJson(['parameter' => $parameter]);
    }

    public function create(Request $request, Response $response)
    {
        if (!PrivilegeController::hasPrivilege(['privilegeId' => 'admin_parameters', 'userId' => $GLOBALS['id']])) {
            return $response->withStatus(403)->withJson(['errors' => 'Service forbidden']);
        }

        $data = $request->getParams();

        $check = Validator::stringType()->notEmpty()->validate($data['id']) && preg_match("/^[\w-]*$/", $data['id']);
        $check = $check && (empty($data['param_value_int']) || Validator::intVal()->validate($data['param_value_int']));
        $check = $check && (empty($data['param_value_string']) || Validator::stringType()->validate($data['param_value_string']));
        if (!$check) {
            return $response->withStatus(400)->withJson(['errors' => 'Bad Request']);
        }

        $parameter = ParameterModel::getById(['id' => $data['id']]);
        if (!empty($parameter)) {
            return $response->withStatus(400)->withJson(['errors' => _PARAMETER_ID_ALREADY_EXISTS]);
        }

        ParameterModel::create($data);
        HistoryController::add([
            'tableName' => 'parameters',
            'recordId'  => $data['id'],
            'eventType' => 'ADD',
            'info'      => _PARAMETER_CREATION . " : {$data['id']}",
            'moduleId'  => 'parameter',
            'eventId'   => 'parameterCreation',
        ]);

        return $response->withJson(['success' => 'success']);
    }

    public function update(Request $request, Response $response, array $args)
    {
        if (!PrivilegeController::hasPrivilege(['privilegeId' => 'admin_parameters', 'userId' => $GLOBALS['id']])) {
            return $response->withStatus(403)->withJson(['errors' => 'Service forbidden']);
        }

        $body = $request->getParsedBody();

        $customId = CoreConfigModel::getCustomId();
        if (in_array($args['id'], ['logo', 'bodyImage'])) {
            if (empty($customId)) {
                return $response->withStatus(400)->withJson(['errors' => 'A custom is needed for this operation']);
            }

            $tmpPath = CoreConfigModel::getTmpPath();
            if (!is_dir("custom/{$customId}/img")) {
                mkdir("custom/{$customId}/img", 0755, true);
            }
            if ($args['id'] == 'logo') {
                if (strpos($body['image'], 'data:image/svg+xml;base64,') === false) {
                    return $response->withStatus(400)->withJson(['errors' => 'Body image is not a base64 image']);
                }
                $tmpFileName  = $tmpPath . 'parameter_logo_' . rand() . '_file.svg';
                $body['logo'] = str_replace('data:image/svg+xml;base64,', '', $body['image']);
                $file         = base64_decode($body['logo']);
                file_put_contents($tmpFileName, $file);

                $size = strlen($file);
                if ($size > 5000000) {
                    return $response->withStatus(400)->withJson(['errors' => 'Logo size is not allowed']);
                }
                copy($tmpFileName, "custom/{$customId}/img/logo.svg");
            } elseif ($args['id'] == 'bodyImage') {
                if (strpos($body['image'], 'data:image/jpeg;base64,') === false) {
                    if (!is_file("dist/{$body['image']}")) {
                        return $response->withStatus(400)->withJson(['errors' => 'Body image does not exist']);
                    }
                    copy("dist/{$body['image']}", "custom/{$customId}/img/bodylogin.jpg");
                } else {
                    $tmpFileName   = $tmpPath . 'parameter_body_' . rand() . '_file.jpg';
                    $body['image'] = str_replace('data:image/jpeg;base64,', '', $body['image']);
                    $file          = base64_decode($body['image']);
                    file_put_contents($tmpFileName, $file);

                    $size       = strlen($file);
                    $imageSizes = getimagesize($tmpFileName);
                    if ($imageSizes[0] < 1920 || $imageSizes[1] < 1080) {
                        return $response->withStatus(400)->withJson(['errors' => 'Body image is not wide enough']);
                    } elseif ($size > 10000000) {
                        return $response->withStatus(400)->withJson(['errors' => 'Body size is not allowed']);
                    }
                    copy($tmpFileName, "custom/{$customId}/img/bodylogin.jpg");
                }
            }
            if (!empty($tmpFileName) && is_file($tmpFileName)) {
                unset($tmpFileName);
            }
        } elseif ($args['id'] == 'applicationName') {
            $config = CoreConfigModel::getJsonLoaded(['path' => 'apps/maarch_entreprise/xml/config.json']);
            $config['config']['applicationName'] = $body['applicationName'];
            if (file_exists("custom/{$customId}/apps/maarch_entreprise/xml/config.json")) {
                $fp = fopen("custom/{$customId}/apps/maarch_entreprise/xml/config.json", 'w');
            } else {
                $fp = fopen("apps/maarch_entreprise/xml/config.json", 'w');
            }
            fwrite($fp, json_encode($config, JSON_PRETTY_PRINT));
            fclose($fp);
        } elseif (in_array($args['id'], ['bindingDocumentFinalAction', 'nonBindingDocumentFinalAction'])) {
            $parameter = ParameterModel::getById(['id' => $args['id']]);
            if (empty($parameter)) {
                return $response->withStatus(400)->withJson(['errors' => 'Parameter not found']);
            }
            if (!in_array($body['param_value_string'], ['restrictAccess', 'transfer', 'copy', 'delete'])) {
                return $response->withStatus(400)->withJson(['errors' => 'param_value_string must be between : restrictAccess, transfer, copy, delete']);
            }
            ParameterModel::update([
                'description'        => '',
                'param_value_string' => $body['param_value_string'],
                'id'                 => $args['id']
            ]);
        } else {
            $parameter = ParameterModel::getById(['id' => $args['id']]);
            if (empty($parameter)) {
                return $response->withStatus(400)->withJson(['errors' => 'Parameter not found']);
            }
    
            $check = (empty($body['param_value_int']) || Validator::intVal()->validate($body['param_value_int']));
            $check = $check && (empty($body['param_value_string']) || Validator::stringType()->validate($body['param_value_string']));
            if (!$check) {
                return $response->withStatus(400)->withJson(['errors' => 'Bad Request']);
            }
    
            $body['id'] = $args['id'];
            ParameterModel::update($body);
        }

        HistoryController::add([
            'tableName' => 'parameters',
            'recordId'  => $args['id'],
            'eventType' => 'UP',
            'info'      => _PARAMETER_MODIFICATION . " : {$args['id']}",
            'moduleId'  => 'parameter',
            'eventId'   => 'parameterModification',
        ]);

        return $response->withStatus(204);
    }

    public function delete(Request $request, Response $response, array $aArgs)
    {
        if (!PrivilegeController::hasPrivilege(['privilegeId' => 'admin_parameters', 'userId' => $GLOBALS['id']])) {
            return $response->withStatus(403)->withJson(['errors' => 'Service forbidden']);
        }

        ParameterModel::delete(['id' => $aArgs['id']]);
        HistoryController::add([
            'tableName' => 'parameters',
            'recordId'  => $aArgs['id'],
            'eventType' => 'DEL',
            'info'      => _PARAMETER_SUPPRESSION . " : {$aArgs['id']}",
            'moduleId'  => 'parameter',
            'eventId'   => 'parameterSuppression',
        ]);

        $parameters = ParameterModel::get();
        foreach ($parameters as $key => $parameter) {
            if (!empty($parameter['param_value_string'])) {
                $parameters[$key]['value'] = $parameter['param_value_string'];
            } elseif (!empty($parameter['param_value_int'])) {
                $parameters[$key]['value'] = $parameter['param_value_int'];
            } elseif (!empty($parameter['param_value_date'])) {
                $parameters[$key]['value'] = $parameter['param_value_date'];
            }
        }

        return $response->withJson(['parameters' => $parameters]);
    }
}
