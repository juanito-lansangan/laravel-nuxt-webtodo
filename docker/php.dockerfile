FROM php:8.3-fpm-alpine

# Update system and install required packages
RUN apk update && apk add --no-cache \
    tzdata \
    autoconf \
    g++ \
    make \
    openssl-dev \
    libpng-dev \
    libzip-dev \
    linux-headers \
    curl

# Install required PHP extensions
RUN docker-php-ext-install gd \
    zip \
    bcmath \
    sockets \
    pdo \
    pdo_mysql

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Clean up (optional, to reduce image size)
RUN rm -rf /var/cache/apk/* /tmp/* /usr/share/man /usr/share/doc

# Skip creating the user if it already exists
# Switch to www-data user
USER www-data

# Set the working directory to where the application files are
WORKDIR /var/www/html

# Optionally configure timezone (uncomment if needed)
# ENV TZ="Asia/Manila"

# Install xdebug (if needed, uncomment the next lines)
# RUN pecl install xdebug
# COPY ./config/90-xdebug.ini "${PHP_INI_DIR}/conf.d"
# COPY ./config/custom.ini "${PHP_INI_DIR}/conf.d"

# Expose ports (optional, depending on your setup)
# EXPOSE 9000

# Ensure that PHP uses the correct timezone for your application
# RUN echo "date.timezone = Asia/Manila" > /usr/local/etc/php/conf.d/99-custom.ini