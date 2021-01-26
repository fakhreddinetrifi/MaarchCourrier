<?php
/******************************************************************************
 BATCH BASKET EVENT STACK

 Processes events from table event_stack

 1 - Add events for each basket with notif enabled

 2 - Scan event

 3 - Prepare e-mail and add to e-mail stack

******************************************************************************/

/* begin */
// load the config and prepare to process
include 'load_basket_event_stack.php';
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
            //Attach Mode ?
            if (!empty($notification['attachfor_type']) || $notification['attachfor_type'] != null) {
                $attachMode = true;
                Bt_writeLog(['level' => 'INFO', 'message' => 'The document will be attached for each recipient']);
            } else {
                $attachMode = false;
            }
            $state = 'ADD_EVENTS';
            break;

        /**********************************************************************/
        /*                          LOAD_EVENTS                               */
        /* Checking if the stack has notifications to proceed                 */
        /**********************************************************************/
        case 'ADD_EVENTS':
            $baskets = \Basket\models\BasketModel::get(['select' => ['basket_id', 'basket_clause'], 'where' => ['flag_notif = ?'], 'data' => ['Y']]);

            foreach ($baskets as $basket) {
                Bt_writeLog(['level' => 'INFO', 'message' => 'BASKET: '.$basket['basket_id'].' in progess ...']);
                $groups = \Basket\models\GroupBasketModel::get(['select' => ['group_id'], 'where' => ['basket_id = ?'], 'data' => [$basket['basket_id']]]);
                $nbGroups = count($groups);

                $u = 1;
                foreach ($groups as $group) {
                    if ($notification['diffusion_type'] == 'group' && !in_array($group['group_id'], explode(",", $notification['diffusion_properties']))) {
                        continue;
                    }
                    $groupInfo = \Group\models\GroupModel::getByGroupId(['groupId' => $group['group_id'], 'select' => ['id']]);
                    $users = \Group\models\GroupModel::getUsersById(['select' => ['users.user_id', 'users.id'], 'id' => $groupInfo['id']]);

                    $countUsersToNotify = count($users);
                    Bt_writeLog(['level' => 'INFO', 'message' => 'GROUP: '.$group['group_id'].' ... '.$countUsersToNotify.' user(s) to notify']);
                    $z = 1;
                    foreach ($users as $userToNotify) {
                        $real_user_id = '';
                        $whereClause  = \SrcCore\controllers\PreparedClauseController::getPreparedClause(['clause' => $basket['basket_clause'], 'login' => $userToNotify['user_id']]);
                        $user_id      = $userToNotify['id'];
                        $redirectedBasket = \Basket\models\RedirectBasketModel::get([
                            'select' => ['actual_user_id'],
                            'where'  => ['owner_user_id = ?', 'basket_id = ?', 'group_id = ?'],
                            'data'   => [$userToNotify['id'], $basket['basket_id'], $groupInfo['id']]
                        ]);
                        if (!empty($redirectedBasket)) {
                            $real_user_id = $userToNotify['id'];
                            $user_id      = $redirectedBasket[0]['actual_user_id'];
                        }

                        $resources = \Resource\models\ResModel::getOnView([
                            'select' => ['res_id'],
                            'where'  => [$whereClause],
                            'data'   => []
                        ]);
                        if (!empty($resources)) {
                            $userNbDoc = count($resources);
                            Bt_writeLog(['level' => 'INFO', 'message' => $userNbDoc.' document(s) to process for '.$userToNotify['user_id']]);
                            $i = 1;
                            $info = 'Notification ['.$basket['basket_id'].'] pour '.$userToNotify['user_id'];
                            if (!empty($real_user_id)) {
                                $notificationEvents = \Notification\models\NotificationsEventsModel::get(['select' => ['record_id'], 'where' => ['event_info = ?', '(user_id = ? OR user_id = ?)'], 'data' => [$info, $userToNotify['id'], $user_id]]);
                            } else {
                                $notificationEvents = \Notification\models\NotificationsEventsModel::get(['select' => ['record_id'], 'where' => ['event_info = ?', 'user_id = ?'], 'data' => [$info, $userToNotify['id']]]);
                            }

                            $aRecordId = array_column($notificationEvents, 'record_id', 'record_id');
                            $aValues   = [];
                            foreach ($resources as $resource) {
                                // echo 'DOCUMENT '.$i.'/'.$userNbDoc.' for USER '.$z.'/'.$countUsersToNotify.' and GROUP '.$u.'/'.$nbGroups."\n";
                                if (empty($aRecordId[$resource['res_id']])) {
                                    $aValues[] = [
                                        'res_letterbox',
                                        '500',
                                        $resource['res_id'],
                                        $user_id,
                                        $info,
                                        'CURRENT_TIMESTAMP'
                                    ];
                                }
                                ++$i;
                            }
                            if (!empty($aValues)) {
                                \SrcCore\models\DatabaseModel::insertMultiple([
                                    'table'   => 'notif_event_stack',
                                    'columns' => ['table_name', 'notification_sid', 'record_id', 'user_id', 'event_info', 'event_date'],
                                    'values'  => $aValues
                                ]);
                            }
                        }
                        ++$z;
                    }
                    ++$u;
                }
            }
            Bt_writeLog(['level' => 'INFO', 'message' => 'Scanning events for notification sid '.$notification['notification_sid']]);
            $events               = \Notification\models\NotificationsEventsModel::get(['select' => ['*'], 'where' => ['notification_sid = ?', 'exec_date is NULL'], 'data' => ['500']]);
            $totalEventsToProcess = count($events);
            $currentEvent         = 0;
            if ($totalEventsToProcess === 0) {
                Bt_exitBatch(0, 'No event to process');
            }
            Bt_writeLog(['level' => 'INFO', 'message' => $totalEventsToProcess.' event(s) to scan']);
            $tmpNotifs = array();
            $state     = 'SCAN_EVENT';
            break;

        /**********************************************************************/
        /*                  MERGE_EVENT                                       */
        /* Process event stack to get recipients                              */
        /**********************************************************************/
        case 'SCAN_EVENT':
            $i = 1;

            $usersId = array_column($events, 'user_id');
            $usersInfo = \User\models\UserModel::get(['select' => ['*'], 'where' => ['id in (?)'], 'data' => [$usersId]]);
            $usersInfo = array_column($usersInfo, null, 'id');
            foreach ($events as $event) {
                preg_match_all('#\[(\w+)]#', $event['event_info'], $result);
                $basket_id = $result[1];

                if ($event['table_name'] == 'res_letterbox' || $event['table_name'] == 'res_view_letterbox') {
                    $res_id = $event['record_id'];
                } else {
                    continue;
                }

                $event['res_id'] = $res_id;
                $user_id         = $event['user_id'];

                $userInfo = $usersInfo[$user_id];
                if (!isset($tmpNotifs[$userInfo['user_id']])) {
                    $tmpNotifs[$userInfo['user_id']]['recipient'] = $userInfo;
                }

                $tmpNotifs[$userInfo['user_id']]['baskets'][$basket_id[0]]['events'][] = $event;

                ++$i;
            }
            $totalNotificationsToProcess = count($tmpNotifs);
            Bt_writeLog(['level' => 'INFO', 'message' => $totalNotificationsToProcess.' notifications to process']);

        /**********************************************************************/
        /*                      FILL_EMAIL_STACK                              */
        /* Merge template and fill notif_email_stack                          */
        /**********************************************************************/
            Bt_writeLog(['level' => 'INFO', 'message' => 'STATE:MERGE NOTIF']);
            $i = 1;
            foreach ($tmpNotifs as $login => $tmpNotif) {
                foreach ($tmpNotif['baskets'] as $basketId => $basket_list) {
                    $baskets = \Basket\models\BasketModel::getByBasketId(['select' => ['basket_name'], 'basketId' => $basketId]);
                    $subject = $baskets['basket_name'];

                    // Merge template with data and style
                    Bt_writeLog(['level' => 'INFO', 'message' => 'generate e-mail '.$i.'/'.$totalNotificationsToProcess.' (TEMPLATE =>'.$notification['template_id'].', SUBJECT => '.$subject.', RECIPIENT => '.$login.', DOCUMENT(S) => '.count($basket_list['events'])]);

                    $params = array(
                        'recipient'    => $tmpNotif['recipient'],
                        'events'       => $basket_list['events'],
                        'notification' => $notification,
                        'maarchUrl'    => $maarchUrl,
                        'coll_id'      => 'letterbox_coll',
                        'res_table'    => 'res_letterbox',
                        'res_view'     => 'res_view_letterbox',
                    );

                    $html = \ContentManagement\controllers\MergeController::mergeNotification(['templateId' => $notification['template_id'], 'params' => $params]);

                    if (strlen($html) === 0) {
                        foreach ($tmpNotif['events'] as $event) {
                            \Notification\models\NotificationsEventsModel::update([
                                'set'   => ['exec_date' => 'CURRENT_TIMESTAMP', 'exec_result' => 'FAILED: Error when merging template'],
                                'where' => ['event_stack_sid = ?'],
                                'data'  => [$event['event_stack_sid']]
                            ]);
                        }
                        Bt_exitBatch(8, 'Could not merge template with the data');
                    }

                    // Prepare e-mail for stack
                    $recipient_mail     = $tmpNotif['recipient']['mail'];

                    if (!empty($recipient_mail)) {
                        $html = str_replace("&#039;", "'", $html);
                        $html = pg_escape_string($html);
                        $html = str_replace('&amp;', '&', $html);
                        $html = str_replace('&', '#and#', $html);

                        // Attachments
                        $attachments = array();
                        if ($attachMode) {
                            Bt_writeLog(['level' => 'INFO', 'message' => 'Adding attachments']);
                            foreach ($basket_list['events'] as $event) {
                                if ($event['res_id'] != '') {
                                    $resourceToAttach = \Resource\models\ResModel::getById(['resId' => $event['res_id'], 'select' => ['path', 'filename', 'docserver_id']]);
                                    if (!empty($resourceToAttach['docserver_id'])) {
                                        $docserver        = \Docserver\models\DocserverModel::getByDocserverId(['docserverId' => $resourceToAttach['docserver_id'], 'select' => ['path_template']]);
                                        $path             = $docserver['path_template'] . str_replace('#', DIRECTORY_SEPARATOR, $resourceToAttach['path']) . $resourceToAttach['filename'];
                                        $path = str_replace('//', '/', $path);
                                        $path = str_replace('\\', '/', $path);
                                        $attachments[] = $path;
                                    }
                                }
                            }
                            Bt_writeLog(['level' => 'INFO', 'message' => count($attachments).' attachment(s) added']);
                        }
                    
                        Bt_writeLog(['level' => 'INFO', 'message' => '... adding e-mail to email stack']);

                        $arrayPDO = [
                            'recipient' => $recipient_mail,
                            'subject'   => $subject,
                            'html_body' => $html
                        ];
                        if (count($attachments) > 0) {
                            $arrayPDO['attachments'] = implode(',', $attachments);
                        }
                        \Notification\models\NotificationsEmailsModel::create($arrayPDO);

                        $notificationSuccess = array_column($basket_list['events'], 'event_stack_sid');
                        if (!empty($notificationSuccess)) {
                            \Notification\models\NotificationsEventsModel::update([
                                'set'   => ['exec_date' => 'CURRENT_TIMESTAMP', 'exec_result' => 'SUCCESS'],
                                'where' => ['event_stack_sid IN (?)'],
                                'data'  => [$notificationSuccess]
                            ]);
                        }
                    }
                }
                ++$i;
            }
            $state = 'END';
    }
}

Bt_writeLog(['level' => 'INFO', 'message' => 'End of process']);
Bt_logInDataBase(
    $totalEventsToProcess,
    0,
    $totalNotificationsToProcess.' notification(s) processed without error'
);

exit($GLOBALS['exitCode']);
