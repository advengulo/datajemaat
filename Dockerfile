# Use the official PHP 7.4 image as the base image
FROM php:7.4-fpm

# Set the working directory in the container
WORKDIR /var/www/html

# Install dependencies
RUN apt-get update && apt-get install -y \
    curl \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    unzip \
    nginx \
    libpng-dev \
    php-fpm

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath xml zip gd

# Copy the Laravel project files to the working directory
COPY . .

# Set up Nginx configuration
COPY ./docker/nginx.conf /etc/nginx/sites-available/default

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install project dependencies
RUN composer install --no-interaction --no-scripts --no-dev --prefer-dist

# Generate the Laravel application key
RUN php artisan key:generate

# Set permissions for Laravel storage and bootstrap/cache directories
RUN chown -R www-data:www-data storage bootstrap/cache

# Expose port 80 (Nginx)
EXPOSE 80

# Start PHP-FPM and Nginx servers
CMD php-fpm && nginx -g "daemon off;"