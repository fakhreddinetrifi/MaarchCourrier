<?php

/**
* Copyright Maarch since 2008 under licence GPLv3.
* See LICENCE.txt file at the root folder for more details.
* This file is part of Maarch software.
*
*/

/**
* @brief Logs Controller
* @author dev@maarch.org
* @ingroup core
*/

namespace SrcCore\controllers;

use SrcCore\models\TextFormatModel;
use SrcCore\models\ValidatorModel;
use SrcCore\models\CoreConfigModel;

require_once 'apps/maarch_entreprise/tools/log4php/Logger.php'; //TODO composer

class LogsController
{
    public static function buildLoggingMethod()
    {
        $loggingMethods = [];

        $loadedXml = CoreConfigModel::getXmlLoaded(['path' => 'apps/maarch_entreprise/xml/logging_method.xml']);
        if ($loadedXml) {
            foreach ($loadedXml->METHOD as $METHOD) {
                $loggingMethods[] = [
                    'ID'               => (string)$METHOD->ID,
                    'ACTIVATED'        => (boolean)$METHOD->ENABLED,
                    'LOGGER_NAME_TECH' => (string)$METHOD->LOGGER_NAME_TECH,
                    'LOGGER_NAME_FUNC' => (string)$METHOD->LOGGER_NAME_FUNC,
                    'LOG_FORMAT'       => (string)$METHOD->APPLI_LOG_FORMAT,
                    'CODE_METIER'      => (string)$METHOD->CODE_METIER
                ];
            }
        } else {
            $loggingMethods[0]['ID']               = 'database';
            $loggingMethods[0]['ACTIVATED']        = true;
            $loggingMethods[1]['ID']               = 'log4php';
            $loggingMethods[1]['ACTIVATED']        = true;
            $loggingMethods[1]['LOGGER_NAME_TECH'] = 'loggerTechnique';
            $loggingMethods[1]['LOGGER_NAME_FUNC'] = 'loggerFonctionnel';
            $loggingMethods[1]['LOG_FORMAT']       = '[%RESULT%][%CODE_METIER%][%WHERE%][%ID%][%HOW%][%USER%][%WHAT%][%ID_MODULE%][%REMOTE_IP%]';
            $loggingMethods[1]['CODE_METIER']      = 'MAARCH';
        }

        return $loggingMethods;
    }

    public static function writeLog(array $aArgs)
    {
        ValidatorModel::notEmpty($aArgs, ['logLine', 'level', 'loggerName']);
        ValidatorModel::stringType($aArgs, ['logLine', 'level', 'loggerName']);

        $customId = CoreConfigModel::getCustomId();
        if (file_exists("custom/{$customId}/apps/maarch_entreprise/xml/log4php.xml")) {
            $path = "custom/{$customId}/apps/maarch_entreprise/xml/log4php.xml";
        } elseif (file_exists('apps/maarch_entreprise/xml/log4php.xml')) {
            $path = 'apps/maarch_entreprise/xml/log4php.xml';
        } else {
            $path = 'apps/maarch_entreprise/xml/log4php.default.xml';
        }

        \Logger::configure($path);
        $logger = \Logger::getLogger($aArgs['loggerName']);

        switch ($aArgs['level']) {
            case 'DEBUG':
                $logger->debug($aArgs['logLine']);
                break;
            case 'INFO':
                $logger->info($aArgs['logLine']);
                break;
            case 'WARN':
                $logger->warn($aArgs['logLine']);
                break;
            case 'ERROR':
                $logger->error($aArgs['logLine']);
                break;
            case 'FATAL':
                $logger->fatal($aArgs['logLine']);
                break;
        }
    }

    protected static function addToLog4php(array $aArgs)
    {
        ValidatorModel::notEmpty($aArgs, ['traceInformations', 'loggingMethod']);
        ValidatorModel::arrayType($aArgs, ['traceInformations', 'loggingMethod']);

        $logLine = str_replace(
            [
                '%RESULT%',
                '%CODE_METIER%',
                '%WHERE%',
                '%ID%',
                '%HOW%',
                '%USER%',
                '%WHAT%',
                '%ID_MODULE%',
                '%REMOTE_IP%'
            ],
            [
                'OK',
                $aArgs['loggingMethod']['CODE_METIER'],
                $aArgs['traceInformations']['WHERE'],
                $aArgs['traceInformations']['ID'],
                $aArgs['traceInformations']['HOW'],
                $aArgs['traceInformations']['USER'],
                $aArgs['traceInformations']['WHAT'],
                $aArgs['traceInformations']['ID_MODULE'],
                $aArgs['traceInformations']['REMOTE_IP']
            ],
            $aArgs['loggingMethod']['LOG_FORMAT']
        );

        $loggerName = (empty($aArgs['isTech']) ? $aArgs['loggingMethod']['LOGGER_NAME_FUNC'] : $aArgs['loggingMethod']['LOGGER_NAME_TECH']);
        $logLine    = TextFormatModel::htmlWasher($logLine);
        $logLine    = TextFormatModel::removeAccent(['string' => $logLine]);

        LogsController::writeLog([
            'loggerName' => $loggerName,
            'logLine'    => $logLine,
            'level'      => $aArgs['traceInformations']['LEVEL']
        ]);
    }

    public static function add(array $aArgs)
    {
        $traceInformations = [
            'WHERE'     => $aArgs['tableName'],
            'ID'        => $aArgs['recordId'],
            'HOW'       => $aArgs['eventType'],
            'USER'      => $GLOBALS['login'],
            'WHAT'      => $aArgs['eventId'],
            'ID_MODULE' => $aArgs['moduleId'],
            'REMOTE_IP' => $_SERVER['REMOTE_ADDR'],
            'LEVEL'     => $aArgs['level']
        ];

        $loggingMethods = LogsController::buildLoggingMethod();

        foreach ($loggingMethods as $loggingMethod) {
            if ($loggingMethod['ACTIVATED'] == true) {
                if ($loggingMethod['ID'] == 'log4php') {
                    if (empty($loggingMethod['LOGGER_NAME_TECH'])) {
                        $loggingMethod['LOGGER_NAME_TECH'] = 'loggerTechnique';
                    }
                    if (empty($loggingMethod['LOGGER_NAME_FUNC'])) {
                        $loggingMethod['LOGGER_NAME_FUNC'] = 'loggerFonctionnel';
                    }
                    LogsController::addToLog4php([
                        'traceInformations' => $traceInformations,
                        'loggingMethod'     => $loggingMethod,
                        'isTech'            => $aArgs['isTech'],
                    ]);
                }
            }
        }
    }
}
