#!/bin/sh
set -e
set -x


VOLUME_HOME="/var/lib/mysql"
VOLUME_WWW="/var/www/velotur.ru"

echo "=> Downloading app ..."
git clone https://github.com/alex-k/velotur.git --depth 1 --branch develop --single-branch $VOLUME_WWW

echo "=> Installing MySQL ..."
mysql_install_db > /dev/null 2>&1

echo "=> Runing mysql ..."
service mysql start
echo '=> Populate initial db'; 
mysqladmin  create activeinfo_newvt
mysql -e "GRANT ALL ON activeinfo_newvt.* TO activeinfo_newvt@localhost IDENTIFIED BY 'vt123' WITH GRANT OPTION"
mysql -u activeinfo_newvt -pvt123 activeinfo_newvt < $VOLUME_WWW/mysql/mysql_data.sql
echo "=> Done!"  

/create_mysql_admin_user.sh

echo "=> Downloading composer ..."
cd $VOLUME_WWW/www && php -r "readfile('https://getcomposer.org/installer');" | php
echo "=> Runing composer install ..."
cd $VOLUME_WWW/www && php composer.phar install
echo "=> Exiting mysql ..."
service mysql stop
