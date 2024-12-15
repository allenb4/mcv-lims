FROM php:7.4-fpm

# Set working directory
WORKDIR /var/www

# Install system dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    curl \
    libzip-dev \
    default-mysql-client \
    bash \
    wkhtmltopdf \
    unzip \
    && docker-php-ext-configure gd  --with-freetype=/usr/include/ --with-jpeg=/usr/include/ \
    && docker-php-ext-install pdo_mysql zip exif pcntl \
    && docker-php-ext-install gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy application files
COPY . /var/www

# Set permissions for Laravel
RUN chown -R www-data:www-data /var/www
RUN chmod -R 775 /var/www

# Run Composer install
RUN composer install --no-scripts --no-dev --optimize-autoloader

# Expose port
EXPOSE 9000

# Start PHP-FPM server
CMD ["php-fpm"]
