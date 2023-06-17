FROM php:7.2-apache

RUN apt-get update && apt-get install -y libpq-dev && docker-php-ext-install pdo pdo_pgsql


WORKDIR /var/www/html

COPY . /var/www/html/

EXPOSE 80