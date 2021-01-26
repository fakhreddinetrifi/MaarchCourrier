<?php

require '../../vendor/autoload.php';

chdir('../..');

$path = "modules/ldap/xml/config.xml";
if (is_file($path)) {
    if (!is_readable($path) || !is_writable($path)) {
        printf("[ERROR] Fichier {$path} non lisible ou non inscriptible.\n");
    } else {
        $xmlfile = simplexml_load_file($path);

        if ($xmlfile) {
            $i = 0;
            foreach ($xmlfile->config->ldap as $item) {
                if ((string)$item->type_ldap == 'openLDAP') {
                    $xmlfile->config->ldap[$i]->baseDN = $item->domain;
                    $xmlfile->config->ldap[$i]->domain = $item->hostname;
                    unset($xmlfile->config->ldap[$i]->hostname);
                }
                ++$i;
            }

            $res = $xmlfile->asXML();
            $fp = fopen($path, "w+");
            if ($fp) {
                fwrite($fp, $res);
            }

            $migrated++;
        }
    }
}

$customs =  scandir('custom');
foreach ($customs as $custom) {
    if (in_array($custom, ['custom.json', 'custom.xml', '.', '..'])) {
        continue;
    }

    $path = "custom/{$custom}/modules/ldap/xml/config.xml";
    if (is_file($path)) {
        if (!is_readable($path) || !is_writable($path)) {
            printf("[ERROR] Fichier {$path} non lisible ou non inscriptible.\n");
        } else {
            $xmlfile = simplexml_load_file($path);

            if ($xmlfile) {
                $i = 0;
                foreach ($xmlfile->config->ldap as $item) {
                    if ((string)$item->type_ldap == 'openLDAP') {
                        $xmlfile->config->ldap[$i]->baseDN = $item->domain;
                        $xmlfile->config->ldap[$i]->domain = $item->hostname;
                        unset($xmlfile->config->ldap[$i]->hostname);
                    }
                    ++$i;
                }

                $res = $xmlfile->asXML();
                $fp = fopen($path, "w+");
                if ($fp) {
                    fwrite($fp, $res);
                }

                $migrated++;
            }
        }
    }
}
