#!/bin/sh
set -e
set -x


VOLUME_WWW="/var/www/velotur.ru"

echo "=> Downloading app ..."
git clone https://github.com/alex-k/velotur.git --depth 1 --branch develop --single-branch $VOLUME_WWW

echo "=> Downloading composer ..."
cd $VOLUME_WWW/www && php -r "readfile('https://getcomposer.org/installer');" | php

echo "=> Runing composer install ..."
cd $VOLUME_WWW/www && php composer.phar install

