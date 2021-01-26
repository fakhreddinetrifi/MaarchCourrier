<?php

require '../../vendor/autoload.php';

chdir('../..');

$nonReadableFiles = [];
$migrated = 0;
$customs =  scandir('custom');

foreach ($customs as $custom) {
    if (in_array($custom, ['custom.json', 'custom.xml', '.', '..'])) {
        continue;
    }

    \SrcCore\models\DatabasePDO::reset();
    new \SrcCore\models\DatabasePDO(['customId' => $custom]);
    $GLOBALS['customId'] = $custom;

    $configPath             = "custom/{$custom}/apps/maarch_entreprise/xml/config.json";
    $notificationConfigPath = "modules/notifications/batch/config/config.xml";
    if (file_exists($configPath)) {
        if (!is_readable($configPath) || !is_writable($configPath)) {
            $nonReadableFiles[] = $configPath;
            continue;
        }
        if (is_readable($notificationConfigPath) && is_writable($notificationConfigPath)) {
            $notificationFilePath = $notificationConfigPath;
        } elseif (is_readable("custom/{$custom}/{$notificationConfigPath}") && is_writable("custom/{$custom}/{$notificationConfigPath}")) {
            $notificationFilePath = "custom/{$custom}/{$notificationConfigPath}";
        } elseif (is_readable("custom/{$custom}/modules/notifications/batch/config/config_{$custom}.xml") && is_writable("custom/{$custom}/modules/notifications/batch/config/config_{$custom}.xml")) {
            $notificationFilePath = "custom/{$custom}/modules/notifications/batch/config/config_{$custom}.xml";
        } else {
            $file = file_get_contents($configPath);
            $file = json_decode($file, true);
            $file['config']['maarchDirectory'] = realpath('.') . '/';
            $file['config']['customID'] = $custom;
            $fp = fopen($configPath, 'w+');
            fwrite($fp, json_encode($file, JSON_PRETTY_PRINT));
            fclose($fp);
            printf("Aucun fichier de configuration de notification trouvé pour le custom {$custom}\n");
            continue;
        }
        $loadedNotificationXml = simplexml_load_file($notificationFilePath);
        
        if ($loadedNotificationXml) {
            $file = file_get_contents($configPath);
            $file = json_decode($file, true);

            $file['config']['maarchDirectory'] = (string)$loadedNotificationXml->CONFIG->MaarchDirectory;
            $file['config']['customID'] = (string)$loadedNotificationXml->CONFIG->customID;
            if (empty($file['config']['maarchUrl'])) {
                $file['config']['maarchUrl'] = (string)$loadedNotificationXml->CONFIG->applicationUrl;
            }

            $fp = fopen($configPath, 'w+');
            fwrite($fp, json_encode($file, JSON_PRETTY_PRINT));
            fclose($fp);

            $notifications = \Notification\models\NotificationModel::get(['select' => ['notification_sid', 'notification_id']]);
            $user          = \User\models\UserModel::get(['select' => ['id'], 'orderBy' => ["user_id='superadmin' desc"], 'limit' => 1]);
            $GLOBALS['id'] = $user[0]['id'];
            $language      = \SrcCore\models\CoreConfigModel::getLanguage();
            if (file_exists("custom/{$custom}/src/core/lang/lang-{$language}.php")) {
                require_once("custom/{$custom}/src/core/lang/lang-{$language}.php");
            }
            require_once("src/core/lang/lang-{$language}.php");
            foreach ($notifications as $notification) {
                \Notification\models\NotificationScheduleModel::createScriptNotification(['notification_sid' => $notification['notification_sid'], 'notification_id' => $notification['notification_id']]);
            }
            printf("Si les scripts de notifications sont lancés dans la crontab, il faut modifier les chemins. Tous les scripts sont dans le dossier : bin/notification/scripts/ \n");

            $migrated++;
        }
    }
}

foreach ($nonReadableFiles as $file) {
    printf("The file %s it is not readable or not writable.\n", $file);
}

printf($migrated . " custom(s) avec config.xml (notifications) trouvé(s) et migré(s).\n");

function formatXml($simpleXMLElement)
{
    $xmlDocument = new DOMDocument('1.0');
    $xmlDocument->preserveWhiteSpace = false;
    $xmlDocument->formatOutput = true;
    $xmlDocument->loadXML($simpleXMLElement->asXML());

    return $xmlDocument->saveXML();
}
