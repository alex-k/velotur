FROM tutum/lamp
MAINTAINER Alex Kochetov <alex@kochetov.com>

ADD ./configs/etc/apache2/sites-enabled/000-default.conf /etc/apache2/sites-enabled/000-default.conf
ADD ./configs/etc/php5/apache2/php.ini /etc/php5/apache2/php.ini

RUN apt-get update && apt-get -y install git vim curl 
RUN a2enmod rewrite

ADD ./mysql/init_db.sh ./mysql/staging_mysql_data.sql /tmp/
RUN /tmp/init_db.sh


#RUN cd /var/www && git clone https://github.com/alex-k/velotur.git --branch develop --single-branch /var/www/velotur.ru/
#
#RUN cd /var/www/velotur.ru/www && php -r "readfile('https://getcomposer.org/installer');" | php
#RUN cd /var/www/velotur.ru/www && php composer.phar install

#ENTRYPOINT "/tmp/run_db.sh"

ADD run.sh /run.sh
RUN chmod 755 /*.sh
CMD ["/run.sh"]
