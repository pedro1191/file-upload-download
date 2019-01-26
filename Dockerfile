FROM php:7.2-apache

RUN apt-get update
RUN a2enmod rewrite

RUN apt-get update && docker-php-ext-install pdo pdo_mysql

COPY . /var/www/html
COPY ./.docker/vhost.conf /etc/apache2/sites-available/000-default.conf

WORKDIR /var/www/html