<?php

/**
* Copyright Maarch since 2008 under licence GPLv3.
* See LICENCE.txt file at the root folder for more details.
* This file is part of Maarch software.
*
*/

/**
 * @brief Retrieve signed mail from external signatory book
 * @author dev@maarch.org
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
$GLOBALS['batchName']    = 'retrieveMailsFromSignatoryBook';
$GLOBALS['wb']           = '';
$totalProcessedResources = 0;

// Load tools
include('batch_tools.php');

$options = getopt("c:", ["config:"]);
if (empty($options['c']) && empty($options['config'])) {
    print("Configuration file missing\n");
    exit(101);
} elseif (!empty($options['c']) && empty($options['config'])) {
    $options['config'] = $options['c'];
    unset($options['c']);
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

print("Load json config file:" . $GLOBALS['configFile'] . "\n");
// Tests existence of config file
if (!file_exists($GLOBALS['configFile'])) {
    print(
        "Configuration file " . $GLOBALS['configFile']
        . " does not exist\n"
    );
    exit(102);
}

$file = file_get_contents($GLOBALS['configFile']);
$file = json_decode($file, true);

if (empty($file)) {
    print("Error on loading config file:" . $GLOBALS['configFile'] . "\n");
    exit(103);
}

// Load config
$config = $file['config'];
$GLOBALS['MaarchDirectory']        = $config['maarchDirectory'];
$GLOBALS['customId']               = $config['customID'];
$GLOBALS['applicationUrl']         = $config['maarchUrl'];

$config = $file['signatureBook'];
$GLOBALS['userWS']                 = $config['userWS'];
$GLOBALS['passwordWS']             = $config['passwordWS'];
$GLOBALS['batchDirectory']         = $GLOBALS['MaarchDirectory'] . 'bin/signatureBook';
$validatedStatus                   = $config['validatedStatus'];
$validatedStatusOnlyVisa           = $config['validatedStatusOnlyVisa'];
$refusedStatus                     = $config['refusedStatus'];
$validatedStatusAnnot              = $config['validatedStatusAnnot'];
$refusedStatusAnnot                = $config['refusedStatusAnnot'];

chdir($GLOBALS['MaarchDirectory']);

set_include_path(get_include_path() . PATH_SEPARATOR . $GLOBALS['MaarchDirectory']);

try {
    Bt_myInclude($GLOBALS['MaarchDirectory'] . 'vendor/autoload.php');

    // On regarde la configuration du parapheur
    if (file_exists($GLOBALS['MaarchDirectory'] . "custom/".$GLOBALS['customId']."/modules/visa/xml/remoteSignatoryBooks.xml")) {
        $path = $GLOBALS['MaarchDirectory'] . "custom/".$GLOBALS['customId']."/modules/visa/xml/remoteSignatoryBooks.xml";
    } else {
        $path = $GLOBALS['MaarchDirectory'] . 'modules/visa/xml/remoteSignatoryBooks.xml';
    }

    if (file_exists($path)) {
        $loadedXml = simplexml_load_file($path);
        if ($loadedXml) {
            $configRemoteSignatoryBook       = [];
            $configRemoteNoteBook            = ['id' => 'maarchParapheur'];
            $configRemoteSignatoryBook['id'] = (string)$loadedXml->signatoryBookEnabled;
            foreach ($loadedXml->signatoryBook as $value) {
                if ($value->id == $configRemoteSignatoryBook['id']) {
                    $configRemoteSignatoryBook['data'] = (array)$value;
                }
                if ($value->id == $configRemoteNoteBook['id']) {
                    $configRemoteNoteBook['data'] = (array)$value;
                }
            }
        } else {
            Bt_writeLog(['level' => 'ERROR', 'message' => $path . ' can not be loaded']);
            exit(102);
        }
    } else {
        Bt_writeLog(['level' => 'ERROR', 'message' => $path . ' does not exist']);
        exit(102);
    }

    if (empty($configRemoteSignatoryBook)) {
        Bt_writeLog(['level' => 'ERROR', 'message' => 'no signatory book enabled']);
        exit(102);
    }

    // On inclut la classe du parapheur activé
    if (!in_array($configRemoteSignatoryBook['id'], ['maarchParapheur', 'xParaph', 'fastParapheur', 'iParapheur', 'ixbus'])) {
        Bt_writeLog(['level' => 'ERROR', 'message' => 'No class detected']);
        exit(102);
    }
} catch (IncludeFileError $e) {
    Bt_writeLog(['level' => 'ERROR', 'message' => 'Problem with the php include path:' .$e .' '. get_include_path()]);
    exit();
}

\SrcCore\models\DatabasePDO::reset();
new \SrcCore\models\DatabasePDO(['customId' => $GLOBALS['customId']]);

$GLOBALS['errorLckFile'] = $GLOBALS['batchDirectory'] . DIRECTORY_SEPARATOR . $GLOBALS['batchName'] .'_error.lck';
$GLOBALS['lckFile']      = $GLOBALS['batchDirectory'] . DIRECTORY_SEPARATOR . $GLOBALS['batchName'] . '.lck';

if (file_exists($GLOBALS['errorLckFile'])) {
    Bt_writeLog(['level' => 'ERROR', 'message' => 'Error persists, please solve this before launching a new batch']);
    exit(13);
}

Bt_getWorkBatch();

Bt_writeLog(['level' => 'INFO', 'message' => 'Retrieve attachments sent to remote signatory book']);

$attachments = \Attachment\models\AttachmentModel::get([
    'select' => ['res_id', 'external_id->>\'signatureBookId\' as external_id', 'external_id->>\'xparaphDepot\' as xparaphdepot', 'format', 'res_id_master', 'title', 'identifier', 'attachment_type', 'recipient_id', 'recipient_type', 'typist', 'origin_id', 'relation'],
    'where' => ['status = ?', 'external_id->>\'signatureBookId\' IS NOT NULL', 'external_id->>\'signatureBookId\' <> \'\''],
    'data'  => ['FRZ']
]);
    
$idsToRetrieve = ['noVersion' => [], 'resLetterbox' => []];

foreach ($attachments as $value) {
    $idsToRetrieve['noVersion'][$value['res_id']] = $value;
}

// On récupère les pj signés dans le parapheur distant
Bt_writeLog(['level' => 'INFO', 'message' => 'Retrieve signed/annotated documents from remote signatory book']);
if ($configRemoteSignatoryBook['id'] == 'ixbus') {
    $retrievedMails = \ExternalSignatoryBook\controllers\IxbusController::retrieveSignedMails(['config' => $configRemoteSignatoryBook, 'idsToRetrieve' => $idsToRetrieve, 'version' => 'noVersion']);
} elseif ($configRemoteSignatoryBook['id'] == 'iParapheur') {
    $retrievedMails = \ExternalSignatoryBook\controllers\IParapheurController::retrieveSignedMails(['config' => $configRemoteSignatoryBook, 'idsToRetrieve' => $idsToRetrieve, 'version' => 'noVersion']);
} elseif ($configRemoteSignatoryBook['id'] == 'fastParapheur') {
    $retrievedMails = \ExternalSignatoryBook\controllers\FastParapheurController::retrieveSignedMails(['config' => $configRemoteSignatoryBook, 'idsToRetrieve' => $idsToRetrieve, 'version' => 'noVersion']);
} elseif ($configRemoteSignatoryBook['id'] == 'maarchParapheur') {
    $retrievedMails = \ExternalSignatoryBook\controllers\MaarchParapheurController::retrieveSignedMails(['config' => $configRemoteSignatoryBook, 'idsToRetrieve' => $idsToRetrieve, 'version' => 'noVersion']);
} elseif ($configRemoteSignatoryBook['id'] == 'xParaph') {
    $retrievedMails = \ExternalSignatoryBook\controllers\XParaphController::retrieveSignedMails(['config' => $configRemoteSignatoryBook, 'idsToRetrieve' => $idsToRetrieve, 'version' => 'noVersion']);
}

Bt_writeLog(['level' => 'INFO', 'message' => 'Retrieve mails sent to remote signatory book']);
$resources = \Resource\models\ResModel::get([
    'select' => ['res_id', 'external_id->>\'signatureBookId\' as external_id', 'subject', 'typist', 'version'],
    'where' => ['external_id->>\'signatureBookId\' IS NOT NULL', 'external_id->>\'signatureBookId\' <> \'\'']
]);

foreach ($resources as $value) {
    $idsToRetrieve['resLetterbox'][$value['res_id']] = $value;
}

if (!empty($idsToRetrieve['resLetterbox'])) {
    if ($configRemoteSignatoryBook['id'] == 'maarchParapheur') {
        $retrievedLetterboxMails = \ExternalSignatoryBook\controllers\MaarchParapheurController::retrieveSignedMails(['config' => $configRemoteNoteBook, 'idsToRetrieve' => $idsToRetrieve, 'version' => 'resLetterbox']);
    } elseif ($configRemoteSignatoryBook['id'] == 'fastParapheur') {
        $retrievedLetterboxMails = \ExternalSignatoryBook\controllers\FastParapheurController::retrieveSignedMails(['config' => $configRemoteSignatoryBook, 'idsToRetrieve' => $idsToRetrieve, 'version' => 'resLetterbox']);
    } elseif ($configRemoteSignatoryBook['id'] == 'iParapheur') {
        $retrievedLetterboxMails = \ExternalSignatoryBook\controllers\IParapheurController::retrieveSignedMails(['config' => $configRemoteSignatoryBook, 'idsToRetrieve' => $idsToRetrieve, 'version' => 'resLetterbox']);
    } elseif ($configRemoteSignatoryBook['id'] == 'ixbus') {
        $retrievedLetterboxMails = \ExternalSignatoryBook\controllers\IxbusController::retrieveSignedMails(['config' => $configRemoteSignatoryBook, 'idsToRetrieve' => $idsToRetrieve, 'version' => 'resLetterbox']);
    }
    $retrievedMails['resLetterbox'] = $retrievedLetterboxMails['resLetterbox'];
}

if (!empty($retrievedMails['error'])) {
    Bt_writeLog(['level' => 'ERROR', 'message' => $retrievedMails['error']]);
    exit;
}

// On dégele les pj et on créé une nouvelle ligne si le document a été signé
if (!empty($retrievedMails['noVersion'])){
    Bt_writeLog(['level' => 'INFO', 'message' => '------------------ TRACE "Attachments is Not Empty"']);
//    foreach ($retrievedMails['noVersion'] as $key => $v) {
//        Bt_writeLog(['level' => 'INFO', 'message' => '------------------ TRACE "KEYS == "' . $key]);
//        foreach ($v as $s => $k) {
//            Bt_writeLog(['level' => 'INFO', 'message' => '------------------ TRACE "KEY == " ' .$s . ' "VALUE == " ' . $k]);
//        }
//    }
}else{
    Bt_writeLog(['level' => 'INFO', 'message' => '------------------ TRACE "Attachments is Empty"']);
}
$nbMailsRetrieved = 0;
foreach ($retrievedMails['noVersion'] as $resId => $value) {
    Bt_writeLog(['level' => 'INFO', 'message' => 'Update res_attachments : ' . $resId . '. ExternalId : ' . $value['external_id']]);

    if (!empty($value['log'])) {
        Bt_writeLog(['level' => 'INFO', 'message' => 'Create log Attachment']);
        Bt_createAttachment([
            'resIdMaster'       => $value['res_id_master'],
            'title'             => '[xParaph Log] ' . $value['title'],
            'chrono'            => $value['identifier'],
            'recipientId'       => $value['recipient_id'],
            'recipientType'     => $value['recipient_type'],
            'typist'            => $value['typist'],
            'format'            => 'xml',
            'type'              => $value['attachment_type'],
            'inSignatureBook'   => false,
            'encodedFile'       => $value['log'],
            'status'            => 'TRA'
        ]);
    }
    $additionalHistoryInfo = '';
    if (!empty($value['workflowInfo'])) {
        $additionalHistoryInfo =  ' : ' . $value['workflowInfo'];
    }

    if ($value['status'] == 'validated') {
        if (!empty($value['encodedFile'])) {
            \SrcCore\models\DatabaseModel::delete([
                'table' => 'res_attachments',
                'where' => ['res_id_master = ?', 'status = ?', 'relation = ?', 'origin = ?'],
                'data'  => [$value['res_id_master'], 'SIGN', $value['relation'], $value['res_id'] . ',res_attachments']
            ]);

            Bt_writeLog(['level' => 'INFO', 'message' => 'Create validated Attachment']);
            Bt_createAttachment([
                'resIdMaster'     => $value['res_id_master'],
                'title'           => $value['title'],
                'chrono'          => $value['identifier'],
                'recipientId'     => $value['recipient_id'],
                'recipientType'   => $value['recipient_type'],
                'typist'          => $value['typist'],
                'format'          => $value['format'],
                'type'            => 'signed_response',
                'status'          => 'TRA',
                'encodedFile'     => $value['encodedFile'],
                'inSignatureBook' => true,
                'originId'        => $resId,
                'signatory_user_serial_id' => $value['signatory_user_serial_id'] ?? null
            ]);
        }

        Bt_writeLog(['level' => 'INFO', 'message' => 'Document validated']);
        \Attachment\models\AttachmentModel::update([
            'set'     => ['status' => 'SIGN', 'in_signature_book' => 'false'],
            'postSet' => ['external_id' => "external_id - 'signatureBookId'"],
            'where'   => ['res_id = ?'],
            'data'    => [$resId]
        ]);
        if (!empty($value['onlyVisa']) && $value['onlyVisa']) {
            $status = $validatedStatusOnlyVisa;
        } else {
            $status = $validatedStatus;
        }
        Bt_validatedMail(['status' => $status, 'resId' => $value['res_id_master']]);

        $historyInfo = 'La signature de la pièce jointe '.$resId.' (res_attachments) a été validée dans le parapheur externe' . $additionalHistoryInfo;
    } elseif ($value['status'] == 'refused') {
        if (!empty($value['encodedFile'])) {
            Bt_writeLog(['level' => 'INFO', 'message' => 'Create refused Attachment']);
            Bt_createAttachment([
                'resIdMaster'     => $value['res_id_master'],
                'title'           => '[REFUSE] ' . $value['title'],
                'chrono'          => $value['identifier'],
                'recipientId'     => $value['recipient_id'],
                'recipientType'   => $value['recipient_type'],
                'typist'          => $value['typist'],
                'format'          => $value['format'],
                'type'            => $value['attachment_type'],
                'status'          => 'A_TRA',
                'encodedFile'     => $value['encodedFile'],
                'inSignatureBook' => false
            ]);
        }
        Bt_writeLog(['level' => 'INFO', 'message' => 'Document refused']);
        \Entity\models\ListInstanceModel::update([
            'set' => ['process_date' => null],
            'where' => ['res_id = ?', 'difflist_type = ?'],
            'data' => [$value['res_id_master'], 'VISA_CIRCUIT']
        ]);
        \Attachment\models\AttachmentModel::update([
            'set'     => ['status' => 'A_TRA'],
            'postSet' => ['external_id' => "external_id - 'signatureBookId'"],
            'where'   => ['res_id = ?'],
            'data'    => [$resId]
        ]);

        \Resource\models\ResModel::update([
            'set' => ['status' => $refusedStatus],
            'where' => ['res_id = ?'],
            'data' => [$value['res_id_master']]
        ]);
    
        $historyInfo = 'La signature de la pièce jointe '.$resId.' (res_attachments) a été refusée dans le parapheur externe' . $additionalHistoryInfo;
    }
    if (in_array($value['status'], ['validated', 'refused'])) {
        Bt_createNote(['creatorId' => $value['noteCreatorId'], 'creatorName' => $value['noteCreatorName'], 'content' => $value['noteContent'], 'resId' => $value['res_id_master']]);
        Bt_history([
            'table_name' => 'res_attachments',
            'record_id'  => $resId,
            'info'       => $historyInfo,
            'event_type' => 'UP',
            'event_id'   => 'attachup'
        ]);
    
        Bt_history([
            'table_name' => 'res_letterbox',
            'record_id'  => $value['res_id_master'],
            'info'       => $historyInfo,
            'event_type' => 'ACTION#1',
            'event_id'   => '1'
        ]);
        $nbMailsRetrieved++;

    }
}




if (!empty($retrievedMails['resLetterbox'])){
    Bt_writeLog(['level' => 'INFO', 'message' => '------------------ TRACE "Main document is Not Empty"']);
//    foreach ($retrievedMails['resLetterbox'] as $key => $v) {
//        foreach ($v as $s => $k) {
//            Bt_writeLog(['level' => 'INFO', 'message' => '------------------ TRACE "KEY == " ' .$s . ' "VALUE == " ' . $k]);
//        }
//    }
}else{
    Bt_writeLog(['level' => 'INFO', 'message' => '------------------ TRACE "Main document is Empty"']);
}

$source = 'fr';
$target = 'en';
$text = 'buenos días';

//$result = \Statickidz\GoogleTranslate::translate($source, $target, $text);
//
//Bt_writeLog(['level' => 'INFO', 'message' => '----TRANSLATE--- '.$result]);

foreach ($retrievedMails['resLetterbox'] as $resId => $value) {
    Bt_writeLog(['level' => 'INFO', 'message' => 'Update res_letterbox : ' . $resId . '. SignatoryBookId : ' . $value['external_id']]);

    if (!empty($value['encodedFile'])) {
        Bt_writeLog(['level' => 'INFO', 'message' => 'Create document in res_letterbox']);
        if ($value['status'] =='validated') {
            $typeToDelete = ['SIGN', 'TNL'];
        } else {
            $typeToDelete = ['NOTE'];
        }
        \SrcCore\models\DatabaseModel::delete([
            'table' => 'adr_letterbox',
            'where' => ['res_id = ?', 'type in (?)', 'version = ?'],
            'data'  => [$resId, $typeToDelete, $value['version']]
        ]);

        $storeResult = \Docserver\controllers\DocserverController::storeResourceOnDocServer([
            'collId'          => 'letterbox_coll',
            'docserverTypeId' => 'DOC',
            'encodedResource' => $value['encodedFile'],
            'format'          => 'pdf'
        ]);
        \SrcCore\models\DatabaseModel::insert([
            'table'         => 'adr_letterbox',
            'columnsValues' => [
                'res_id'       => $resId,
                'type'         => in_array($value['status'], ['refused', 'refusedNote', 'validatedNote']) ? 'NOTE' : 'SIGN',
                'docserver_id' => $storeResult['docserver_id'],
                'path'         => $storeResult['destination_dir'],
                'filename'     => $storeResult['file_destination_name'],
                'version'      => $value['version'],
                'fingerprint'  => empty($storeResult['fingerPrint']) ? null : $storeResult['fingerPrint']
            ]
        ]);
    }
    if (in_array($value['status'], ['validatedNote', 'validated', 'refusedNote', 'refused'])) {
        $additionalHistoryInfo = '';
        if (!empty($value['workflowInfo'])) {
            $additionalHistoryInfo =  ' : ' . $value['workflowInfo'];
        }
        if (in_array($value['status'], ['validatedNote', 'validated'])) {
            Bt_writeLog(['level' => 'INFO', 'message' => '--------------- TRACE "validated"']);
            $status = $validatedStatus;
            if ($value['status'] == 'validatedNote') {
                Bt_writeLog(['level' => 'INFO', 'message' => '--------------- TRACE "validatedNote"']);
                $status = $validatedStatusAnnot;
            }





            $history = \Statickidz\GoogleTranslate::translate($source, $target, 'Le document '.$resId.' (res_letterbox) a été validé dans le parapheur externe' . $additionalHistoryInfo);

        }elseif (in_array($value['status'], ['refusedNote', 'refused'])) {
            Bt_writeLog(['level' => 'INFO', 'message' => 'Document refused']);
            $status = $refusedStatus;
            if ($value['status'] == 'refusedNote') {
                $status = $refusedStatusAnnot;
            }
            $history = 'Le document '.$resId.' (res_letterbox) a été refusé dans le parapheur externe' . $additionalHistoryInfo;
        }
        Bt_history([
            'table_name' => 'res_letterbox',
            'record_id'  => $resId,
            'info'       => $history,
            'event_type' => 'ACTION#1',
            'event_id'   => '1'
        ]);
        Bt_createNote(['creatorId' => $value['noteCreatorId'], 'creatorName' => $value['noteCreatorName'], 'content' => $value['noteContent'], 'resId' => $resId]);
        Bt_writeLog(['level' => 'INFO', 'message' => '-------------- TRACE: "STATUS " == ' .$status]);
        \Resource\models\ResModel::update([
            'set'     => ['status' => $status],
            'postSet' => ['external_id' => "external_id - 'signatureBookId'"],
            'where'   => ['res_id = ?'],
            'data'    => [$resId]
        ]);
        $nbMailsRetrieved++;
    }
}

Bt_writeLog(['level' => 'INFO', 'message' => 'End of process']);
Bt_writeLog(['level' => 'INFO', 'message' => $nbMailsRetrieved.' document(s) retrieved']);

Bt_logInDataBase($nbMailsRetrieved, $err, $nbMailsRetrieved.' mail(s) retrieved from signatory book');
Bt_updateWorkBatch();

exit($GLOBALS['exitCode']);
