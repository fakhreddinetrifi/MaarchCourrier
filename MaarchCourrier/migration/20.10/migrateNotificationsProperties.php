<?php

require '../../vendor/autoload.php';

chdir('../..');

$customs =  scandir('custom');
$migrated = 0;
foreach ($customs as $custom) {
    if (in_array($custom, ['custom.json', 'custom.xml', '.', '..'])) {
        continue;
    }

    \SrcCore\models\DatabasePDO::reset();
    new \SrcCore\models\DatabasePDO(['customId' => $custom]);

    $notifications = \Notification\models\NotificationModel::get([
        'select'    => ['diffusion_properties', 'notification_sid'],
        'where'     => ['diffusion_type = ?'],
        'data'      => ['user']
    ]);
    foreach ($notifications as $notification) {
        $users = explode(',', $notification['diffusion_properties']);
        if (!empty($users)) {
            $users = \User\models\UserModel::get(['select' => ['id'], 'where' => ['user_id in (?)'], 'data' => [$users]]);
            $users = array_column($users, 'id');
            if (!empty($users)) {
                $users = implode(',', $users);
            } else {
                $users = null;
            }
            \Notification\models\NotificationModel::update(['notification_sid' => $notification['notification_sid'], 'diffusion_properties' => $users]);
        }
    }
    $migrated++;
}

printf("migrateNotificationsProperties : " . $migrated . " custom(s) trouvé(s) et migré(s).\n");
