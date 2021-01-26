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
    $visaConfigPath         = "modules/visa/batch/config/config.xml";
    if (file_exists($configPath)) {
        if (!is_readable($configPath) || !is_writable($configPath)) {
            $nonReadableFiles[] = $configPath;
            continue;
        }
        if (is_readable($visaConfigPath) && is_writable($visaConfigPath)) {
            $visaFilePath = $visaConfigPath;
        } elseif (is_readable("custom/{$custom}/{$visaConfigPath}") && is_writable("custom/{$custom}/{$visaConfigPath}")) {
            $visaFilePath = "custom/{$custom}/{$visaConfigPath}";
        } else {
            printf("Aucun fichier de configuration de parapheur externe trouvé pour le custom {$custom}\n");
            continue;
        }
        $loadedVisaXml = simplexml_load_file($visaFilePath);
        
        if ($loadedVisaXml) {
            $file = file_get_contents($configPath);
            $file = json_decode($file, true);

            $signatureBook = [];
            $signatureBook['validatedStatus']          = (string)$loadedVisaXml->CONFIG->validatedStatus;
            $signatureBook['validatedStatusOnlyVisa']  = (string)$loadedVisaXml->CONFIG->validatedStatusOnlyVisa;
            $signatureBook['refusedStatus']            = (string)$loadedVisaXml->CONFIG->refusedStatus;
            $signatureBook['validatedStatusAnnot']     = (string)$loadedVisaXml->CONFIG->validatedStatusAnnot;
            $signatureBook['refusedStatusAnnot']       = (string)$loadedVisaXml->CONFIG->refusedStatusAnnot;
            $signatureBook['userWS']                   = (string)$loadedVisaXml->CONFIG->userWS;
            $signatureBook['passwordWS']               = (string)$loadedVisaXml->CONFIG->passwordWS;
            $file['signatureBook'] = $signatureBook;

            if (empty($file['config']['maarchUrl'])) {
                $file['config']['maarchUrl'] = (string)$loadedVisaXml->CONFIG->applicationUrl;
            }

            $fp = fopen($configPath, 'w+');
            fwrite($fp, json_encode($file, JSON_PRETTY_PRINT));
            fclose($fp);

            createScript();

            $migrated++;
        }
    }
}

foreach ($nonReadableFiles as $file) {
    printf("The file %s it is not readable or not writable.\n", $file);
}

printf($migrated . " custom(s) avec config.xml (visa) trouvé(s) et migré(s).\n");

function formatXml($simpleXMLElement)
{
    $xmlDocument = new DOMDocument('1.0');
    $xmlDocument->preserveWhiteSpace = false;
    $xmlDocument->formatOutput = true;
    $xmlDocument->loadXML($simpleXMLElement->asXML());

    return $xmlDocument->saveXML();
}

function createScript()
{
    $corePath = str_replace('migration/20.10', '', __DIR__);
    $config   = $corePath . \SrcCore\models\CoreConfigModel::getConfigPath();

    if (!empty($GLOBALS['customId'])) {
        $folderScript = $corePath.'custom/'.$GLOBALS['customId'].'/bin/signatureBook/scripts/';
        if (!file_exists($folderScript)) {
            mkdir($folderScript, 0777, true);
        }
        $scriptPath = $folderScript . 'retrieveMailFromExternalSignatoryBook.sh';
    } else {
        $scriptPath = $corePath.'bin/signatureBook/scripts/retrieveMailFromExternalSignatoryBook.sh';
    }
    $fileToOpen = fopen($scriptPath, 'w+');

    fwrite($fileToOpen, '#!/bin/sh');
    fwrite($fileToOpen, "\n");
    fwrite($fileToOpen, 'cd ' . $corePath . 'bin/signatureBook/');
    fwrite($fileToOpen, "\n");
    fwrite($fileToOpen, 'filePath=\''.$corePath.'bin/signatureBook/process_mailsFromSignatoryBook.php\'');
    fwrite($fileToOpen, "\n");
    fwrite($fileToOpen, 'php $filePath -c ' . $config);
    fwrite($fileToOpen, "\n");
    fclose($fileToOpen);
    shell_exec('chmod +x '. $scriptPath);

    printf("Si le script process_mailsFromSignatoryBook.php est lancé dans la crontab, il faut modifier le chemin comme ceci : " . $scriptPath . "\n");
}
