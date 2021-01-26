<?php

/**
 * Copyright Maarch since 2008 under licence GPLv3.
 * See LICENCE.txt file at the root folder for more details.
 * This file is part of Maarch software.
 */

/**
 * @brief process the event stack
 *
 * @file
 * @author  Cyril Vazquez  <dev@maarch.org>
 * @date $date$
 * @version $Revision$
 * @ingroup notification
 */

/**
* @brief  Class to include the file error
*
*/
class IncludeFileError extends Exception
{
    public function __construct($file)
    {
        $this->file = $file;
        parent :: __construct('Include File \'$file\' is missing!', 1);
    }
}

// Globals variables definition
$GLOBALS['batchName']    = 'process_event_stack';
$GLOBALS['wb']           = '';
$totalProcessedResources = 0;

// Load tools
include('batch_tools.php');

$options = getopt("c:n:", ["config:", "notif:"]);
if (empty($options['c']) && empty($options['config'])) {
    print("Configuration file missing\n");
    exit(101);
} elseif (!empty($options['c']) && empty($options['config'])) {
    $options['config'] = $options['c'];
    unset($options['c']);
}
if (empty($options['n']) && empty($options['notif'])) {
    print("Notification id missing\n");
    exit(102);
} elseif (!empty($options['n']) && empty($options['notif'])) {
    $options['notif'] = $options['n'];
    unset($options['n']);
}

$txt = '';
foreach (array_keys($options) as $key) {
    if (isset($options[$key]) && $options[$key] == false) {
        $txt .= $key . '=false,';
    } else {
        $txt .= $key . '=' . $options[$key] . ',';
    }
}
print($txt . "\n");
$GLOBALS['configFile'] = $options['config'];
$notificationId = $options['notif'];

print("Load json config file:" . $GLOBALS['configFile'] . "\n");

// Tests existence of config file
if (!file_exists($GLOBALS['configFile'])) {
    print(
        "Configuration file " . $GLOBALS['configFile']
        . " does not exist\n"
    );
    exit(102);
}
// Loading config file
print("Load json config file:" . $GLOBALS['configFile'] . "\n");
$file = file_get_contents($GLOBALS['configFile']);
$file = json_decode($file, true);

if (empty($file)) {
    print("Error on loading config file:" . $GLOBALS['configFile'] . "\n");
    exit(103);
}

// Load config
$config = $file['config'];
$maarchDirectory        = $config['maarchDirectory'];
$customID               = $config['customID'];
$maarchUrl         = $config['maarchUrl'];

$customIDPath    = '';

if ($customID <> '') {
    $customIDPath = $customID . '_';
}
chdir($maarchDirectory);

$GLOBALS['customId']  = $customID;
$GLOBALS['batchDirectory'] = $maarchDirectory . 'bin'
                           . DIRECTORY_SEPARATOR . 'notification';

set_include_path(get_include_path() . PATH_SEPARATOR . $maarchDirectory);

try {
    Bt_myInclude('vendor/autoload.php');
} catch (IncludeFileError $e) {
    Bt_writeLog(['level' => 'ERROR', 'message' => 'Problem with the php include path:' .$e .' '. get_include_path()]);
    exit();
}

\SrcCore\models\DatabasePDO::reset();
new \SrcCore\models\DatabasePDO(['customId' => $customID]);

$GLOBALS['errorLckFile'] = $GLOBALS['batchDirectory'] . DIRECTORY_SEPARATOR
                         . $customIDPath . $GLOBALS['batchName'] . '_error.lck';
$GLOBALS['lckFile'] = $GLOBALS['batchDirectory'] . DIRECTORY_SEPARATOR
                    . $customIDPath . $GLOBALS['batchName'] . '.lck';
                    
if (file_exists($GLOBALS['errorLckFile'])) {
    Bt_writeLog(['level' => 'ERROR', 'message' => 'Error persists, please solve this before launching a new batch']);
    exit(13);
}

Bt_getWorkBatch();
