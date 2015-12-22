#!/bin/bash

VOLUME_WWW="/var/www/velotur.ru"

echo "=> Runing composer install ..."
cd $VOLUME_WWW/www && php composer.phar install

source /etc/apache2/envvars
tail -F /var/log/apache2/* &
exec apache2 -D FOREGROUND

#exec supervisord -n
