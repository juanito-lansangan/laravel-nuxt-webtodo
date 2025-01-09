FROM php:8-fpm-alpine

RUN mkdir -p /var/www/html

WORKDIR /var/www/html

RUN docker-php-ext-install pdo pdo_mysql

COPY ./docker/config/crontab /etc/crontabs/root

CMD ["crond", "-f"]