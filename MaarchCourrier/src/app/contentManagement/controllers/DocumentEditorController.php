<?php

/**
 * Copyright Maarch since 2008 under licence GPLv3.
 * See LICENCE.txt file at the root folder for more details.
 * This file is part of Maarch software.
 */

/**
 * @brief Online Editor Controller
 *
 * @author dev@maarch.org
 */

namespace ContentManagement\controllers;

use Slim\Http\Request;
use Slim\Http\Response;
use SrcCore\models\CoreConfigModel;
use SrcCore\models\ValidatorModel;

class DocumentEditorController
{
    const DOCUMENT_EDITION_METHODS = ['java','onlyoffice','collaboraonline'];

    public static function get(Request $request, Response $response)
    {
        $allowedMethods = DocumentEditorController::getAllowedMethods();

        return $response->withJson($allowedMethods);
    }

    public static function getAllowedMethods()
    {
        $loadedXml = CoreConfigModel::getXmlLoaded(['path' => 'apps/maarch_entreprise/xml/documentEditorsConfig.xml']);

        $allowedMethods = [];
        foreach (self::DOCUMENT_EDITION_METHODS as $method) {
            if (!empty($loadedXml->$method->enabled) && $loadedXml->$method->enabled == 'true') {
                $allowedMethods[] = $method;
            }
        }

        return $allowedMethods;
    }

    public static function isAvailable(array $args)
    {
        ValidatorModel::notEmpty($args, ['uri', 'port']);
        ValidatorModel::stringType($args, ['uri', 'port']);

        $aUri = explode("/", $args['uri']);
        $exec = shell_exec("nc -vz -w 5 {$aUri[0]} {$args['port']} 2>&1");

        if (strpos($exec, 'not found') !== false) {
            return ['errors' => 'Netcat command not found', 'lang' => 'preRequisiteMissing'];
        }

        return strpos($exec, 'succeeded!') !== false || strpos($exec, 'open') !== false || strpos($exec, 'Connected') !== false;
    }
}
