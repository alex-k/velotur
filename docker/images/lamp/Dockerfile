FROM tutum/lamp
MAINTAINER Alex Kochetov <alex@kochetov.com>

ADD ./configs/etc/apache2/sites-enabled/000-default.conf /etc/apache2/sites-enabled/000-default.conf
ADD ./configs/etc/php5/apache2/php.ini /etc/php5/apache2/php.ini

RUN apt-get update && apt-get -y install git vim curl
RUN a2enmod rewrite

#RUN curl https://github.com/alex-k/velotur/archive/master.zip | tar -xf- -C /var/www/velotur.ru
RUN cd /var/www && git clone https://github.com/alex-k/velotur.git /var/www/velotur.ru/

# Configure apache
RUN chown -R www-data:www-data /var/www

EXPOSE 80
