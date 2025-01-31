FROM php:8.3-fpm

# Arguments defined in docker-compose.yml
ARG uid=1000
ARG user=laravel

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Install xdebug
# RUN pecl install xdebug

COPY ./src/backend-api .

# Copy config files
# COPY ./config/90-xdebug.ini "${PHP_INI_DIR}"/conf.d
# COPY ./config/custom.ini "${PHP_INI_DIR}"/conf.d

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Create system user to run Composer and Artisan Commands
RUN useradd -G www-data,root -u $uid -d /home/$user $user
RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user

RUN composer install

# Set working directory
WORKDIR /var/www/html

USER $user