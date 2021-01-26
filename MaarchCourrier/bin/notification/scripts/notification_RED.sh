#!/bin/sh
path='/var/www/html/MaarchCourrier/bin/notification/'
cd $path
php 'process_event_stack.php' -c /var/www/html/MaarchCourrier/apps/maarch_entreprise/xml/config.json -n RED
cd $path
php 'process_email_stack.php' -c /var/www/html/MaarchCourrier/apps/maarch_entreprise/xml/config.json
