<?php

require '../../vendor/autoload.php';

chdir('../..');

$customs =  scandir('custom');


foreach ($customs as $custom) {
    if (in_array($custom, ['custom.json', 'custom.xml', '.', '..'])) {
        continue;
    }

    \SrcCore\models\DatabasePDO::reset();
    new \SrcCore\models\DatabasePDO(['customId' => $custom]);

    $configuration = [];

    $path = "custom/{$custom}/apps/maarch_entreprise/xml/mapping_sso.xml";
    if (file_exists($path)) {
        if (!is_readable($path)) {
            printf("[ERROR] Fichier {$path} non lisible.\n");
            continue;
        }
        $loadedXml = simplexml_load_file($path);

        if (!empty($loadedXml)) {
            $configuration['url'] = (string)$loadedXml->WEB_SSO_URL;

            $configuration['mapping'] = [];

            if (isset($loadedXml->USER_ID)) {
                $configuration['mapping'][] = [
                    'ssoId'    => (string)$loadedXml->USER_ID,
                    'maarchId' => 'login'
                ];
            }
            $configuration = !empty($configuration) ? json_encode($configuration, JSON_UNESCAPED_SLASHES) : '{"url":"","mapping":[{"ssoId":"","maarchId":"login"}]}';
            \Configuration\models\ConfigurationModel::create(['privilege' => 'admin_sso', 'value' => $configuration]);
            printf("Migration mapping SSO (CUSTOM {$custom}) : fichier de configuration mapping_sso.xml trouvé et migré.\n");
            continue;
        }
    }

    \Configuration\models\ConfigurationModel::create(['privilege' => 'admin_sso', 'value' => '{"url":"","mapping":[{"ssoId":"","maarchId":"login"}]}']);
}
