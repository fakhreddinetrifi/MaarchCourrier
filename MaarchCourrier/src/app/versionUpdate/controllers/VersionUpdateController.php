<?php

/**
 * Copyright Maarch since 2008 under licence GPLv3.
 * See LICENCE.txt file at the root folder for more details.
 * This file is part of Maarch software.
 *
 */

/**
 * @brief Version Update Controller
 * @author dev@maarch.org
 */

namespace VersionUpdate\controllers;

use Docserver\models\DocserverModel;
use Gitlab\Client;
use Group\controllers\PrivilegeController;
use Parameter\models\ParameterModel;
use Slim\Http\Request;
use Slim\Http\Response;
use SrcCore\models\CoreConfigModel;
use SrcCore\models\DatabaseModel;
use SrcCore\models\DatabasePDO;
use SrcCore\models\ValidatorModel;

class VersionUpdateController
{
    public function get(Request $request, Response $response)
    {
        if (!PrivilegeController::hasPrivilege(['privilegeId' => 'admin_update_control', 'userId' => $GLOBALS['id']])) {
            return $response->withStatus(403)->withJson(['errors' => 'Service forbidden']);
        }

        $client = Client::create('https://labs.maarch.org/api/v4/');
        try {
            $tags = $client->api('tags')->all('12');
        } catch (\Exception $e) {
            return $response->withJson(['errors' => $e->getMessage()]);
        }

        $applicationVersion = CoreConfigModel::getApplicationVersion();
        if (empty($applicationVersion)) {
            return $response->withStatus(400)->withJson(['errors' => "Can't load package.json"]);
        }

        $currentVersion = $applicationVersion;

        $versions = explode('.', $currentVersion);
        $currentVersionBranch = "{$versions[0]}.{$versions[1]}";
        $currentVersionTag = $versions[2];
        $currentVersionBranchYear = $versions[0];
        $currentVersionBranchMonth = $versions[1];

        $availableMinorVersions = [];
        $availableMajorVersions = [];

        foreach ($tags as $value) {
            if (!preg_match("/^\d{2}\.\d{2}\.\d+$/", $value['name'])) {
                continue;
            }
            $explodedValue = explode('.', $value['name']);
            $tag = $explodedValue[2];

            $pos = strpos($value['name'], $currentVersionBranch);
            if ($pos === false) {
                $year = substr($value['name'], 0, 2);
                $month = substr($value['name'], 3, 2);
                if (($year == $currentVersionBranchYear && $month > $currentVersionBranchMonth) || $year > $currentVersionBranchYear) {
                    $availableMajorVersions[] = $value['name'];
                }
            } else {
                if ($tag > $currentVersionTag) {
                    $availableMinorVersions[] = $value['name'];
                }
            }
        }

        natcasesort($availableMinorVersions);
        natcasesort($availableMajorVersions);

        if (empty($availableMinorVersions)) {
            $lastAvailableMinorVersion = null;
        } else {
            $lastAvailableMinorVersion = $availableMinorVersions[0];
        }

        if (empty($availableMajorVersions)) {
            $lastAvailableMajorVersion = null;
        } else {
            $lastAvailableMajorVersion = $availableMajorVersions[0];
        }

        $output = [];

        exec('git status --porcelain --untracked-files=no 2>&1', $output);

        return $response->withJson([
            'lastAvailableMinorVersion' => $lastAvailableMinorVersion,
            'lastAvailableMajorVersion' => $lastAvailableMajorVersion,
            'currentVersion'            => $currentVersion,
            'canUpdate'                 => empty($output),
            'diffOutput'                => $output
        ]);
    }

    /**
        * @codeCoverageIgnore
    */
    public function update(Request $request, Response $response)
    {
        if (!PrivilegeController::hasPrivilege(['privilegeId' => 'admin_update_control', 'userId' => $GLOBALS['id']])) {
            return $response->withStatus(403)->withJson(['errors' => 'Service forbidden']);
        }

        $client = Client::create('https://labs.maarch.org/api/v4/');
        try {
            $tags = $client->api('tags')->all('12');
        } catch (\Exception $e) {
            return $response->withJson(['errors' => $e->getMessage()]);
        }

        $applicationVersion = CoreConfigModel::getApplicationVersion();
        if (empty($applicationVersion)) {
            return $response->withStatus(400)->withJson(['errors' => "Can't load package.json"]);
        }

        $currentVersion = $applicationVersion;

        $versions = explode('.', $currentVersion);
        $currentVersionBranch = "{$versions[0]}.{$versions[1]}";
        $currentVersionTag = $versions[2];

        $availableMinorVersions = [];

        foreach ($tags as $value) {
            if (strpos($value['name'], $currentVersionBranch) === false) {
                continue;
            }
            $explodedValue = explode('.', $value['name']);
            $tag = $explodedValue[2];

            if ($tag > $currentVersionTag) {
                $availableMinorVersions[] = $value['name'];
            }
        }

        if (empty($availableMinorVersions)) {
            return $response->withStatus(400)->withJson(['errors' => 'No minor versions available']);
        }

        natcasesort($availableMinorVersions);

        $minorVersion = $availableMinorVersions[0];

        $output = [];
        exec('git status --porcelain --untracked-files=no 2>&1', $output);
        if (!empty($output)) {
            return $response->withStatus(400)->withJson(['errors' => 'Some files are modified. Can not update application', 'lang' => 'canNotUpdateApplication']);
        }

        $minorVersions = explode('.', $minorVersion);
        $currentVersionTag = (int)$currentVersionTag;
        $currentVersionTag++;
        $sqlFiles = [];
        while ($currentVersionTag <= (int)$minorVersions[2]) {
            if (is_file("migration/{$versions[0]}.{$versions[1]}/{$versions[0]}{$versions[1]}{$currentVersionTag}.sql")) {
                if (!is_readable("migration/{$versions[0]}.{$versions[1]}/{$versions[0]}{$versions[1]}{$currentVersionTag}.sql")) {
                    return $response->withStatus(400)->withJson(['errors' => "File migration/{$versions[0]}.{$versions[1]}/{$versions[0]}{$versions[1]}{$currentVersionTag}.sql is not readable"]);
                }
                $sqlFiles[] = "migration/{$versions[0]}.{$versions[1]}/{$versions[0]}{$versions[1]}{$currentVersionTag}.sql";
            }
            $currentVersionTag++;
        }

        $control = VersionUpdateController::executeSQLUpdate(['sqlFiles' => $sqlFiles]);
        if (!empty($control['errors'])) {
            return $response->withStatus(400)->withJson(['errors' => $control['errors']]);
        }

        $currentCustomId = CoreConfigModel::getCustomId();
        if (is_file('custom/custom.json')) {
            $jsonFile = file_get_contents('custom/custom.json');
            $jsonFile = json_decode($jsonFile, true);

            foreach ($jsonFile as $custom) {
                if ($custom['id'] != $currentCustomId && is_dir("custom/{$custom['id']}")) {
                    DatabasePDO::reset();
                    new DatabasePDO(['customId' => $custom['id']]);

                    $controlCustom = VersionUpdateController::executeSQLUpdate(['sqlFiles' => $sqlFiles]);
                    if (!empty($controlCustom['errors'])) {
                        return $response->withStatus(400)->withJson(['errors' => "Error with custom {$custom['id']} : " . $controlCustom['errors']]);
                    }
                }
            }
        }

        $output = [];
        exec('git fetch');
        exec("git checkout {$minorVersion} 2>&1", $output, $returnCode);

        $log = "Application update from {$currentVersion} to {$minorVersion}\nCheckout response {$returnCode} => " . implode(' ', $output) . "\n";
        file_put_contents("{$control['directoryPath']}/updateVersion.log", $log, FILE_APPEND);

        if ($returnCode != 0) {
            return $response->withStatus(400)->withJson(['errors' => "Application update failed. Please check updateVersion.log at {$control['directoryPath']}"]);
        }

        return $response->withStatus(204);
    }

    private static function executeSQLUpdate(array $args)
    {
        ValidatorModel::arrayType($args, ['sqlFiles']);

        $docserver = DocserverModel::getCurrentDocserver(['typeId' => 'DOC', 'collId' => 'letterbox_coll', 'select' => ['path_template']]);
        $directoryPath = explode('/', rtrim($docserver['path_template'], '/'));
        array_pop($directoryPath);
        $directoryPath = implode('/', $directoryPath);

        if (!is_dir($directoryPath . '/migration')) {
            if (!is_writable($directoryPath)) {
                return ['errors' => 'Directory path is not writable : ' . $directoryPath];
            }
            mkdir($directoryPath . '/migration', 0755, true);
        } elseif (!is_writable($directoryPath . '/migration')) {
            return ['errors' => 'Directory path is not writable : ' . $directoryPath . '/migration'];
        }

        if (!empty($args['sqlFiles'])) {
            $config = CoreConfigModel::getJsonLoaded(['path' => 'apps/maarch_entreprise/xml/config.json']);

            $actualTime = date("dmY-His");
            $tablesToSave = '';
            foreach ($args['sqlFiles'] as $sqlFile) {
                $fileContent = file_get_contents($sqlFile);
                $explodedFile = explode("\n", $fileContent);
                foreach ($explodedFile as $key => $line) {
                    if (strpos($line, '--DATABASE_BACKUP') !== false) {
                        $lineNb = $key;
                    }
                }
                if (isset($lineNb)) {
                    $explodedLine = explode('|', $explodedFile[$lineNb]);
                    array_shift($explodedLine);
                    foreach ($explodedLine as $table) {
                        if (!empty($table)) {
                            $tablesToSave .= ' -t ' . trim($table);
                        }
                    }
                }
            }

            $execReturn = exec("pg_dump --dbname=\"postgresql://{$config['database'][0]['user']}:{$config['database'][0]['password']}@{$config['database'][0]['server']}:{$config['database'][0]['port']}/{$config['database'][0]['name']}\" {$tablesToSave} -a > \"{$directoryPath}/migration/backupDB_maarchcourrier_{$actualTime}.sql\"", $output, $intReturn);
            if (!empty($execReturn)) {
                return ['errors' => 'Pg dump failed : ' . $execReturn];
            }

            foreach ($args['sqlFiles'] as $sqlFile) {
                $fileContent = file_get_contents($sqlFile);
                DatabaseModel::exec($fileContent);
            }
        }

        return ['directoryPath' => "{$directoryPath}/migration"];
    }

    public function updateSQLVersion(Request $request, Response $response)
    {
        $parameter = ParameterModel::getById(['select' => ['param_value_string'], 'id' => 'database_version']);

        $parameter = explode('.', $parameter['param_value_string']);
        $minorVersion = count($parameter) > 2 ? (int)$parameter[2] : 1;

        $applicationVersion = CoreConfigModel::getApplicationVersion();
        $versions = explode('.', $applicationVersion);
        $currentVersion = (int)$versions[2];

        $minorVersion++;
        $sqlFiles = [];
        while ($minorVersion <= $currentVersion) {
            if (is_file("migration/{$versions[0]}.{$versions[1]}/{$versions[0]}{$versions[1]}{$minorVersion}.sql")) {
                if (!is_readable("migration/{$versions[0]}.{$versions[1]}/{$versions[0]}{$versions[1]}{$minorVersion}.sql")) {
                    return $response->withStatus(400)->withJson(['errors' => "File migration/{$versions[0]}.{$versions[1]}/{$versions[0]}{$versions[1]}{$minorVersion}.sql is not readable"]);
                }
                $sqlFiles[] = "migration/{$versions[0]}.{$versions[1]}/{$versions[0]}{$versions[1]}{$minorVersion}.sql";
            }
            $minorVersion++;
        }

        if (!empty($sqlFiles)) {
            $control = VersionUpdateController::executeSQLUpdate(['sqlFiles' => $sqlFiles]);
            if (!empty($control['errors'])) {
                return $response->withStatus(400)->withJson(['errors' => $control['errors']]);
            }
            return $response->withJson(['success' => 'Database has been updated']);
        }

        return $response->withStatus(204);
    }
}
