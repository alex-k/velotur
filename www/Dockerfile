FROM tutum/apache-php
MAINTAINER Alex Kochetov <alex@kochetov.com>

ADD ./configs/etc/apache2/sites-enabled/000-default.conf /etc/apache2/sites-enabled/000-default.conf
ADD ./configs/etc/php5/apache2/php.ini /etc/php5/apache2/php.ini



RUN apt-get update && apt-get -y install git vim curl mysql-client
RUN a2enmod rewrite

RUN locale-gen ru_RU && locale-gen ru_RU.UTF-8 && update-locale

#RUN apt-get -y install mysql-server
#RUN mysql_install_db

ADD build.sh /build.sh
ADD run.sh /run.sh
RUN chmod 755 /*.sh

RUN mkdir -m 0777 /var/log/velotur/

#ADD mysql.sql /mysql_data.sql
RUN date > build.log

ADD app /app

RUN /build.sh $VT_BRANCH

EXPOSE 80

CMD ["/run.sh"]
