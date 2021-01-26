<?php

/******************************************************************************/

// load the config and prepare to process
include('load_process_emails.php');

/* begin */
$state = 'LOAD_EMAILS';
while ($state <> 'END') {
    if (isset($GLOBALS['logger'])) {
        $GLOBALS['logger']->write('STATE:' . $state, 'INFO');
    }
    switch ($state) {
		
	/**********************************************************************/
    /*                          LOAD_EMAILS 							  */
    /* List the records to proceed       								  */
    /**********************************************************************/
    case 'LOAD_EMAILS' :
		$totalEmailsToProcess = 1;
		$currentEmail = 0;
		$GLOBALS['emails'] = array();
		$emailRecordset = new stdClass();
		$emailRecordset->to_list=$GLOBALS['MailToNotify'];
		$emailRecordset->email_object='statistiques du '.date('d/m/Y');
		$emailRecordset->email_body='Ci-joint.';

		$allFiles = scandir($GLOBALS['ExportFolder']);
		foreach ($allFiles as $key => $allFile) {
			$fileInfo = pathinfo($allFile);
			if ($fileInfo['extension'] == 'csv') {
				if (is_readable($GLOBALS['ExportFolder'].$fileInfo['basename'])) {
					rename($GLOBALS['ExportFolder'].$fileInfo['basename'], $GLOBALS['TmpDirectory'].'/'.$fileInfo['basename']);
					$csvFiles[] = $GLOBALS['TmpDirectory'].'/'.$fileInfo['basename'];
				} else {
					Bt_exitBatch(0, 'csv is not readable');
				}	
			}
		}
		if (count($csvFiles) === 0) {
			Bt_exitBatch(0, 'No e-mails to process');
        }
		$GLOBALS['logger']->write($totalEmailsToProcess . ' e-mails to proceed.', 'INFO');
		$csvFiles=implode(',',$csvFiles);
		$emailRecordset->attachments=$csvFiles;
		//while ($emailRecordset = $stmt->fetchObject()) {
			$GLOBALS['emails'][] = $emailRecordset;
		//}
		$state = 'SEND_AN_EMAIL';
		$err = 0;
        $errTxt = '';
    break;
		
	/**********************************************************************/
    /*                          SEND_AN_EMAIL	 		          	          */
    /* Load parameters and send an e-mail                                    */
    /**********************************************************************/
    case 'SEND_AN_EMAIL' :
		if($currentEmail < $totalEmailsToProcess) {
			$email = $GLOBALS['emails'][$currentEmail];
			//var_dump($email);exit;
			$GLOBALS['mailer'] = new htmlMimeMail();
			$GLOBALS['mailer']->setSMTPParams(
				$host = (string)$mailerParams->smtp_host, 
				$port = (string)$mailerParams->smtp_port,
				$helo = (string)$mailerParams->domains,
				$auth = filter_var($mailerParams->smtp_auth, FILTER_VALIDATE_BOOLEAN),
				$user = (string)$mailerParams->smtp_user,
				$pass = (string)$mailerParams->smtp_password
				);
			$mailfrom_generic = (string)$mailerParams->mailfrom;
			
			$mailsEntities = $sendmail_tools->getAttachedEntitiesMails();
			$entityShortLabel = substr($mailsEntities[$email->sender_email], 0, strrpos($mailsEntities[$email->sender_email], "("));
				
            $GLOBALS['mailer']->setFrom($mailfrom_generic);
            $GLOBALS['logger']->write("set mailfrom : " . $entityShortLabel . ' <' . $mailfrom_generic. '> ');

            $emailFrom = $mailfrom_generic;

			//$GLOBALS['mailer']->setReplyTo($sendmail_tools->explodeSenderEmail($email->sender_email));
			
			//--> Set the return path
			if (!empty($mailfrom_generic)) {
				$GLOBALS['mailer']->setReturnPath(
					$userInfo['firstname'] . ' ' .  $userInfo['lastname'] . ' <' . $mailfrom_generic . '> '
				);
			} else {
				$GLOBALS['mailer']->setReturnPath($userInfo['mail']);
			}

			//--> To
			$to = array();
			$to = explode(',', $email->to_list);
			$GLOBALS['logger']->write("set to : " . $email->to_list);
			//--> Set subject
			$GLOBALS['mailer']->setSubject($email->email_object);
			$GLOBALS['logger']->write("set object : " . $email->email_object);

			//--> Set body: Is Html/raw text ?
			$body = $sendmail_tools->htmlToRaw($email->email_body);
			$GLOBALS['mailer']->setText($body);
			$GLOBALS['logger']->write("set body : " . $body);
			//--> Set charset
			$GLOBALS['mailer']->setTextCharset($GLOBALS['charset']);
			$GLOBALS['mailer']->setHtmlCharset($GLOBALS['charset']);
			$GLOBALS['mailer']->setHeadCharset($GLOBALS['charset']);
					
			//Res attachment
			if (!empty($email->attachments)) {
                $attachments = explode(',', $email->attachments);
				foreach($attachments as $attachment_path) {
					$GLOBALS['logger']->write("set attachment on : " . $attachment_path, 'INFO');
	
					if(is_file($attachment_path)) {
						$fileInfo = pathinfo($attachment_path);
						//Filename
						$attachmentFilename = $sendmail_tools->createFilename($fileInfo['filename'], $fileInfo['extension']);
						$GLOBALS['logger']->write("set attachment filename : " . $attachmentFilename, 'INFO');

						//File content
						$file_content = $GLOBALS['mailer']->getFile($attachment_path);
						//Add file
						$GLOBALS['mailer']->addAttachment($file_content, $attachmentFilename, $attachmentFile['mime_type']); 
					}
				}
            }
			

			//Now send the mail
			$GLOBALS['logger']->write("sending e-mail ...", 'INFO');
			$return = $GLOBALS['mailer']->send($to, (string)$mailerParams->type);

			if( ($return == 1 && ((string)$mailerParams->type == "smtp" || (string)$mailerParams->type == "mail" )) || ($return == 0 && (string)$mailerParams->type == "sendmail")) {
				$exec_result = 'S';
				$GLOBALS['logger']->write("e-mail sent.", 'INFO');
			} else {
				//$GLOBALS['logger']->write("Errors when sending message through SMTP :" . implode(', ', $GLOBALS['mailer']->errors), 'ERROR');
                $GLOBALS['logger']->write("SENDING EMAIL ERROR ! (" . $return[0].")", 'ERROR');
                $GLOBALS['logger']->write("e-mail not sent !", 'ERROR');
				$exec_result = 'E';
				$err++;
				$errTxt = ' (Last Error : '.$return[0].')';

            	if(!empty($userInfo['mail'])){
            		Bt_doQuery($GLOBALS['db'], $query, array($emailFrom, $userInfo['mail'], $GLOBALS['subjectmail'], $GLOBALS['bodymail'].'<br><br>'.$return[0], $GLOBALS['charset']));
                }

			}
			
			$currentEmail++;
			$state = 'SEND_AN_EMAIL';
		} else {
			$state = 'END';
		}
        break;
	}
}

$emailSent = $totalEmailsToProcess - $err;

$GLOBALS['logger']->write($emailSent.' email(s) sent', 'INFO');
$GLOBALS['logger']->write('end of process', 'INFO');

Bt_logInDataBase(
    $totalEmailsToProcess, $err, $emailSent.' email(s) sent'.$errTxt
);
Bt_updateWorkBatch();

//clean tmp directory
echo "clean tmp path ....\n";
foreach($attachments as $attachment_path) {
	unlink($attachment_path);
}

exit($GLOBALS['exitCode']);
?>
