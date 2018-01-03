FROM php:5.6-apache

MAINTAINER Alex Kochetov <alex@kochetov.com>

RUN apt-get update && apt-get install -y \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libmcrypt-dev \
        libpng12-dev \
    && docker-php-ext-install -j$(nproc) iconv mcrypt mysql pdo pdo_mysql mbstring\
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install -j$(nproc) gd


RUN apt-get update && apt-get -y install telnet dnsutils
RUN apt-get update && apt-get -y install git vim curl mysql-client zip unzip
RUN a2enmod rewrite

RUN apt-get install locales
RUN locale-gen ru_RU && locale-gen ru_RU.UTF-8 && update-locale

ADD ./configs/etc/apache2/sites-enabled/000-default.conf /etc/apache2/sites-enabled/000-default.conf
ADD ./configs/php.ini /usr/local/etc/php/conf.d/velotur.ini

RUN date > build.log

ADD app /app
ADD api /api

RUN mkdir -m 0777 /var/log/velotur/

ADD build.sh /build.sh
RUN sh /build.sh $VT_BRANCH
