FROM php:8.2-fpm

RUN apt-get update
RUN apt-get install -y git libzip-dev zip libicu-dev libpng-dev libcurl4-gnutls-dev
RUN docker-php-ext-configure intl
RUN docker-php-ext-install pdo pdo_mysql mysqli exif zip intl gd curl
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer
RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www
USER www
