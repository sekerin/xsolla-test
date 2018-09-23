FROM php:7.2-fpm

RUN apt-get update && DEBIAN_FRONTEND=noninteractive apt-get install -y \
      zlib1g-dev

RUN docker-php-ext-install zip
RUN pecl install xdebug-2.6.0
RUN docker-php-ext-enable xdebug

COPY php.ini /usr/local/etc/php/php.ini
COPY conf.d /usr/local/etc/php/conf.d
