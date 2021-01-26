<?php

/******************************************************************************/
// load the config and prepare to process
include('load_process_email_stack.php');
$state = 'LOAD_EMAILS';
while ($state <> 'END') {
    Bt_writeLog(['level' => 'INFO', 'message' => 'STATE:' . $state]);
    switch ($state) {
        /**********************************************************************/
        /*                          LOAD_NOTIFS                               */
        /* List the stack to proceed                                          */
        /**********************************************************************/
        case 'LOAD_EMAILS':
            $emails = \Notification\models\NotificationsEmailsModel::get(['select' => ['*'], 'where' => ['exec_date is NULL'], 'data' => []]);
            $totalEmailsToProcess = count($emails);
            $currentEmail = 0;
            if ($totalEmailsToProcess === 0) {
                Bt_exitBatch(0, 'No notification to send');
            }
            Bt_writeLog(['level' => 'INFO', 'message' => $totalEmailsToProcess . ' notification(s) to send.']);
            $state  = 'SEND_AN_EMAIL';
            $err    = 0;
            $errTxt = '';
            break;
            
        /**********************************************************************/
        /*                          SEND_AN_EMAIL                                 */
        /* Load parameters and send an e-mail                                    */
        /**********************************************************************/
        case 'SEND_AN_EMAIL':
            $configuration = \Configuration\models\ConfigurationModel::getByPrivilege(['privilege' => 'admin_email_server', 'select' => ['value']]);
            foreach ($emails as $key => $email) {
                $configuration = json_decode($configuration['value'], true);
                if (empty($configuration)) {
                    Bt_exitBatch(110, 'Configuration admin_email_server is missing');
                }
                
                $phpmailer = new \PHPMailer\PHPMailer\PHPMailer();
                $phpmailer->setFrom($configuration['from'], $configuration['from']);
                if (in_array($configuration['type'], ['smtp', 'mail'])) {
                    if ($configuration['type'] == 'smtp') {
                        $phpmailer->isSMTP();
                    } elseif ($configuration['type'] == 'mail') {
                        $phpmailer->isMail();
                    }
        
                    $phpmailer->Host = $configuration['host'];
                    $phpmailer->Port = $configuration['port'];
                    $phpmailer->SMTPAutoTLS = false;
                    if (!empty($configuration['secure'])) {
                        $phpmailer->SMTPSecure = $configuration['secure'];
                    }
                    $phpmailer->SMTPAuth = $configuration['auth'];
                    if ($configuration['auth']) {
                        $phpmailer->Username = $configuration['user'];
                        if (!empty($configuration['password'])) {
                            $phpmailer->Password = \SrcCore\models\PasswordModel::decrypt(['cryptedPassword' => $configuration['password']]);
                        }
                    }
                } elseif ($configuration['type'] == 'sendmail') {
                    $phpmailer->isSendmail();
                } elseif ($configuration['type'] == 'qmail') {
                    $phpmailer->isQmail();
                }
        
                $phpmailer->CharSet = $configuration['charset'];
        
                $phpmailer->addAddress($email['recipient']);
        
                $phpmailer->isHTML(true);

                $email['html_body'] = str_replace('#and#', '&', $email['html_body']);
                $email['html_body'] = str_replace("\''", "'", $email['html_body']);
                $email['html_body'] = str_replace("\'", "'", $email['html_body']);
                $email['html_body'] = str_replace("''", "'", $email['html_body']);
    
                $dom = new \DOMDocument();
                $internalErrors = libxml_use_internal_errors(true);
                $dom->loadHTML($email['html_body'], LIBXML_NOWARNING);
                libxml_use_internal_errors($internalErrors);
                $images = $dom->getElementsByTagName('img');
    
                foreach ($images as $key => $image) {
                    $originalSrc = $image->getAttribute('src');
                    if (preg_match('/^data:image\/(\w+);base64,/', $originalSrc)) {
                        $encodedImage = substr($originalSrc, strpos($originalSrc, ',') + 1);
                        $imageFormat = substr($originalSrc, 11, strpos($originalSrc, ';') - 11);
                        $phpmailer->addStringEmbeddedImage(base64_decode($encodedImage), "embeded{$key}", "embeded{$key}.{$imageFormat}");
                        $email['html_body'] = str_replace($originalSrc, "cid:embeded{$key}", $email['html_body']);
                    }
                }
        
                $phpmailer->Subject = $email['subject'];
                $phpmailer->Body = $email['html_body'];
                if (empty($email['html_body'])) {
                    $phpmailer->AllowEmpty = true;
                }
        
                if ($email['attachments'] != '') {
                    $attachments = explode(',', $email['attachments']);
                    foreach ($attachments as $num => $attachment) {
                        if (is_file($attachment)) {
                            $ext  = strrchr($attachment, '.');
                            $name = str_pad(($num + 1), 4, '0', STR_PAD_LEFT) . $ext;
                            $phpmailer->addStringAttachment(file_get_contents($attachment), $name);
                        }
                    }
                }
        
                $phpmailer->Timeout = 30;
                $phpmailer->SMTPDebug = 1;
                $phpmailer->Debugoutput = function ($str) {
                    if (strpos($str, 'SMTP ERROR') !== false) {
                        $user = \User\models\UserModel::get(['select' => ['id'], 'orderBy' => ["user_id='superadmin' desc"], 'limit' => 1]);
                        \History\controllers\HistoryController::add([
                            'tableName'    => 'emails',
                            'recordId'     => 'email',
                            'eventType'    => 'ERROR',
                            'eventId'      => 'sendEmail',
                            'userId'       => $user[0]['id'],
                            'info'         => $str
                        ]);
                    }
                };
        
                $isSent = $phpmailer->send();
                if ($isSent) {
                    $exec_result = 'SENT';
                    Bt_writeLog(['level' => 'INFO', 'message' => "notification sent"]);
                } else {
                    $err++;
                    Bt_writeLog(['level' => 'ERROR', 'message' => "SENDING EMAIL ERROR ! (" . $phpmailer->ErrorInfo.")"]);
                    $errTxt = ' (Last Error : '.$phpmailer->ErrorInfo.')';
                    $exec_result = 'FAILED';
                    $GLOBALS['exitCode'] = 108;
                }

                \Notification\models\NotificationsEmailsModel::update([
                    'set'   => ['exec_date' => 'CURRENT_TIMESTAMP', 'exec_result' => $exec_result],
                    'where' => ['email_stack_sid = ?'],
                    'data'  => [$email['email_stack_sid']]
                ]);
                $state = 'SEND_AN_EMAIL';
            }
            $state = 'END';
            break;
    }
}
$emailSent = $totalEmailsToProcess - $err;

Bt_writeLog(['level' => 'INFO', 'message' => $emailSent.' notification(s) sent']);
Bt_writeLog(['level' => 'INFO', 'message' => 'End of process']);

Bt_logInDataBase(
    $totalEmailsToProcess,
    $err,
    $emailSent.' notification(s) sent'.$errTxt
);
Bt_updateWorkBatch();

unlink($GLOBALS['lckFile']);
exit($GLOBALS['exitCode']);
