<?php

require '../../vendor/autoload.php';

chdir('../..');

$path = "apps/maarch_entreprise/xml/config.xml";
if (file_exists($path)) {
    if (!is_readable($path)) {
        printf("[ERROR] Fichier apps/maarch_entreprise/xml/config.xml non lisible.\n");
    } else {
        $loadedXml = simplexml_load_file($path);

        if ($loadedXml) {
            $i = 0;
            $database = [];
            while (!empty($loadedXml->CONFIG->databaseserver[$i])) {
                $database[] = [
                    "server"    => (string)$loadedXml->CONFIG->databaseserver[$i],
                    "port"      => (string)$loadedXml->CONFIG->databaseserverport[$i],
                    "type"      => (string)$loadedXml->CONFIG->databasetype[$i],
                    "name"      => (string)$loadedXml->CONFIG->databasename[$i],
                    "user"      => (string)$loadedXml->CONFIG->databaseuser[$i],
                    "password"  => (string)$loadedXml->CONFIG->databasepassword[$i]
                ];
                ++$i;
            }

            $timezone = (string)$loadedXml->CONFIG->timezone;
            $timezone = !empty($timezone) ? $timezone : 'Europe/Paris';

            $jsonFile = [
                'config'    => [
                    'lang'                      => (string)$loadedXml->CONFIG->lang,
                    'applicationName'           => (string)$loadedXml->CONFIG->applicationname,
                    'cookieTime'                => 10080,
                    'timezone'                  => $timezone,
                    'lockAdvancedPrivileges'    => false
                ],
                'database'  => $database
            ];

            $fp = fopen("apps/maarch_entreprise/xml/config.json", 'w');
            fwrite($fp, json_encode($jsonFile, JSON_PRETTY_PRINT));
            fclose($fp);

            unlink($path);
            printf("[SUCCESS] Fichier apps/maarch_entreprise/xml/config.xml migré.\n");
        }
    }
}

$customs =  scandir('custom');
foreach ($customs as $custom) {
    if (in_array($custom, ['custom.json', 'custom.xml', '.', '..'])) {
        continue;
    }

    $path = "custom/{$custom}/apps/maarch_entreprise/xml/config.xml";
    if (file_exists($path)) {
        if (!is_readable($path)) {
            printf("[ERROR] Fichier custom/{$custom}/apps/maarch_entreprise/xml/config.xml non lisible.\n");
            continue;
        }
        $loadedXml = simplexml_load_file($path);

        $timezone = (string)$loadedXml->CONFIG->timezone;
        $timezone = !empty($timezone) ? $timezone : 'Europe/Paris';

        if ($loadedXml) {
            $jsonFile = [
                'config'    => [
                    'lang'                      => (string)$loadedXml->CONFIG->lang,
                    'applicationName'           => (string)$loadedXml->CONFIG->applicationname,
                    'cookieTime'                => 10080,
                    'timezone'                  => $timezone,
                    'lockAdvancedPrivileges'    => false
                ],
                'database'  => [
                    [
                        "server"    => (string)$loadedXml->CONFIG->databaseserver,
                        "port"      => (string)$loadedXml->CONFIG->databaseserverport,
                        "type"      => (string)$loadedXml->CONFIG->databasetype,
                        "name"      => (string)$loadedXml->CONFIG->databasename,
                        "user"      => (string)$loadedXml->CONFIG->databaseuser,
                        "password"  => (string)$loadedXml->CONFIG->databasepassword
                    ]
                ]
            ];

            $fp = fopen("custom/{$custom}/apps/maarch_entreprise/xml/config.json", 'w');
            fwrite($fp, json_encode($jsonFile, JSON_PRETTY_PRINT));
            fclose($fp);

            unlink($path);
            printf("[SUCCESS] Fichier custom/{$custom}/apps/maarch_entreprise/xml/config.xml migré.\n");
        }
    }
}
