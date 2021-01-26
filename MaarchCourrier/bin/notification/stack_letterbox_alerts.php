<?php

/******************************************************************************/
/* begin */
// load the config and prepare to process
include('load_stack_letterbox_alerts.php');

$state = 'LOAD_ALERTS_NOTIFS';
while ($state <> 'END') {
    Bt_writeLog(['level' => 'INFO', 'message' => 'STATE:' . $state]);

    switch ($state) {
        /**********************************************************************/
        /*                          LOAD_ALERTS_NOTIFS                               */
        /* Load parameters                                                    */
        /**********************************************************************/
        case 'LOAD_ALERTS_NOTIFS':
            $alertRecordset = \Notification\models\NotificationModel::get(['select' => ['notification_sid', 'event_id'], 'where' => ['event_id in (?)'], 'data' => [['alert1', 'alert2']]]);
            if (empty($alertRecordset)) {
                Bt_exitBatch(0, 'No alert set');
            }
            Bt_writeLog(['level' => 'INFO', 'message' => count($alertRecordset) . " notifications set for mail alerts"]);

            $alertNotifs = [];
            foreach ($alertRecordset as $value) {
                $alertNotifs[$value['event_id']][] = $value['notification_sid'];
            }
            $state = 'LOAD_DOCTYPES';
            break;
        
        /**********************************************************************/
        /*                          LOAD_DOCTYPES                            */
        /* Load parameters                                                   */
        /**********************************************************************/
        case 'LOAD_DOCTYPES':
            $doctypes = \Doctype\models\DoctypeModel::get();
            $doctypes = array_column($doctypes, null, 'type_id');
            Bt_writeLog(['level' => 'INFO', 'message' => count($doctypes) . " document types set"]);
            $state = 'LIST_DOCS';
            break;
        /**********************************************************************/
        /*                          LIST_DOCS                                 */
        /* List the resources to proccess for alarms                          */
        /**********************************************************************/
        case 'LIST_DOCS':
            $resources = \Resource\models\ResModel::get([
                'select' => ['res_id', 'type_id', 'process_limit_date', 'flag_alarm1', 'flag_alarm2'],
                'where' => ['closing_date IS null', 'status NOT IN (?)', '(flag_alarm1 = \'N\' OR flag_alarm2 = \'N\')', 'process_limit_date IS NOT NULL'],
                'data'  => [['CLO', 'DEL', 'END']]
            ]);
            if (empty($resources)) {
                Bt_exitBatch(0, 'No document to process');
            }
            $totalDocsToProcess = count($resources);
            Bt_writeLog(['level' => 'INFO', 'message' => $totalDocsToProcess . " documents to process (i.e. not closed, at least one alert to send)"]);

            $state = 'A_DOC';
            break;
            
        /**********************************************************************/
        /*                          A_DOC                                     */
        /* Add notification to event_stack for each notif to be sent          */
        /**********************************************************************/
        case 'A_DOC':
            foreach ($resources as $myDoc) {
                Bt_writeLog(['level' => 'INFO', 'message' => "Processing document #" . $myDoc['res_id']]);
                    
                $myDoctype = $doctypes[$myDoc['type_id']];
                if (!$myDoctype) {
                    Bt_writeLog(['level' => 'WARN', 'message' => 'Unknown document type ' . $myDoc['type_id']]);
                    continue;
                }
                Bt_writeLog(['level' => 'INFO', 'message' => "Document type id is #" . $myDoc['type_id']]);
                $users = \User\models\UserModel::get(['select' => ['id'], 'orderBy' => ["user_id='superadmin' desc"], 'limit' => 1]);
                $user = $users[0];

                // Alert 1 = limit - n days
                if ($myDoc['flag_alarm1'] != 'Y' && $myDoc['flag_alarm2'] != 'Y' && $myDoctype['delay1'] > 0) {
                    $processDate = \Resource\controllers\IndexingController::calculateProcessDate(['date' => $myDoc['process_limit_date'], 'delay' => $myDoctype['delay1'], 'sub' => true]);
                    if (strtotime($processDate) <= time()) {
                        Bt_writeLog(['level' => 'INFO', 'message' => "Alarm 1 will be sent"]);
                        $info = 'Relance 1 pour traitement du document No' . $myDoc['res_id'] . ' avant date limite.';
                        if (count($alertNotifs['alert1']) > 0) {
                            foreach ($alertNotifs['alert1'] as $notification_sid) {
                                \Notification\models\NotificationsEventsModel::create([
                                    'notification_sid' => $notification_sid,
                                    'table_name'       => 'res_view_letterbox',
                                    'record_id'        => $myDoc['res_id'],
                                    'user_id'          => $user['id'],
                                    'event_info'       => $info
                                ]);
                            }
                        }
                        \Resource\models\ResModel::update(['set' => ['flag_alarm1' => 'Y', 'alarm1_date' => 'CURRENT_TIMESTAMP'], 'where' => ['res_id = ?'], 'data' => [$myDoc['res_id']]]);
                    }
                }

                // Alert 2 = limit + n days
                if ($myDoc['flag_alarm2'] != 'Y' && $myDoctype['delay2'] > 0) {
                    $processDate = \Resource\controllers\IndexingController::calculateProcessDate(['date' => $myDoc['process_limit_date'], 'delay' => $myDoctype['delay2']]);
                    if (strtotime($processDate) <= time()) {
                        Bt_writeLog(['level' => 'INFO', 'message' => "Alarm 2 will be sent"]);
                        $info = 'Relance 2 pour traitement du document No' . $myDoc['res_id'] . ' apres date limite.';
                        if (count($alertNotifs['alert2']) > 0) {
                            foreach ($alertNotifs['alert2'] as $notification_sid) {
                                \Notification\models\NotificationsEventsModel::create([
                                    'notification_sid' => $notification_sid,
                                    'table_name'       => 'res_view_letterbox',
                                    'record_id'        => $myDoc['res_id'],
                                    'user_id'          => $user['id'],
                                    'event_info'       => $info
                                ]);
                            }
                        }
                        \Resource\models\ResModel::update(['set' => ['flag_alarm1' => 'Y', 'flag_alarm2' => 'Y', 'alarm2_date' => 'CURRENT_TIMESTAMP'], 'where' => ['res_id = ?'], 'data' => [$myDoc['res_id']]]);
                    }
                }
            }
            $state = 'END';
            break;
    }
}

Bt_writeLog(['level' => 'INFO', 'message' => 'End of process']);
Bt_logInDataBase($totalDocsToProcess, 0, 'process without error');

unlink($GLOBALS['lckFile']);
exit($GLOBALS['exitCode']);
