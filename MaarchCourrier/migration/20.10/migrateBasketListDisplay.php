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

    $migrated = 0;

    $groupBaskets = \Basket\models\GroupBasketModel::get([
        'select' => ['id', 'list_display']
    ]);

    foreach ($groupBaskets as $groupBasket) {
        $oldListDisplay = json_decode($groupBasket['list_display']);
        $listDisplay = [
            'templateColumns' => count($oldListDisplay),
            'subInfos' => $oldListDisplay
        ];
        $listDisplay = json_encode($listDisplay);

        \Basket\models\GroupBasketModel::update([
            'set'   => ['list_display' => $listDisplay],
            'where' => ['id = ?'],
            'data'  => [$groupBasket['id']]
        ]);
        $migrated++;
    }



    printf("Migration bannettes list display (CUSTOM {$custom}) : " . $migrated . " Bannettes(s) trouvée(s) et migrée(s).\n");
}
