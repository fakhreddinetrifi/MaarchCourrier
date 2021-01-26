<?php

/**
* Copyright Maarch since 2008 under licence GPLv3.
* See LICENCE.txt file at the root folder for more details.
* This file is part of Maarch software.
*
*/

/**
 * @brief Library to process the garbage_collector
 *
 * @file
 * @author  Laurent Giovannoni  <dev@maarch.org>
 * @date $date$
 * @version $Revision$
 * @ingroup life_cycle
 */

/**
* @brief Class to include the file error
*
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
    include('Maarch_CLITools/ArgsParser.php');
    include('LoggerLog4php.php');
    include('Maarch_CLITools/FileHandler.php');
    include('Maarch_CLITools/ConsoleHandler.php');
} catch (IncludeFileError $e) {
    echo 'Maarch_CLITools required ! \n (pear.maarch.org)\n';
    exit(106);
}
include('batch_tools.php');
// Globals variables definition
$state = '';
$configFile = '';
$MaarchDirectory = '';
$batchDirectory = '';
$batchName = 'process_garbage_collector';

$table = '';
$adrTable = '';
$view = '';
$steps = Array();
$docservers = Array();
$docserverSourcePath = '';
$docserverSourceFingerprint = '';
$databasetype = '';
$exitCode = 0;
$running_date = date('Y-m-d H:i:s');
$func = '';
$db = '';
$db2 = '';
$docserverControler = '';
$wb = '';
$docserversFeatures = array();
$lckFile = '';
$errorLckFile = '';
$totalProcessedResources = 0;
$dateToPurgeDEL =  '';
$dateToPurgeOBS =  '';
$log4PhpEnabled = false;
$resAlreadyDone = false;
$attachAlreadyDone = false;
$obsAlreadyDone = false;
$debug = 'true';

// Defines scripts arguments
$argsparser = new ArgsParser();
// The config file
$argsparser->add_arg(
    'config', 
    array(
        'short' => 'c',
        'long' => 'config',
        'mandatory' => true,
        'help' => 'Config file path is mandatory.',
    )
);

$argsparser->add_arg(
    'mode', 
    array(
        'short' => 'm',
        'long' => 'mode',
        'mandatory' => true,
        'help' => 'Mode (count|purge) is mandatory.',
    )
);

// The path of the log directory
$argsparser->add_arg(
    'logs', 
    array(
        'short' => 'logs',
        'long' => 'logs',
        'mandatory' => false,
        'help' => '',
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
    if ($e->arg_name == 'config') {
        echo 'Configuration file missing' . PHP_EOL;
        exit(101);
    }
    if ($e->arg_name == 'mode') {
        echo 'mode(count|purge) missinng' . PHP_EOL;
        exit(105);
    }
}
// Log management
//$GLOBALS['logger'] = new Logger();
$GLOBALS['logger'] = new Logger4Php();
$GLOBALS['logger']->set_threshold_level('DEBUG');
$console = new ConsoleHandler();
$GLOBALS['logger']->add_handler($console);
if (!empty($options['logs'])) {
    if (!is_dir($options['logs'] . '/process_garbage_collector/')) {
        mkdir($options['logs'] . '/process_garbage_collector/', 0770, true);
    }
    $logFile = $options['logs'] . '/process_garbage_collector/'
        . date('Y-m-d_H-i-s') . '.log';
} else {
    if (!is_dir('logs/process_garbage_collector/')) {
        mkdir('logs/process_garbage_collector/', 0770, true);
    }
    $logFile = 'logs/process_garbage_collector/'
        . date('Y-m-d_H-i-s') . '.log';
}
$file = new FileHandler($logFile);
$GLOBALS['logger']->add_handler($file);
$GLOBALS['logger']->write('STATE:INIT', 'INFO');
$txt = '';
foreach (array_keys($options) as $key) {
    if (isset($options[$key]) && $options[$key] == false) {
        $txt .= $key . '=false,';
    } else {
        $txt .= $key . '=' . $options[$key] . ',';
    }
}
$GLOBALS['logger']->write($txt, 'DEBUG');
$GLOBALS['configFile'] = $options['config'];
$GLOBALS['logger']->write($txt, 'INFO');
// Tests existence of config file
if (!file_exists($GLOBALS['configFile'])) {
    $GLOBALS['logger']->write('Configuration file ' . $GLOBALS['configFile'] 
                              . ' does not exist', 'ERROR', 102);
    exit(102);
}
// Loading config file
$GLOBALS['logger']->write('Load xml config file:' . $GLOBALS['configFile'], 
                          'INFO');
$xmlconfig = simplexml_load_file($GLOBALS['configFile']);
if ($xmlconfig == FALSE) {
    $GLOBALS['logger']->write('Error on loading config file:' 
                              . $GLOBALS['configFile'], 'ERROR', 103);
    exit(103);
}

$GLOBALS['mode'] = $options['mode'];
if (
       strtolower($GLOBALS['mode']) <> 'count' 
    && strtolower($GLOBALS['mode']) <> 'purge'
) {
    $GLOBALS['logger']->write('Error on mode, must be count or purge:' 
                              . $GLOBALS['mode'], 'ERROR', 105);
    exit(105);
}
// Load the config vars
$CONFIG = $xmlconfig->CONFIG;
$lang = (string) $CONFIG->Lang;
$GLOBALS['MaarchDirectory'] = (string) $CONFIG->MaarchDirectory;
$GLOBALS['batchDirectory'] = $GLOBALS['MaarchDirectory'] . 'modules/life_cycle/batch/';
$logLevel = (string) $CONFIG->LogLevel;
$GLOBALS['logger']->set_threshold_level($logLevel);
$DisplayedLogLevel = (string) $CONFIG->DisplayedLogLevel;
$GLOBALS['databasetype'] = (string) $xmlconfig->CONFIG_BASE->databasetype;
$GLOBALS['dateToPurgeDEL'] = (string) $CONFIG->dateToPurgeDEL;
$GLOBALS['dateToPurgeOBS'] = (string) $CONFIG->dateToPurgeOBS;
$GLOBALS['debug'] = (string) $CONFIG->debug;
if (empty($GLOBALS['debug'])) {
    $GLOBALS['debug'] = 'true';
}

if (empty($GLOBALS['dateToPurgeDEL']) && empty($GLOBALS['dateToPurgeOBS'])) {
    $GLOBALS['logger']->write('dateToPurgeDEL or dateToPurgeOBS must be filled', 'ERROR', 1);
    exit(105);
}

$i = 0;
set_include_path(get_include_path() . PATH_SEPARATOR 
    . $GLOBALS['MaarchDirectory']);
//log4php params
$log4phpParams = $xmlconfig->LOG4PHP;
if ((string) $log4phpParams->enabled == 'true') {
    $GLOBALS['logger']->set_log4PhpLibrary(
        $GLOBALS['MaarchDirectory'] 
            . 'apps/maarch_entreprise/tools/log4php/Logger.php'
    );
    $GLOBALS['logger']->set_log4PhpLogger((string) $log4phpParams->Log4PhpLogger);
    $GLOBALS['logger']->set_log4PhpBusinessCode((string) $log4phpParams->Log4PhpBusinessCode);
    $GLOBALS['logger']->set_log4PhpConfigPath((string) $log4phpParams->Log4PhpConfigPath);
    $GLOBALS['logger']->set_log4PhpBatchName('life_cycle_process_garbage_collector');
}

if ($logLevel == 'DEBUG') {
    error_reporting(E_ALL);
}
$GLOBALS['logger']->change_handler_log_level($file, $logLevel);
$GLOBALS['logger']->change_handler_log_level($console, $DisplayedLogLevel);
unset($xmlconfig);

// Include library
try {
    Bt_myInclude($GLOBALS['MaarchDirectory'] . 'core/class/class_functions.php');
    Bt_myInclude($GLOBALS['MaarchDirectory'] . 'core/class/class_db_pdo.php');
    Bt_myInclude($GLOBALS['MaarchDirectory'] . 'core/class/class_db.php');
    Bt_myInclude($GLOBALS['MaarchDirectory'] . 'core/class/class_core_tools.php');
    Bt_myInclude($GLOBALS['MaarchDirectory'] . 'core/core_tables.php');
    Bt_myInclude($GLOBALS['MaarchDirectory'] . 'core/class/docservers_controler.php');
    Bt_myInclude($GLOBALS['MaarchDirectory'] . 'core/docservers_tools.php');
    Bt_myInclude($GLOBALS['MaarchDirectory'] . 'core/class/docserver_types_controler.php');
} catch (IncludeFileError $e) {
    $GLOBALS['logger']->write(
        'Problem with the php include path:' 
        . get_include_path(), 'ERROR', 111
    );
    exit(111);
}

$coreTools = new core_tools();
$coreTools->load_lang($lang, $GLOBALS['MaarchDirectory'], 'maarch_entreprise');
session_start();
$_SESSION['modules_loaded'] = array();
$GLOBALS['func'] = new functions();
$GLOBALS['db'] = new Database($GLOBALS['configFile']);
$GLOBALS['db2'] = new Database($GLOBALS['configFile']);
$GLOBALS['dbLog'] = new Database($GLOBALS['configFile']);
$GLOBALS['docserverControler'] = new docservers_controler();
$GLOBALS['errorLckFile'] = $GLOBALS['batchDirectory'] . '/' 
                         . $GLOBALS['batchName'] . '_error.lck';
$GLOBALS['lckFile'] = $GLOBALS['batchDirectory'] . '/' 
                    . $GLOBALS['batchName'] . '.lck';
if (file_exists($GLOBALS['errorLckFile'])) {
    $GLOBALS['logger']->write(
        'Error persists, please solve this before launching a new batch', 
        'ERROR', 29
    );
    exit(29);
}
if (file_exists($GLOBALS['lckFile'])) {
    $GLOBALS['logger']->write(
        'An instance of the garbage_collector batch : is already in progress',
        'ERROR', 109
    );
    exit(109);
}
$semaphore = fopen($GLOBALS['lckFile'], 'a');
fwrite($semaphore, '1');
fclose($semaphore);
Bt_getWorkBatch();

$GLOBALS['wb'] = rand() . $GLOBALS['wbCompute'];
Bt_updateWorkBatch();
$GLOBALS['logger']->write('Batch number:' . $GLOBALS['wb'], 'INFO');

if (strtolower($GLOBALS['mode']) == 'purge') {
    if (strtolower($GLOBALS['debug']) == 'false') {
        $GLOBALS['logger']->write('YOUR ARE NOT IN DEBUG MODE, DATA WILL BE ERASED', 'INFO');
    } else {
        $GLOBALS['logger']->write('your are in debug mode', 'INFO');
    }
} else {
    $GLOBALS['logger']->write('your are in count mode', 'INFO');
}

