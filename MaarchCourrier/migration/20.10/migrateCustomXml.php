<?php

require '../../vendor/autoload.php';

chdir('../..');


$file = 'custom/custom.xml';

if (is_file($file)) {
    if (!is_readable($file) || !is_writable($file)) {
        printf("File custom/custom.xml is not readable or not writable.\n");
        exit;
    }
    $loadedXml = simplexml_load_file($file);

    $jsonFile = [];
    if ($loadedXml) {
        foreach ($loadedXml->custom as $value) {
            $ip = null;
            if (!empty((string)$value->ip)) {
                $ip = (string)$value->ip;
            } elseif ((string)$value->external_domain) {
                $ip = (string)$value->external_domain;
            } elseif ((string)$value->domain) {
                $ip = (string)$value->domain;
            }
            $jsonFile[] = [
                'id'                => (string)$value->custom_id,
                'uri'               => $ip,
                'path'              => (string)$value->path
            ];
        }

        $fp = fopen('custom/custom.json', 'w');
        fwrite($fp, json_encode($jsonFile, JSON_PRETTY_PRINT));
        fclose($fp);
    }
    unlink($file);
    printf("Fichier custom/custom.xml migré en fichier json.\n");
}
