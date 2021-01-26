<?php
/******************************************************************************
 BATCH PROCESS EVENT STACK

 Processes events from table event_stack

 1 - Groups events by
    * Recipient
    * Event

 2 - Merge template for each recipient / event

 3 - Prepare e-mail and add to e-mail stack

******************************************************************************/

/* begin */
// load the config and prepare to process
include 'load_process_event_stack.php';
$state = 'LOAD_NOTIFICATIONS';
while ($state != 'END') {
    Bt_writeLog(['level' => 'INFO', 'message' => 'STATE:'.$state]);
    switch ($state) {
        /**********************************************************************/
        /*                          LOAD_NOTIFICATIONS                        */
        /* Load notification defsidentified with notification id              */
        /**********************************************************************/
        case 'LOAD_NOTIFICATIONS':
            Bt_writeLog(['level' => 'INFO', 'message' => 'Loading configuration for notification id '.$notificationId]);
            $notification = \Notification\models\NotificationModel::getByNotificationId(['notificationId' => $notificationId, 'select' => ['*']]);
            if ($notification === false) {
                Bt_exitBatch(1, "Notification '".$notificationId."' not found");
            }
            if ($notification['is_enabled'] === 'N') {
                Bt_exitBatch(100, "Notification '".$notificationId."' is disabled");
            }
            $state = 'LOAD_EVENTS';
            break;

        /**********************************************************************/
        /*                          LOAD_EVENTS                               */
        /* Checking if the stack has notifications to proceed                 */
        /**********************************************************************/
        case 'LOAD_EVENTS':
            Bt_writeLog(['level' => 'INFO', 'message' => 'Loading events for notification sid '.$notification['notification_sid']]);
            $events = \Notification\models\NotificationsEventsModel::get(['select' => ['*'], 'where' => ['notification_sid = ?', 'exec_date is NULL'], 'data' => [$notification['notification_sid']]]);
            $totalEventsToProcess = count($events);
            $currentEvent = 0;
            if ($totalEventsToProcess === 0) {
                Bt_exitBatch(0, 'No event to process');
            }
            Bt_writeLog(['level' => 'INFO', 'message' => $totalEventsToProcess.' events to process']);
            $tmpNotifs = array();
            $state = 'MERGE_EVENT';
            break;

        /**********************************************************************/
        /*                  MERGE_EVENT                                       */
        /* Process event stack to get recipients                              */
        /**********************************************************************/
        case 'MERGE_EVENT':
            foreach ($events as $event) {
                Bt_writeLog(['level' => 'INFO', 'message' => "Getting recipients using diffusion type '".$notification['diffusion_type']."'"]);
                // Diffusion type specific recipients
                $recipients = \Notification\controllers\DiffusionTypesController::getItemsToNotify(['request' => 'recipients', 'notification' => $notification, 'event' => $event]);
                // Diffusion type specific res_id
                Bt_writeLog(['level' => 'INFO', 'message' => "Getting document ids using diffusion type '".$notification['diffusion_type']."'"]);
                $res_id = false;
                if ($event['table_name'] == 'res_letterbox' || $event['table_name'] == 'res_view_letterbox') {
                    $res_id = $event['record_id'];
                } else {
                    $res_id = \Notification\controllers\DiffusionTypesController::getItemsToNotify(['request' => 'res_id', 'notification' => $notification, 'event' => $event]);
                }
                $event['res_id'] = $res_id;

                //Attach Mode ?
                if (!empty($notification['attachfor_type']) || $notification['attachfor_type'] != null) {
                    $attachMode = true;
                    Bt_writeLog(['level' => 'INFO', 'message' => 'The document will be attached for each recipient']);
                } else {
                    $attachMode = false;
                }

                $nbRecipients = count($recipients);

                Bt_writeLog(['level' => 'INFO', 'message' => $nbRecipients.' recipients found, checking active and absences']);

                $parameter = \Parameter\models\ParameterModel::getById(['select' => ['param_value_int'], 'id' => 'user_quota']);
                if ($notification['diffusion_type'] === 'dest_entity') {
                    foreach ($recipients as $key => $recipient) {
                        $entity_id = $recipient['entity_id'];
                        Bt_writeLog(['level' => 'INFO', 'message' => 'Recipient entity '.$entity_id]);

                        if (($recipient['enabled'] == 'N' && (empty($parameter) || $parameter['param_value_int'] == 0)) || $recipient['mail'] == '') {
                            Bt_writeLog(['level' => 'INFO', 'message' => $entity_id.' is disabled or mail is invalid, this notification will not be send']);
                            unset($recipients[$key]);
                            continue;
                        }

                        if (!isset($tmpNotifs[$entity_id])) {
                            $tmpNotifs[$entity_id]['recipient'] = $recipient;
                        }
                        $tmpNotifs[$entity_id]['events'][] = $event;
                    }
                } else {
                    foreach ($recipients as $key => $recipient) {
                        $user_id = $recipient['user_id'];
                        Bt_writeLog(['level' => 'INFO', 'message' => 'Recipient '.$user_id]);
                        
                        if (($recipient['status'] == 'SPD' && (empty($parameter) || $parameter['param_value_int'] == 0)) || $recipient['status'] == 'DEL') {
                            Bt_writeLog(['level' => 'INFO', 'message' => $user_id.' is disabled or deleted, this notification will not be send']);
                            unset($recipients[$key]);
                            continue;
                        }

                        if (!isset($tmpNotifs[$user_id])) {
                            $tmpNotifs[$user_id]['recipient'] = $recipient;
                        }
                        $tmpNotifs[$user_id]['events'][] = $event;
                    }
                }

                if (count($recipients) === 0) {
                    Bt_writeLog(['level' => 'WARN', 'message' => 'No recipient found']);
                    \Notification\models\NotificationsEventsModel::update([
                        'set'   => ['exec_date' => 'CURRENT_TIMESTAMP', 'exec_result' => 'INFO: no recipient found'],
                        'where' => ['event_stack_sid = ?'],
                        'data'  => [$event['event_stack_sid']]
                    ]);
                }
            }
            $totalNotificationsToProcess = count($tmpNotifs);
            Bt_writeLog(['level' => 'INFO', 'message' => $totalNotificationsToProcess.' notifications to process']);
            switch ($notification['notification_mode']) {
                case 'EMAIL':
                    $state = 'FILL_EMAIL_STACK';
                    break;
            }
            break;

        /**********************************************************************/
        /*                      FILL_EMAIL_STACK                              */
        /* Merge template and fill notif_email_stack                          */
        /**********************************************************************/
        case 'FILL_EMAIL_STACK':
            foreach ($tmpNotifs as $user_id => $tmpNotif) {
                // Merge template with data and style
                Bt_writeLog(['level' => 'INFO', 'message' => 'Merging template #'.$notification['template_id']
                .' ('.count($tmpNotif['events']).' events) for user '.$user_id]);

                $params = array(
                    'recipient'    => $tmpNotif['recipient'],
                    'events'       => $tmpNotif['events'],
                    'notification' => $notification,
                    'maarchUrl'    => $maarchUrl,
                    'coll_id'      => 'letterbox_coll',
                    'res_table'    => 'res_letterbox',
                    'res_view'     => 'res_view_letterbox',
                );
                $html = \ContentManagement\controllers\MergeController::mergeNotification(['templateId' => $notification['template_id'], 'params' => $params]);
                if (strlen($html) === 0) {
                    $notificationError = array_column($tmpNotif['events'], 'event_stack_sid');
                    if (!empty($notificationError)) {
                        \Notification\models\NotificationsEventsModel::update([
                            'set'   => ['exec_date' => 'CURRENT_TIMESTAMP', 'exec_result' => 'FAILED: Error when merging template'],
                            'where' => ['event_stack_sid IN (?)'],
                            'data'  => [$notificationError]
                        ]);
                    }
                    Bt_exitBatch(8, 'Could not merge template with the data');
                }

                // Prepare e-mail for stack
                $recipient_mail = $tmpNotif['recipient']['mail'];
                $subject        = $notification['description'];

                if (!empty($recipient_mail)) {
                    $html = str_replace("&#039;", "'", $html);
                    $html = pg_escape_string($html);
                    $html = str_replace('&amp;', '&', $html);
                    $html = str_replace('&', '#and#', $html);

                    $recipient_mail = $tmpNotif['recipient']['mail'];

                    // Attachments
                    $attachments = array();
                    if ($attachMode) {
                        Bt_writeLog(['level' => 'INFO', 'message' => 'Adding attachments']);
                        foreach ($tmpNotif['events'] as $event) {
                            if ($event['res_id'] != '') {
                                $resourceToAttach = \Resource\models\ResModel::getById(['resId' => $event['res_id'], 'select' => ['path', 'filename', 'docserver_id']]);
                                if (!empty($resourceToAttach['docserver_id'])) {
                                    $docserver     = \Docserver\models\DocserverModel::getByDocserverId(['docserverId' => $resourceToAttach['docserver_id'], 'select' => ['path_template']]);
                                    $path          = $docserver['path_template'] . str_replace('#', DIRECTORY_SEPARATOR, $resourceToAttach['path']) . $resourceToAttach['filename'];
                                    $path          = str_replace('//', '/', $path);
                                    $path          = str_replace('\\', '/', $path);
                                    $attachments[] = $path;
                                }
                            }
                        }
                        Bt_writeLog(['level' => 'INFO', 'message' => count($attachments).' attachment(s) added']);
                    }

                    Bt_writeLog(['level' => 'INFO', 'message' => 'adding e-mail to email stack']);

                    $arrayPDO = [
                        'recipient' => $recipient_mail,
                        'subject'   => $subject,
                        'html_body' => $html
                    ];
                    if (count($attachments) > 0) {
                        $arrayPDO['attachments'] = implode(',', $attachments);
                    }
                    \Notification\models\NotificationsEmailsModel::create($arrayPDO);

                    $notificationSuccess = array_column($tmpNotif['events'], 'event_stack_sid');
                    if (!empty($notificationSuccess)) {
                        \Notification\models\NotificationsEventsModel::update([
                            'set'   => ['exec_date' => 'CURRENT_TIMESTAMP', 'exec_result' => 'SUCCESS'],
                            'where' => ['event_stack_sid IN (?)'],
                            'data'  => [$notificationSuccess]
                        ]);
                    }
                }
            }
            $state = 'END';
            break;
    }
}

Bt_writeLog(['level' => 'INFO', 'message' => 'End of process']);
Bt_logInDataBase($totalEventsToProcess, 0, 'process without error');

exit($GLOBALS['exitCode']);
