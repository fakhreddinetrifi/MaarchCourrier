<?php
/**
* Copyright Maarch since 2008 under licence GPLv3.
* See LICENCE.txt file at the root folder for more details.
* This file is part of Maarch software.

* @brief   load_process_emails
* @author  dev <dev@maarch.org>
* @ingroup life_cycle
*/

class IncludeFileError extends Exception
{
    public function __construct($file)
    {
        $this->file = $file;
        parent :: __construct('Include File \'$file\' is missing!', 1);
    }
}

try {
    include 'Maarch_CLITools/ArgsParser.php';
    include 'LoggerLog4php.php';
    include 'Maarch_CLITools/FileHandler.php';
    include 'Maarch_CLITools/ConsoleHandler.php';
} catch (IncludeFileError $e) {
    echo 'Maarch_CLITools required ! \n (pear.maarch.org)\n';
    exit(106);
}

// Globals variables definition
$GLOBALS['batchName'] = 'extract_data';
$GLOBALS['wb'] = '';
$totalProcessedResources = 0;
$batchDirectory = '';
$log4PhpEnabled = false;

// Open Logger
$GLOBALS['logger'] = new Logger4Php();
$GLOBALS['logger']->set_threshold_level('INFO');

if (!is_dir('logs/send_data')) {
    mkdir('logs/send_data');
}
$logFile = 'logs' . DIRECTORY_SEPARATOR . 'send_data' . DIRECTORY_SEPARATOR . date('Y-m-d_H-i-s') . '.log';

$file = new FileHandler($logFile);
$GLOBALS['logger']->add_handler($file);

// Load tools
require 'batch_tools.php';

// Defines scripts arguments
$argsparser = new ArgsParser();
// The config file
$argsparser->add_arg(
    'config_sendmail',
    array(
        'short' => 'cs',
        'long' => 'config_sendmail',
        'mandatory' => true,
        'help' => 'Config file path is mandatory.',
    )
);

$argsparser->add_arg(
    'config',
    array(
        'short' => 'c',
        'long' => 'config',
        'mandatory' => true,
        'help' => 'Config file path is mandatory.',
    )
);

// Parsing script options
try {
    $options = $argsparser->parse_args($GLOBALS['argv']);
    // If option = help then options = false and the script continues ...
    if ($options == false) {
        exit(0);
    }
} catch (MissingArgumentError $e) {
    if ($e->arg_name == 'config_sendmail') {
        $GLOBALS['logger']->write('Configuration file missing', 'ERROR', 101);
        exit(101);
    }
}
$txt = '';
foreach (array_keys($options) as $key) {
    if (isset($options[$key]) && $options[$key] == false) {
        $txt .= $key . '=false,';
    } else {
        $txt .= $key . '=' . $options[$key] . ',';
    }
}
$GLOBALS['logger']->write($txt, 'DEBUG');
$GLOBALS['configFile'] = $options['config_sendmail'];
// Loading config file
$GLOBALS['logger']->write(
    'Load xml config file:' . $GLOBALS['configFile'],
    'INFO'
);
// Tests existence of config file
if (!file_exists($GLOBALS['configFile'])) {
    $GLOBALS['logger']->write(
        'Configuration file ' . $GLOBALS['configFile']
        . ' does not exist',
        'ERROR',
        102
    );
    echo "\nConfiguration file " . $GLOBALS['configFile'] . " does not exist ! \nThe batch cannot be launched !\n\n";
    exit(102);
}

$xmlconfig = simplexml_load_file($GLOBALS['configFile']);

if ($xmlconfig == false) {
    $GLOBALS['logger']->write(
        'Error on loading config file:'
        . $GLOBALS['configFile'],
        'ERROR',
        103
    );
    exit(103);
}


// Load config
$config = $xmlconfig->CONFIG;
$lang = (string)$config->Lang;
$GLOBALS['maarchDirectory'] = $_SESSION['config']['corepath'] = (string)$config->MaarchDirectory;
$_SESSION['config']['app_id'] = (string) $config->MaarchApps;
$GLOBALS['CustomId'] = $_SESSION['custom_override_id'] = (string)$config->CustomId;
$GLOBALS['TmpDirectory'] = $GLOBALS['maarchDirectory'] . 'modules' . DIRECTORY_SEPARATOR . 'life_cycle'  . DIRECTORY_SEPARATOR . 'batch'. DIRECTORY_SEPARATOR . 'tmp';
$GLOBALS['batchDirectory'] = $GLOBALS['maarchDirectory'] . 'modules' . DIRECTORY_SEPARATOR . 'life_cycle'  . DIRECTORY_SEPARATOR . 'batch';

$notificationErrors = $xmlconfig->NOTIFICATION_ERROR;
$GLOBALS['adminmail'] = (string)$notificationErrors->adminmail;
$GLOBALS['subjectmail'] = (string)$notificationErrors->subjectmail;
$GLOBALS['bodymail'] = (string)$notificationErrors->body;

set_include_path(get_include_path() . PATH_SEPARATOR . $GLOBALS['maarchDirectory']);

//log4php params
$log4phpParams = $xmlconfig->LOG4PHP;
if ((string) $log4phpParams->enabled == 'true') {
    $GLOBALS['logger']->set_log4PhpLibrary($GLOBALS['maarchDirectory'] . 'apps/maarch_entreprise/tools/log4php/Logger.php');
    $GLOBALS['logger']->set_log4PhpLogger((string) $log4phpParams->Log4PhpLogger);
    $GLOBALS['logger']->set_log4PhpBusinessCode((string) $log4phpParams->Log4PhpBusinessCode);
    $GLOBALS['logger']->set_log4PhpConfigPath((string) $log4phpParams->Log4PhpConfigPath);
    $GLOBALS['logger']->set_log4PhpBatchName($GLOBALS['batchName']);
} else {
    echo "\n/!\ WARNING /!\ LOG4PHP is disabled ! Informations of batch process will not show !\n\n";
}


// Mailer
$mailerParams = $xmlconfig->MAILER;
$path_to_mailer = (string)$mailerParams->path_to_mailer;

//Charset
$GLOBALS['charset'] = (string)$mailerParams->charset;

try {
    Bt_myInclude(
        $GLOBALS['maarchDirectory'] . 'core' . DIRECTORY_SEPARATOR . 'class'
        . DIRECTORY_SEPARATOR . 'class_functions.php'
    );
    Bt_myInclude(
        $GLOBALS['maarchDirectory'] . 'core' . DIRECTORY_SEPARATOR . 'class'
        . DIRECTORY_SEPARATOR . 'class_db_pdo.php'
    );
    Bt_myInclude(
        $GLOBALS['maarchDirectory'] . 'core' . DIRECTORY_SEPARATOR . 'class'
        . DIRECTORY_SEPARATOR . 'class_core_tools.php'
    );
    Bt_myInclude(
        $GLOBALS['maarchDirectory'] . "modules" . DIRECTORY_SEPARATOR . "sendmail"
        . DIRECTORY_SEPARATOR . "class". DIRECTORY_SEPARATOR . "class_modules_tools.php"
    );
    Bt_myInclude(
        $GLOBALS['maarchDirectory'] . "modules" . DIRECTORY_SEPARATOR . "entities"
        . DIRECTORY_SEPARATOR . "class". DIRECTORY_SEPARATOR . "class_manage_entities.php"
    );
    Bt_myInclude(
        $GLOBALS['maarchDirectory'] . $path_to_mailer
    );
} catch (IncludeFileError $e) {
    $GLOBALS['logger']->write(
        'Problem with the php include path:' .$e .' '. get_include_path(),
        'ERROR'
    );
    exit();
}

// Controlers and objects
$dbConfig = $xmlconfig->CONFIG_BASE;
$_SESSION['config']['databaseserver']       = (string)$dbConfig->databaseserver;
$_SESSION['config']['databaseserverport']   = (string)$dbConfig->databaseserverport;
$_SESSION['config']['databaseuser']         = (string)$dbConfig->databaseuser;
$_SESSION['config']['databasepassword']     = (string)$dbConfig->databasepassword;
$_SESSION['config']['databasename']         = (string)$dbConfig->databasename;
$_SESSION['config']['databasetype']         = (string)$dbConfig->databasetype;

 $i = 0;
foreach ($xmlconfig->COLLECTION as $col) {
    $GLOBALS['collections'][$i] = array(
        'id' => (string) $col->Id,
        'table' => (string) $col->Table,
        'version_table' => (string) $col->VersionTable,
        'view' => (string) $col->View,
        'adr' => (string) $col->Adr,
        'extensions' => (string) $col->Extension
    );
    $i++;
}

$GLOBALS['configFileStat'] = $options['config'];
// Loading config file
$GLOBALS['logger']->write(
    'Load xml config file:' . $GLOBALS['configFileStat'],
    'INFO'
);
// Tests existence of config file
if (!file_exists($GLOBALS['configFileStat'])) {
    $GLOBALS['logger']->write(
        'Configuration file ' . $GLOBALS['configFileStat']
        . ' does not exist',
        'ERROR',
        102
    );
    echo "\nConfiguration file " . $GLOBALS['configFileStat'] . " does not exist ! \nThe batch cannot be launched !\n\n";
    exit(102);
}

$xmlconfigStat = simplexml_load_file($GLOBALS['configFileStat']);

if ($xmlconfigStat == false) {
    $GLOBALS['logger']->write(
        'Error on loading config file:'
        . $GLOBALS['configFileStat'],
        'ERROR',
        103
    );
    exit(103);
}
$configStat = $xmlconfigStat->CONFIG;
$GLOBALS['ExportFolder'] = (string)$configStat->ExportFolder;
$GLOBALS['MailToNotify'] = (string)$configStat->MailToNotify;

$coreTools = new core_tools();
$coreTools->load_lang($lang, $GLOBALS['maarchDirectory'], $_SESSION['config']['app_id']);

$sendmail_tools = new sendmail();
$GLOBALS['func'] = new functions();

$GLOBALS['db'] = new Database($GLOBALS['configFile']);

$GLOBALS['errorLckFile'] = $GLOBALS['batchDirectory'] . DIRECTORY_SEPARATOR
    . $GLOBALS['batchName'] .'_error.lck';
$GLOBALS['lckFile'] = $GLOBALS['batchDirectory'] . DIRECTORY_SEPARATOR
    . $GLOBALS['batchName'] . '.lck';

if (file_exists($GLOBALS['errorLckFile'])) {
    $GLOBALS['logger']->write(
        'Error persists, please solve this before launching a new batch',
        'ERROR',
        13
    );
    exit(13);
}

Bt_getWorkBatch();
