#!/bin/sh
path='/var/www/html/MaarchCourrier/bin/notification/'
cd $path
php 'stack_letterbox_alerts.php' -c /var/www/html/MaarchCourrier/apps/maarch_entreprise/xml/config.json
php 'process_event_stack.php' -c /var/www/html/MaarchCourrier/apps/maarch_entreprise/xml/config.json -n RET1
cd $path
php 'process_email_stack.php' -c /var/www/html/MaarchCourrier/apps/maarch_entreprise/xml/config.json
