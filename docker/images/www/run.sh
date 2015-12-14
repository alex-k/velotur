#!/bin/bash

VOLUME_HOME="/var/lib/mysql"
VOLUME_WWW="/var/www/velotur.ru"

sed -ri -e "s/^upload_max_filesize.*/upload_max_filesize = ${PHP_UPLOAD_MAX_FILESIZE}/" \
    -e "s/^post_max_size.*/post_max_size = ${PHP_POST_MAX_SIZE}/" /etc/php5/apache2/php.ini

if [[ ! -d $VOLUME_HOME/mysql ]]; then
    echo "=> An empty or uninitialized MySQL volume is detected in $VOLUME_HOME"
    echo "=> Installing MySQL ..."
    mysql_install_db > /dev/null 2>&1
    echo "=> Downloading db structure ..."
    git clone https://github.com/alex-k/velotur.git --branch develop --single-branch /tmp/
    echo '=> Populate initial db'; 
    /init_db.sh 
    echo "=> Done!"  
    /create_mysql_admin_user.sh
else
    echo "=> Using an existing volume of MySQL"
fi

exec supervisord -n

if [[ ! -d $VOLUME_WWW ]]; then
    echo "=> An empty application directory is detected in $VOLUME_WWW"
    echo "=> Downloading app ..."
    git clone https://github.com/alex-k/velotur.git --branch develop --single-branch /var/www/velotur.ru/
    echo "=> Downloading composer ..."
    cd /var/www/velotur.ru/www && php -r "readfile('https://getcomposer.org/installer');" | php
    echo "=> Runing composer install ..."
    cd /var/www/velotur.ru/www && php composer.phar install
fi

