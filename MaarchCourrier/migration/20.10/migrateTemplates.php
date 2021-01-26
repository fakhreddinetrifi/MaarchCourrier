<?php

use Template\models\TemplateModel;

require '../../vendor/autoload.php';
include_once('../../vendor/tinybutstrong/opentbs/tbs_plugin_opentbs.php');

const DATA_CONTACT_NOTIFICATION = [
    'attachmentRecipient.company'                   => '[sender.company]',
    'attachmentRecipient.department'                => '[sender.department]',
    'attachmentRecipient.civility'                  => '[sender.civility]',
    'attachmentRecipient.lastname'                  => '[sender.lastname]',
    'attachmentRecipient.firstname'                 => '[sender.firstname]',
    'attachmentRecipient.function'                  => '[sender.function]',
    'attachmentRecipient.postal_address;strconv=no' => '[sender.postal_address;strconv=no]',
    'attachmentRecipient.postal_address'            => '[sender.postal_address]',
    'attachmentRecipient.address_number'            => '[sender.address_number]',
    'attachmentRecipient.address_street'            => '[sender.address_street]',
    'attachmentRecipient.address_additional1'       => '[sender.address_additional1]',
    'attachmentRecipient.address_additional2'       => '[sender.address_additional2]',
    'attachmentRecipient.address_town'              => '[sender.address_town]',
    'attachmentRecipient.address_postcode'          => '[sender.address_postcode]',
    'attachmentRecipient.address_country'           => '[sender.address_country]',
    'attachmentRecipient.phone'                     => '[sender.phone]',
    'attachmentRecipient.email'                     => '[sender.email]',
    'contact.company'              => '[sender.company]',
    'contact.department'           => '[sender.department]',
    'contact.civility'             => '[sender.civility]',
    'contact.lastname;block=tr'    => '[sender.lastname;block=tr]',
    'contact.lastname'             => '[sender.lastname]',
    'contact.firstname'            => '[sender.firstname]',
    'contact.lastname'             => '[sender.lastname]',
    'contact.firstname'            => '[sender.firstname]',
    'contact.function'             => '[sender.function]',
    'contact.address_number'       => '[sender.address_number]',
    'contact.address_street'       => '[sender.address_street]',
    'contact.address_additional1'  => '[sender.address_additional1]',
    'contact.address_additional2'  => '[sender.address_additional2]',
    'contact.address_town'         => '[sender.address_town]',
    'contact.address_postcode'     => '[sender.address_postcode]',
    'contact.address_country'      => '[sender.address_country]',
    'contact.phone'                => '[sender.phone]',
    'contact.email'                => '[sender.email]',
];

chdir('../..');

$customs = scandir('custom');

foreach ($customs as $custom) {
    if (in_array($custom, ['custom.json', 'custom.xml', '.', '..'])) {
        continue;
    }

    \SrcCore\models\DatabasePDO::reset();
    new \SrcCore\models\DatabasePDO(['customId' => $custom]);

    $migrated    = 0;
    $nonMigrated = 0;

    $templates = TemplateModel::get(['select' => ['template_content', 'template_id'], 'where' => ['template_target = ?', 'template_type = ?'], 'data' => ['notifications', 'HTML']]);

    foreach ($templates as $template) {
        $content = $template['template_content'];

        $newContent = $content;
        foreach (DATA_CONTACT_NOTIFICATION as $key => $value) {
            $newContent = str_replace('[' . $key . ']', $value, $newContent);
        }

        if ($content != $newContent) {
            TemplateModel::update([
                        'set'   => [
                            'template_content' => $newContent
                        ],
                        'where' => ['template_id = ?'],
                        'data'  => [$template['template_id']]
                    ]);
            $migrated++;
        } else {
            $nonMigrated++;
        }
    }

    printf("Migration de Modèles de documents (CUSTOM {$custom}) : " . $migrated . " Modèle(s) migré(s), $nonMigrated non migré(s).\n");
}
