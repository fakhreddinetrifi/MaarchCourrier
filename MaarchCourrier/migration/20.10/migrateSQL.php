<?php

require '../../vendor/autoload.php';

chdir('../..');

$REQUESTS = [
    "DROP TABLE IF EXISTS saved_queries"
];


if (is_file("apps/maarch_entreprise/xml/config.json")) {
    \SrcCore\models\DatabasePDO::reset();
    $db = new \SrcCore\models\DatabasePDO();

    foreach ($REQUESTS as $query) {
        $db->query($query);
    }

    printf("Exécution du dernier script sql pour le socle.\n");
}


$customs =  scandir('custom');
foreach ($customs as $custom) {
    if (in_array($custom, ['custom.json', 'custom.xml', '.', '..'])) {
        continue;
    }

    if (is_file("custom/{$custom}/apps/maarch_entreprise/xml/config.json")) {
        \SrcCore\models\DatabasePDO::reset();
        $db = new \SrcCore\models\DatabasePDO(['customId' => $custom]);

        foreach ($REQUESTS as $query) {
            $db->query($query);
        }

        printf("Exécution du dernier script sql pour le custom {$custom}.\n");
    }
}
