#!/bin/sh
set -e
set -x

#echo "=> Cloning app [branch $BRANCH]..."
#git clone https://github.com/alex-k/velotur.git --depth 1 --branch $BRANCH --single-branch $VOLUME_WWW

#echo "=> Downloading composer ..."
#cd $VOLUME_WWW/www && php -r "readfile('https://getcomposer.org/installer');" | php

echo "=> Touch build.log file..."
cd /app && date > build.log

echo "=> Runing composer install ..."
cd /app && php composer.phar install

