# Dockerfile
FROM php:8.4-apache

# enable apache mod_rewrite
RUN a2enmod rewrite

# install extensions & Xdebug
RUN apt-get update \
  && apt-get install -y git unzip libzip-dev \
  && docker-php-ext-install pdo_mysql zip \
  && pecl install xdebug \
  && docker-php-ext-enable xdebug

# copy our PHP config (optional)
COPY docker-php-errors.ini /usr/local/etc/php/conf.d/

# set workdir
WORKDIR /var/www/html

# composer (if you want it in container)
# COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# expose apache port
EXPOSE 80
