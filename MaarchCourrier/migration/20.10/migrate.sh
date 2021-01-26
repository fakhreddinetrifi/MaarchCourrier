#!/bin/sh
php ./migrateConfigXml.php # mettre en premier
php ./migrateNotificationsProperties.php
php ./migrateNotificationsConfig.php
php ./migrateRemoteSignatureBookConfig.php
php ./migrateExportSedaConfig.php
php ./migrateImages.php
php ./migrateCustomLang.php
php ./migrateBasketListDisplay.php
php ./migrateTemplates.php
php ./migrateSavedQueries.php
php ./migrateSsoMapping.php
php ./migrateOpenLdapConfig.php
php ./migrateCustomXml.php # mettre en avant dernier
php ./migrateSQL.php # mettre en dernier
