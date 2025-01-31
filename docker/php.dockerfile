# Stage 1: Base stage with php Alpine image
FROM php:8.3-fpm-alpine as builder

# Update app
RUN apk update && apk add --no-cache tzdata
# Set timezone
# ENV TZ="Asia/Manila"
WORKDIR /builder

COPY ./src/backend-api .

RUN apk add --update --no-cache autoconf g++ make openssl-dev
RUN apk add libpng-dev
RUN apk add libzip-dev
RUN apk add --update linux-headers

RUN docker-php-ext-install gd
RUN docker-php-ext-install zip
RUN docker-php-ext-install bcmath
RUN docker-php-ext-install sockets
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
### End Init install

RUN docker-php-ext-install pdo pdo_mysql
RUN composer install

# Install xdebug
# RUN pecl install xdebug

# Copy config files
# COPY ./config/90-xdebug.ini "${PHP_INI_DIR}"/conf.d
# COPY ./config/custom.ini "${PHP_INI_DIR}"/conf.d