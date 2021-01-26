<?php

/*
* Copyright Maarch since 2008 under licence GPLv3.
* See LICENCE.txt file at the root folder for more details.
* This file is part of Maarch software.
*/

/**
* @brief API to manage docservers
*
* @file
* @author Laurent Giovannoni
* @date $date$
* @version $Revision$
* @ingroup core
*/

//Loads the required class
try {
    require_once 'core/class/docservers.php';
    require_once 'core/class/docservers_controler.php';
    require_once 'core/core_tables.php';
} catch (Exception $e) {
    functions::xecho($e->getMessage()) . ' // ';
}

/**
 * copy doc in a docserver.
 * @param   string $sourceFilePath collection resource
 * @param   array $infoFileNameInTargetDocserver infos of the doc to store,
 *          contains : subdirectory path and new filename
 * @param   string $docserverSourceFingerprint
 * @return  array of docserver data for res_x else return error
 */
function Ds_copyOnDocserver(
    $sourceFilePath,
    $infoFileNameInTargetDocserver,
    $docserverSourceFingerprint = 'NONE'
) {
    error_reporting(0);
    $destinationDir = $infoFileNameInTargetDocserver['destinationDir'];
    $fileDestinationName =
        $infoFileNameInTargetDocserver['fileDestinationName'];
    $sourceFilePath = str_replace('\\\\', '\\', $sourceFilePath);
    if (file_exists($destinationDir . $fileDestinationName)) {
        $storeInfos = array('error' => _FILE_ALREADY_EXISTS);
        return $storeInfos;
    }
    if (!is_dir($destinationDir)) {
        mkdir($destinationDir, 0770, true);
        Ds_setRights($destinationDir);
    }
    $cp = copy($sourceFilePath, $destinationDir . $fileDestinationName);
    Ds_setRights($destinationDir . $fileDestinationName);
    if ($cp == false) {
        $storeInfos = array('error' => _DOCSERVER_COPY_ERROR);
        return $storeInfos;
    }
    $fingerprintControl = array();
    $fingerprintControl = Ds_controlFingerprint(
        $sourceFilePath,
        $destinationDir . $fileDestinationName,
        $docserverSourceFingerprint
    );
    if ($fingerprintControl['status'] == 'ko') {
        $storeInfos = array('error' => $fingerprintControl['error']);
        return $storeInfos;
    }

    if (isset($GLOBALS['currentStep'])) {
        $destinationDir = str_replace(
            $GLOBALS['docservers'][$GLOBALS['currentStep']]['docserver']
            ['path_template'],
            '',
            $destinationDir
        );
    }
    $destinationDir = str_replace(
        DIRECTORY_SEPARATOR,
        '#',
        $destinationDir
    );
    $storeInfos = array(
        'destinationDir' => $destinationDir,
        'fileDestinationName' => $fileDestinationName,
        'fileSize' => filesize($sourceFilePath),
    );
    if (isset($GLOBALS['TmpDirectory']) && $GLOBALS['TmpDirectory'] <> '') {
        Ds_washTmp($GLOBALS['TmpDirectory'], true);
    }
    return $storeInfos;
}

/**
 * Compute the path in the docserver for a batch
 * @param $docServer docservers path
 * @return array Contains 2 items : subdirectory path and error
 */
function Ds_createPathOnDocServer($docServer)
{
    error_reporting(0);
    umask(0022);
    if (!is_dir($docServer . date('Y') . DIRECTORY_SEPARATOR)) {
        mkdir($docServer . date('Y') . DIRECTORY_SEPARATOR, 0770);
        Ds_setRights($docServer . date('Y') . DIRECTORY_SEPARATOR);
    }
    if (!is_dir(
        $docServer . date('Y') . DIRECTORY_SEPARATOR.date('m')
        . DIRECTORY_SEPARATOR
    )
    ) {
        mkdir(
            $docServer . date('Y') . DIRECTORY_SEPARATOR.date('m')
            . DIRECTORY_SEPARATOR,
            0770
        );
        Ds_setRights(
            $docServer . date('Y') . DIRECTORY_SEPARATOR.date('m')
            . DIRECTORY_SEPARATOR
        );
    }
    if (isset($GLOBALS['wb']) && $GLOBALS['wb'] <> '') {
        $path = $docServer . date('Y') . DIRECTORY_SEPARATOR.date('m')
              . DIRECTORY_SEPARATOR . 'BATCH' . DIRECTORY_SEPARATOR
              . $GLOBALS['wb'] . DIRECTORY_SEPARATOR;
        if (!is_dir($path)) {
            mkdir($path, 0770, true);
            Ds_setRights($path);
        } else {
            return array(
                'destinationDir' => $path,
                'error' => 'Folder alreay exists, workbatch already exist:'
                . $path,
            );
        }
    } else {
        $path = $docServer . date('Y') . DIRECTORY_SEPARATOR.date('m')
              . DIRECTORY_SEPARATOR;
    }
    return array(
        'destinationDir' => $path,
        'error' => '',
    );
}

/**
 * Compute the fingerprint of a resource
 * @param   string $path path of the resource
 * @param   string $fingerprintMode (md5, sha512, ...)
 * @return  string the fingerprint
 */
function Ds_doFingerprint($path, $fingerprintMode)
{
    if ($fingerprintMode == 'NONE' || $fingerprintMode == '') {
        return '0';
    } else {
        return hash_file(strtolower($fingerprintMode), $path);
    }
}

 /**
 * Control fingerprint between two resources
 * @param   string $pathInit path of the resource 1
 * @param   string $pathTarget path of the resource 2
 * @param   string $fingerprintMode (md5, sha512, ...)
 * @return  array ok or ko with error
 */
function Ds_controlFingerprint(
    $pathInit,
    $pathTarget,
    $fingerprintMode = 'NONE'
) {
    $result = array();
    if (Ds_doFingerprint(
        $pathInit,
        $fingerprintMode
    ) <> Ds_doFingerprint($pathTarget, $fingerprintMode)
    ) {
        $result = array(
            'status' => 'ko',
            'error' => _PB_WITH_FINGERPRINT_OF_DOCUMENT . ' ' . $pathInit
            . ' '. _AND . ' ' . $pathTarget,
        );
    } else {
        $result = array(
            'status' => 'ok',
            'error' => '',
        );
    }
    return $result;
}

 /**
 * Set Rights on resources
 * @param   string $dest path of the resource
 * @return  nothing
 */
function Ds_setRights($dest)
{
    if (DIRECTORY_SEPARATOR == '/'
        && (isset($GLOBALS['apacheUserAndGroup'])
        && $GLOBALS['apacheUserAndGroup'] <> '')
    ) {
        exec(
            'chown '
            . escapeshellarg($GLOBALS['apacheUserAndGroup']) . ' '
            . escapeshellarg($dest)
        );
    }
    umask(0022);
    chmod($dest, 0770);
}

/**
* get the mime type of a file with a path
* @param $filePath path of the file
* @return string of the mime type
*/
function Ds_getMimeType($filePath)
{
    return mime_content_type($filePath);
}

/**
 * del tmp files
 * @param   $dir dir to wash
 * @param   $contentOnly boolean true if only the content
 * @return  boolean
 */
function Ds_washTmp($dir, $contentOnly = false)
{
    if (is_dir($dir)) {
        $objects = scandir($dir);
        foreach ($objects as $object) {
            if ($object != '.' && $object != '..') {
                if (
                    filetype($dir . DIRECTORY_SEPARATOR . $object) == 'dir'
                ) {
                    Ds_washTmp($dir . DIRECTORY_SEPARATOR . $object);
                } else {
                    unlink($dir . DIRECTORY_SEPARATOR . $object);
                }
            }
        }
        reset($objects);
        if (!$contentOnly) {
            rmdir($dir);
        }
    }
}

/**
 * Check the mime type of a file with the extension config file
* Return array with the status of the check and the mime type of the file
* @param  string $filePath
* @param  array
*/
function Ds_isFileTypeAllowed($filePath, $extDefault = '')
{
    $mimeType = Ds_getMimeType(
        $filePath
    );
    $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
    if ($ext == '' || $ext == 'tmp') {
        $ext = $extDefault;
    }
    if ($ext == 'html' && $mimeType == "text/plain") {
        $arrayReturn = array(
            'status' => true,
            'mime_type' => "text/html",
        );
        return $arrayReturn;
    }
    if (file_exists($_SESSION['config']['corepath'] . 'custom'
        . DIRECTORY_SEPARATOR.$_SESSION['custom_override_id']
        . DIRECTORY_SEPARATOR
        . 'apps' . DIRECTORY_SEPARATOR . $_SESSION['config']['app_id']
        . DIRECTORY_SEPARATOR . 'xml' . DIRECTORY_SEPARATOR
        . 'extensions.xml')
    ) {
        $path = $_SESSION['config']['corepath'] . 'custom'
        . DIRECTORY_SEPARATOR . $_SESSION['custom_override_id']
        . DIRECTORY_SEPARATOR . 'apps' . DIRECTORY_SEPARATOR
        . $_SESSION['config']['app_id'] . DIRECTORY_SEPARATOR . 'xml'
        . DIRECTORY_SEPARATOR . 'extensions.xml';
    } else {
        $path = $_SESSION['config']['corepath'] . 'apps'
        . DIRECTORY_SEPARATOR . $_SESSION['config']['app_id']
        . DIRECTORY_SEPARATOR . 'xml' . DIRECTORY_SEPARATOR . 'extensions.xml';
    }
    $xmlconfig = simplexml_load_file($path);
    $ext_list = array();
    $i = 0;
    foreach ($xmlconfig->FORMAT as $FORMAT) {
        $ext_list[$i] = array(
            'name' => (string) $FORMAT->name,
            'mime' => (string) $FORMAT->mime
        );
        $i++;
    }
    $type_state = false;
    for ($i=0; $i<count($ext_list); $i++) {
        if ($ext_list[$i]['mime'] == $mimeType
            && strtolower($ext_list[$i]['name']) == $ext
        ) {
            $type_state = true;
            break;
        }
    }
    $arrayReturn = array(
        'status' => $type_state,
        'mime_type' => $mimeType,
    );
    return $arrayReturn;
}
