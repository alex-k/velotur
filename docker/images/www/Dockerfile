FROM tutum/lamp
MAINTAINER Alex Kochetov <alex@kochetov.com>

ADD ./configs/etc/apache2/sites-enabled/000-default.conf /etc/apache2/sites-enabled/000-default.conf
ADD ./configs/etc/php5/apache2/php.ini /etc/php5/apache2/php.ini

#ADD ../../../www /var/www/testfiles
#ADD ../../../mysql/mysql_data.sql /tmp/mysql_data.sql

RUN apt-get update && apt-get -y install git vim curl 
RUN a2enmod rewrite



#RUN cd /var/www && git clone https://github.com/alex-k/velotur.git --branch develop --single-branch /var/www/velotur.ru/
#
#RUN cd /var/www/velotur.ru/www && php -r "readfile('https://getcomposer.org/installer');" | php
#RUN cd /var/www/velotur.ru/www && php composer.phar install

#ENTRYPOINT "/tmp/run_db.sh"

#ADD init_db.sh /init_db.sh
ADD build.sh /build.sh
ADD run.sh /run.sh
RUN chmod 755 /*.sh

RUN /build.sh

EXPOSE 80 3306

CMD ["/run.sh"]
