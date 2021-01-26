<?php

require '../../vendor/autoload.php';

chdir('../..');

$migrated = 0;
$customs  = scandir('custom');

foreach ($customs as $custom) {
    if (in_array($custom, ['custom.json', 'custom.xml', '.', '..'])) {
        continue;
    }

    $oldImgFolderPath = "custom/{$custom}/apps/maarch_entreprise/img";
    if (is_dir($oldImgFolderPath)) {
        if (!is_readable($oldImgFolderPath) || !is_writable($oldImgFolderPath)) {
            printf("WARNING : The folder %s is not readable or not writable.\n", $oldImgFolderPath);
            continue;
        }

        $newImgFolderPath = "custom/{$custom}/img";
        if (!is_dir($newImgFolderPath)) {
            if (!@mkdir($newImgFolderPath, 0755, true)) {
                printf("WARNING : The folder %s can not be created.\n", $newImgFolderPath);
                continue;
            }
        } elseif (!is_readable($newImgFolderPath) || !is_writable($newImgFolderPath)) {
            printf("WARNING : The folder %s is not readable or not writable.\n", $newImgFolderPath);
            continue;
        }

        $images = scandir($oldImgFolderPath);
        foreach ($images as $image) {
            if (in_array($image, ['.', '..'])) {
                continue;
            }
            if (!rename($oldImgFolderPath . '/' . $image, $newImgFolderPath . '/' . $image)) {
                printf("WARNING : The file %s can not be moved to {$newImgFolderPath} : permission denied.\n", $image);
            }
        }
        
        rmdir($oldImgFolderPath);
        $migrated++;
    }
}

printf($migrated . " dossier(s) custom/custom_id/apps/maarch_entreprise/img trouvé(s) et migré(s).\n");
