<?php

/**
* Copyright Maarch since 2008 under licence GPLv3.
* See LICENCE.txt file at the root folder for more details.
* This file is part of Maarch software.
*
*/

/**
 * @brief Batch to process garbage_collector
 *
 * @file
 * @author  Laurent Giovannoni  <dev@maarch.org>
 * @date $date$
 * @version $Revision$
 * @ingroup life_cycle
 */

/*****************************************************************************
WARNING : THIS BATCH ERASE RESOURCES IN DATABASE AND IN DOCSERVERS 
Please note this batch deletes resources in the database 
and storage spaces (docservers). 
You need to run only if it is set -> Make especially careful to 
define the where clause.
FOR THE CASE OF AIP : to be used only if the AIP are single resources.
*****************************************************************************/

/**
 * *****   LIGHT PROBLEMS without an error semaphore
 *  101 : Configuration file missing
 *  102 : Configuration file does not exist
 *  103 : Error on loading config file
 *  104 : SQL Query Error
 *  105 : a parameter is missing
 *  106 : Maarch_CLITools is missing
 *  107 : Stack empty for the request
 *  108 : There are still documents to be processed
 *  109 : An instance of the batch for the required policy and cyle is already
 *        in progress
 *  111 : Problem with the php include path
 *  112 : AIP not able to be purged
 *  113 : Security problem with where clause
 * ****   HEAVY PROBLEMS with an error semaphore
 *  13  : Docserver not found
 */

date_default_timezone_set('Europe/Paris');
try {
    include('load_process_garbage_collector.php');
} catch (IncludeFileError $e) {
    echo "Maarch_CLITools required ! \n (pear.maarch.org)\n";
    exit(106);
}

/******************************************************************************/
/* beginning */

$GLOBALS['state'] = "SELECT_RES";
$resourcesArrayGLOBAL = array();
$resourcesArray = array();

while ($GLOBALS['state'] <> "END") {
    if (isset($GLOBALS['logger'])) {
        $GLOBALS['logger']->write("STATE:" . $GLOBALS['state'], 'DEBUG');
    }
    switch($GLOBALS['state']) {
        /**********************************************************************/
        /*                          SELECT_RES                                */
        /*                                                                    */
        /**********************************************************************/
        case 'SELECT_RES' :
            if (isset($GLOBALS['dateToPurgeDEL']) && !empty($GLOBALS['dateToPurgeDEL'])) {
                $where_clause = "STATUS = 'DEL' and creation_date <= '" . $GLOBALS['dateToPurgeDEL'] . "'";
                if ($GLOBALS['resAlreadyDone']) {
                    echo 'attach turn' . PHP_EOL;
                    //SECOND SELECT THE ATTACH IN DEL STATUS
                    $GLOBALS['table'] = 'res_attachments';
                    $GLOBALS['versionTable'] = '';
                    $GLOBALS['adrTable'] = 'adr_attachments';
                    $GLOBALS['attachAlreadyDone'] = true;
                    $GLOBALS['dateToPurgeDEL'] = '';
                } else {
                    echo 'res turn' . PHP_EOL;
                    //FIRST SELECT THE DOC IN DEL STATUS
                    $GLOBALS['table'] = 'res_letterbox';
                    $GLOBALS['versionTable'] = '';
                    $GLOBALS['adrTable'] = 'adr_letterbox';
                    $GLOBALS['resAlreadyDone'] = true;
                }
                if (!$GLOBALS['resAlreadyDone'] && !$GLOBALS['attachAlreadyDone']) {
                    echo 'resAlreadyDone and attachAlreadyDone' . PHP_EOL;
                    $state = 'SELECT_RES';
                    break;
                }
            } elseif (isset($GLOBALS['dateToPurgeOBS']) && !empty($GLOBALS['dateToPurgeOBS'])) {
                echo 'obs turn' . PHP_EOL;
                //THIRD SELECT THE ATTACH IN OBS STATUS
                $where_clause = "STATUS = 'OBS' and creation_date <= '" . $GLOBALS['dateToPurgeOBS'] . "'";
                $GLOBALS['table'] = 'res_attachments';
                $GLOBALS['versionTable'] = '';
                $GLOBALS['adrTable'] = 'adr_attachments';
                $GLOBALS['obsAlreadyDone'] = true;
                $GLOBALS['dateToPurgeOBS'] = '';
            } else {
                echo 'END OF TURNS' . PHP_EOL;
                $GLOBALS['resAlreadyDone'] = true;
                $GLOBALS['attachAlreadyDone'] = true;
                $GLOBALS['obsAlreadyDone'] = true;
                if (strtolower($GLOBALS['mode']) == 'purge') {
                    if ($GLOBALS['resAlreadyDone'] && $GLOBALS['attachAlreadyDone'] && $GLOBALS['obsAlreadyDone']) {
                        $state = 'DELETE_RES_ON_FS';
                        //var_dump($resourcesArray);
                    } else {
                        $state = 'SELECT_RES';
                    }
                } elseif (strtolower($GLOBALS['mode']) == 'count') {
                    if ($GLOBALS['resAlreadyDone'] && $GLOBALS['attachAlreadyDone'] && $GLOBALS['obsAlreadyDone']) {
                        $state = 'END';
                        //var_dump($resourcesArray);
                    } else {
                        $state = 'SELECT_RES';
                    }
                }
                break;
            }
            $orderBy = 'order by res_id';
            
            $query = "select res_id, docserver_id, path, filename, fingerprint from " 
                . $GLOBALS['table'] 
                . " where " . $where_clause . " " . $orderBy;
            $stmt = Bt_doQuery($GLOBALS['db'], $query);
            $GLOBALS['logger']->write('select res query:' . $query, 'INFO');
            // $resourcesArray = array();
            if ($stmt->rowCount() > 0) {
                while ($resoucesRecordset = $stmt->fetchObject()) {
                    $queryDs = "select path_template from docservers " 
                       . " where docserver_id = ?";
                    $stmt2 = Bt_doQuery(
                        $GLOBALS['db2'], 
                        $queryDs, 
                        array($resoucesRecordset->docserver_id)
                    );
                    if ($stmt2->rowCount() == 0) {
                        Bt_exitBatch(13, 'Docserver:' 
                            . $resoucesRecordset->docserver_id . ' not found');
                        break;
                    } else {
                        $dsRecordset = $stmt2->fetchObject();
                        $dsPath = $dsRecordset->path_template;
                    }
                    array_push(
                        $resourcesArray,
                        array(
                            'res_id' => $resoucesRecordset->res_id,
                            'table' => $GLOBALS['table'],
                            'version_table' => $GLOBALS['versionTable'],
                            'docserver_id' => $resoucesRecordset->docserver_id,
                            'path_template' => $dsPath,
                            'path' =>  str_replace('#', DIRECTORY_SEPARATOR, 
                                $resoucesRecordset->path),
                            'filename' => $resoucesRecordset->filename,
                            'fingerprint' => $resoucesRecordset->fingerprint,
                            'adr' => array(),
                        )
                    );
                    $GLOBALS['totalProcessedResources']++;
                }
            } else {
                // Bt_exitBatch(111, 'no resource found for where clause:' 
                //     . str_replace("'", "''", $GLOBALS['whereClause']));
                break;
            }
            
            $queryIsAdr = "select 1 as adr_exists FROM information_schema.tables WHERE table_name = ?";
            $stmt = Bt_doQuery(
                $GLOBALS['db'], 
                $queryIsAdr,
                array($GLOBALS['adrTable'])
            );
            $adrExists = $stmt->fetchObject();
            if ($adrExists->adr_exists) {
                $countRA = count($resourcesArray);
                for ($cptRes = 0;$cptRes < $countRA;$cptRes++) {
                    $queryAip = "select res_id, docserver_id, path, filename, fingerprint from " 
                        . $GLOBALS['adrTable']
                        . " where res_id = ?";
                    $stmt = Bt_doQuery(
                        $GLOBALS['db'], 
                        $queryAip,
                        array($resourcesArray[$cptRes]["res_id"])
                    );
                    if ($stmt->rowCount() > 0) {
                        while ($resoucesRecordsetAdr = $stmt->fetchObject()) {
                            $queryDs = "select path_template from docservers " 
                               . " where docserver_id = ?";
                            $stmt2 = Bt_doQuery(
                                $GLOBALS['db2'], 
                                $queryDs,
                                array($resoucesRecordsetAdr->docserver_id)
                            );
                            if ($stmt2->rowCount() == 0) {
                                Bt_exitBatch(13, 'Docserver:' 
                                    . $resoucesRecordsetAdr->docserver_id . ' not found');
                                break;
                            } else {
                                $dsRecordset = $stmt2->fetchObject();
                                $dsPath = $dsRecordset->path_template;
                            }
                            array_push($resourcesArray[$cptRes]['adr'], array(
                                    'res_id' => $resoucesRecordsetAdr->res_id,
                                    'adr_table' => $GLOBALS['adrTable'],
                                    'docserver_id' => $resoucesRecordsetAdr->docserver_id,
                                    'path_template' => $dsPath,
                                    'path' => str_replace('#', DIRECTORY_SEPARATOR, 
                                        $resoucesRecordsetAdr->path),
                                    'filename' => $resoucesRecordsetAdr->filename,
                                    'fingerprint' => $resoucesRecordsetAdr->fingerprint,
                                )
                            );
                        }
                    }
                    //history
                    // $query = "insert into " . HISTORY_TABLE
                    //        . " (table_name, record_id, event_type, user_id, "
                    //        . "event_date, info, id_module) values (?, ?, 'ADD', 'PURGE_BOT', '"
                    //        . date("d") . "/" . date("m") . "/" . date("Y") . " " . date("H") 
                    //        . ":" . date("i") . ":" . date("s")
                    //        . "', ?, 'life_cyle')";
                    // $stmt = Bt_doQuery(
                    //     $GLOBALS['db'], 
                    //     $query,
                    //     array(
                    //         $GLOBALS['table'],
                    //         $resourcesArray[$cptRes]["res_id"],
                    //         "purge, where clause:" 
                    //             . str_replace("'", "''", $GLOBALS['whereClause'])
                    //     )
                    // );
                }
            }
            //print_r($resourcesArray);
            if (strtolower($GLOBALS['mode']) == 'purge') {
                if ($GLOBALS['resAlreadyDone'] && $GLOBALS['attachAlreadyDone'] && $GLOBALS['obsAlreadyDone']) {
                    echo 'END OF TURNS' . PHP_EOL;
                    $state = 'DELETE_RES_ON_FS';
                    // var_dump($resourcesArray);
                } else {
                    $state = 'SELECT_RES';
                }
            } elseif (strtolower($GLOBALS['mode']) == 'count') {
                if ($GLOBALS['resAlreadyDone'] && $GLOBALS['attachAlreadyDone'] && $GLOBALS['obsAlreadyDone']) {
                    $state = 'END';
                    echo 'END OF TURNS' . PHP_EOL;
                    // var_dump($resourcesArray);
                } else {
                    $state = 'SELECT_RES';
                }
            }
            
            break;
        /**********************************************************************/
        /*                          DELETE_RES_ON_FS                          */
        /*                                                                    */
        /**********************************************************************/
        case "DELETE_RES_ON_FS" :
            //var_dump($resourcesArray);
            if (strtolower($GLOBALS['debug']) == 'true') {
                $action = 'nothing';
            } elseif (strtolower($GLOBALS['debug']) == 'false') {
                $action = 'erase';
            } else {
                $action = 'nothing';
            }
            $cptRes = 0;
            $countRA = count($resourcesArray);
            for ($cptRes = 0;$cptRes < $countRA;$cptRes++) {
                $GLOBALS['logger']->write('Prepare file deletion for res_id:' 
                    . $resourcesArray[$cptRes]["res_id"] 
                    . 'on table:' . $resourcesArray[$cptRes]['table'], 'INFO');
                $countAdr = count($resourcesArray[$cptRes]['adr']);
                if ($countAdr > 0) {
                    $cptAdr = 0;
                    for ($cptAdr = 0;$cptAdr < $countAdr;$cptAdr++) {
                        $path = $resourcesArray[$cptRes]['adr'][$cptAdr]['path_template'] 
                            . $resourcesArray[$cptRes]['adr'][$cptAdr]['path']
                            . $resourcesArray[$cptRes]['adr'][$cptAdr]['filename'];
                        echo $path . PHP_EOL;
                        if (file_exists($path) && is_file($path)) {
                            if ($action == 'erase') {
                                unlink($path);
                            } else {
                                echo 'debug mode, no erase of file:' . $path;
                            }
                        } else {
                            $GLOBALS['logger']->write('File for res_id ' 
                                . $resourcesArray[$cptRes]['res_id']
                                . 'on table:' . $resourcesArray[$cptRes]['table'] 
                                . ' not exits : '
                                . $path, 'WARNING');
                        }
                    }
                } else {
                    $path = $resourcesArray[$cptRes]['path_template'] 
                          . $resourcesArray[$cptRes]['path']
                          . $resourcesArray[$cptRes]['filename'];
                    echo $path . PHP_EOL;
                    if (file_exists($path) && is_file($path)) {
                        if ($action == 'erase') {
                                unlink($path);
                            } else {
                                echo 'debug mode, no erase of file:' . $path;
                            }
                    } else {
                        $GLOBALS['logger']->write('File for res_id ' 
                            . $resourcesArray[$cptRes]['res_id'] 
                            . 'on table:' . $resourcesArray[$cptRes]['table']
                            . ' not exits : '
                            . $path, 'WARNING');
                    }
                }
            }
            $state = 'DELETE_RES_ON_DB';
            break;
        /**********************************************************************/
        /*                          DELETE_RES_ON_DB                          */
        /*                                                                    */
        /**********************************************************************/
        case "DELETE_RES_ON_DB" :

            for ($cptRes = 0;$cptRes < $countRA;$cptRes++) {

                if (strtolower($GLOBALS['debug']) == 'true') {
                    $action = 'SELECT';
                } elseif (strtolower($GLOBALS['debug']) == 'false') {
                    $action = 'DELETE';
                } else {
                    $action = 'SELECT';
                }
               
                $deleteResQuery = "$action FROM " . $resourcesArray[$cptRes]['table']
                   . " WHERE res_id = ?";
                $stmt = Bt_doQuery(
                    $GLOBALS['db'], 
                    $deleteResQuery,
                    array($resourcesArray[$cptRes]["res_id"])
                );

                if ($resourcesArray[$cptRes]['version_table'] <> "") {
                    $deleteVersionQuery = "$action FROM " . $resourcesArray[$cptRes]['version_table']
                       . " WHERE res_id_master = ?";
                    $stmt = Bt_doQuery(
                        $GLOBALS['db'], 
                        $deleteVersionQuery,
                        array($resourcesArray[$cptRes]["res_id"])
                    );
                }

                if ($resourcesArray[$cptRes]['adr']['adr_table'] <> "") {
                    $deleteAdrQuery = "$action FROM " . $resourcesArray[$cptRes]['adr']['adr_table']
                       . " WHERE res_id = ?";
                    $stmt = Bt_doQuery(
                        $GLOBALS['db'], 
                        $deleteAdrQuery,
                        array($resourcesArray[$cptRes]["res_id"])
                    );
                }

                if ($resourcesArray[$cptRes]['table'] == 'res_letterbox') {
                    $deleteAdrQuery = "$action FROM contacts_res WHERE res_id = ?";
                    $stmt = Bt_doQuery(
                        $GLOBALS['db'], 
                        $deleteAdrQuery,
                        array($resourcesArray[$cptRes]["res_id"])
                    );
                
                    $deleteNotesQuery = "$action FROM notes "
                       . " WHERE identifier = ?";
                    $stmt = Bt_doQuery(
                        $GLOBALS['db'], 
                        $deleteNotesQuery, 
                        array(
                            $resourcesArray[$cptRes]["res_id"]
                        )
                    );

                    $deleteLinkedQuery = "$action FROM res_linked "
                       . " WHERE (res_child = ? or res_parent = ?)";
                    $stmt = Bt_doQuery(
                        $GLOBALS['db'], 
                        $deleteLinkedQuery, 
                        array(
                            $resourcesArray[$cptRes]["res_id"],
                            $resourcesArray[$cptRes]["res_id"]
                        )
                    );

                    $deleteAttachmentsQuery = "$action FROM res_attachments "
                       . " WHERE res_id_master = ?";
                    $stmt = Bt_doQuery(
                        $GLOBALS['db'], 
                        $deleteAttachmentsQuery, 
                        array(
                            $resourcesArray[$cptRes]["res_id"]
                        )
                    );
                }
            }

            $state = 'END';

            break;
    }
}
//var_dump($resourcesArray);
$GLOBALS['logger']->write('End of process for ' 
    . $GLOBALS['totalProcessedResources'] . ' files. In mode ' . $GLOBALS['mode'], 'INFO');
Bt_logInDataBase(
    $GLOBALS['totalProcessedResources'], 0, 'process without error'
);
unlink($GLOBALS['lckFile']);
exit($GLOBALS['exitCode']);
