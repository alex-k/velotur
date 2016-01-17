#!/bin/sh
set -e
set -x

BRANCH=$1

if [ -z "$BRANCH"];
then
  BRANCH='abcdefg'
fi

VOLUME_WWW="/var/www/velotur.ru"

echo "=> Cloning app [branch $BRANCH]..."
git clone https://github.com/alex-k/velotur.git --depth 1 --branch $BRANCH --single-branch $VOLUME_WWW

echo "=> Downloading composer ..."
cd $VOLUME_WWW/www && php -r "readfile('https://getcomposer.org/installer');" | php

echo "=> Runing composer install ..."
cd $VOLUME_WWW/www && php composer.phar install

