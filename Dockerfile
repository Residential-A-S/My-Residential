# Dockerfile
FROM php:8.4-apache

# turn on full PHP error reporting
COPY docker-php-errors.ini /usr/local/etc/php/conf.d/

# install any PHP extensions you need
RUN apt-get update \
 && docker-php-ext-install pdo_mysql mysqli \
 && a2enmod rewrite \
 && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html
