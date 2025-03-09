FROM php:8.4-fpm

WORKDIR /usr/share/chess-data

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zip

RUN docker-php-ext-install mysqli pdo_mysql

COPY --from=composer:2.8 /usr/bin/composer /usr/bin/composer
COPY composer.json composer.json
COPY composer.lock composer.lock

RUN composer install \
    --no-interaction \
    --no-plugins \
    --no-scripts \
    --no-dev \
    --prefer-dist