FROM tutum/lamp
MAINTAINER Alex Kochetov <alex@kochetov.com>

ADD ./configs/etc/apache2/sites-enabled/000-default.conf /etc/apache2/sites-enabled/000-default.conf
ADD ./configs/etc/php5/apache2/php.ini /etc/php5/apache2/php.ini

RUN apt-get update && apt-get -y install git vim curl
RUN a2enmod rewrite

RUN cd /var/www && git clone https://github.com/alex-k/velotur.git /var/www/velotur.ru/

RUN mysql < /var/www/velotur.ru/mysql/mysql.sql
RUN mysql < /var/www/velotur.ru/mysql/activeinfo_newvt.sql

# Configure apache
RUN chown -R www-data:www-data /var/www

EXPOSE 80
