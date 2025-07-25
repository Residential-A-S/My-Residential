# Dockerfile
FROM php:8.4-apache

# enable apache mod_rewrite
RUN a2enmod rewrite \
  && sed -ri \
      -e 's!DocumentRoot /var/www/html!DocumentRoot /var/www/html/public!g' \
      -e 's!<Directory /var/www/html>!<Directory /var/www/html/public>!g' \
      -e 's!AllowOverride None!AllowOverride All!g' \
      /etc/apache2/apache2.conf


# install extensions & Xdebug
RUN apt-get update \
  && apt-get install -y git unzip libzip-dev \
  && docker-php-ext-install pdo_mysql zip \
  && pecl install xdebug \
  && docker-php-ext-enable xdebug

# copy our PHP config (optional)
COPY docker-php-errors.ini /usr/local/etc/php/conf.d/

# point Apache at our public/ folder
RUN sed -ri \
    -e 's!DocumentRoot /var/www/html!DocumentRoot /var/www/html/public!g' \
    /etc/apache2/sites-available/000-default.conf \
  && sed -ri \
    -e 's!<Directory /var/www/html>!<Directory /var/www/html/public>!g' \
    /etc/apache2/apache2.conf

# set workdir
WORKDIR /var/www/html

# composer (if you want it in container)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# expose apache port
EXPOSE 80
