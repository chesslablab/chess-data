FROM php:7.3-fpm

RUN apt-get update && apt-get install -y \
    git \
    libzip-dev \
    unzip \
    zip

RUN docker-php-ext-configure zip --with-libzip

RUN docker-php-ext-install mysqli pdo_mysql zip sockets

RUN curl --silent --show-error https://getcomposer.org/installer | php && \
    mv composer.phar /usr/local/bin/composer

RUN curl --silent --show-error https://deb.nodesource.com/setup_12.x | bash
RUN apt-get install -y nodejs

RUN pecl install mailparse
