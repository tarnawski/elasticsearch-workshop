FROM php:7.3-fpm

MAINTAINER Tomasz Tarnawski <tarnawski27@gmail.com>

ADD ./php.ini /usr/local/etc/php/

RUN apt-get update && apt-get install -y libicu-dev
RUN docker-php-ext-install intl mbstring opcache pdo_mysql
