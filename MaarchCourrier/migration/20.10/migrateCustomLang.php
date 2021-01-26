<?php

require '../../vendor/autoload.php';

chdir('../..');

$migrated = 0;
$customs  = scandir('custom');

foreach ($customs as $custom) {
    if (in_array($custom, ['custom.json', 'custom.xml', '.', '..'])) {
        continue;
    }

    $customLangFolderPath = "custom/{$custom}/lang";
    if (is_dir($customLangFolderPath)) {
        if (!is_readable($customLangFolderPath) || !is_writable($customLangFolderPath)) {
            printf("WARNING : The folder %s is not readable or not writable.\n", $customLangFolderPath);
            continue;
        }

        $customLangFiles = scandir($customLangFolderPath);
        foreach ($customLangFiles as $customLangFile) {
            if (strtolower(pathinfo($customLangFile, PATHINFO_EXTENSION)) == 'ts') {
                if (!is_readable($customLangFolderPath . '/' . $customLangFile) || !is_writable($customLangFolderPath . '/' . $customLangFile)) {
                    printf("WARNING : The file %s is not readable or not writable.\n", $customLangFolderPath . '/' . $customLangFile);
                    continue;
                }
                $fileContent = trim(file_get_contents($customLangFolderPath . '/' . $customLangFile));
                $fileContent = trim(substr($fileContent, strpos($fileContent, "{"), -2));  // get content from first "{" , and remove last "};"
                $jsonContent = json_decode(rtrim($fileContent, ",") . "}");
                file_put_contents($customLangFolderPath . '/' . str_ireplace(".ts", ".json", $customLangFile), json_encode($jsonContent, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
                unlink($customLangFolderPath . '/' . $customLangFile);
                $migrated++;
            }
        }
    }
}

printf($migrated . " fichier(s) custom/custom_id/lang/*.ts trouvé(s) et migré(s).\n");
