# Use official PHP image with FPM
FROM php:8.3-fpm

# Set working directory
WORKDIR /var/www/html

# Install system dependencies in a single layer and clean up
RUN apt-get update --no-install-recommends -y && \
    apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip && \
    docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*

# Install xdebug (if needed, uncomment)
# RUN pecl install xdebug && docker-php-ext-enable xdebug

# Copy project files
COPY ./src/api .

# Copy PHP custom configuration
COPY ./docker/api/custom.ini "${PHP_INI_DIR}"/conf.d

# Get the latest Composer and optimize installation
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install dependencies with optimized Composer flags for performance
RUN composer install --prefer-dist --no-dev --optimize-autoloader

# Ensure proper permissions for storage and cache directories
RUN chown -R www-data:www-data storage bootstrap/cache && \
    chmod -R 775 storage bootstrap/cache

# Expose port 9000 for PHP-FPM
EXPOSE 9000

# Command to run PHP-FPM
CMD ["php-fpm"]