FROM php:8.2-fpm-alpine

RUN apk add --no-cache postgresql-dev fcgi curl libmcrypt-dev libzip-dev oniguruma-dev \
    freetype-dev zlib-dev libpng-dev libjpeg-turbo-dev libpq-dev icu-dev ffmpeg wget unzip zip \
    --update linux-headers \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-configure gd --with-jpeg=/usr/include/ --with-freetype=/usr/include/ \
    && docker-php-ext-install pdo_pgsql bcmath intl zip mbstring exif pcntl gd

RUN mv $PHP_INI_DIR/php.ini-development $PHP_INI_DIR/php.ini

WORKDIR /app
