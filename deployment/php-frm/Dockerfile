FROM php:7.4.26-fpm

RUN apt-get update && apt-get install -y \
        zlib1g-dev \
        libpng-dev \
        libpq-dev \
        curl \
        wget \
        zip \
        unzip \
        git

RUN apt-get -y update \
    && apt-get install -y libicu-dev \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl

RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

RUN docker-php-ext-install mysqli pdo pdo_mysql gd bcmath \
    && docker-php-ext-enable mysqli pdo pdo_mysql

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/

CMD ["php-fpm"]
