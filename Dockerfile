# Dockerfile
FROM php:8.4-apache

# enable apache mod_rewrite
RUN a2enmod rewrite
COPY 000-default.conf /etc/apache2/sites-available/000-default.conf

RUN docker-php-ext-install pdo_mysql

# set workdir
WORKDIR /var/www/html

# expose apache port
EXPOSE 80
