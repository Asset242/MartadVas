FROM php:8.2-fpm


# Install Nginx and other necessary dependencies
RUN apt-get update && apt-get install -y \
    nginx \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    git \
    curl \
    supervisor \
    && rm -rf /var/lib/apt/lists/*


# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql


# Install Node.js and npm
RUN curl -fsSL https://deb.nodesource.com/setup_22.x | bash - \
    && apt-get install -y nodejs


# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer


# Set the working directory for the Laravel project
WORKDIR /var/www


# Copy the Laravel application into the container
COPY . .


COPY .env .






RUN mkdir -p storage/framework/views \
    && chown -R www-data:www-data storage \
    && chmod -R 755 storage


# Install Laravel dependencies
RUN composer install --no-interaction --optimize-autoloader


# Install npm dependencies and build the assets
RUN npm install
RUN npm run build


# Laravel optimization and cache clearing
RUN php artisan key:generate \
    && php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache


# Change permissions for Laravel directories
RUN mkdir -p /var/www/storage /var/www/bootstrap/cache \
    && chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www/storage \
    && chmod -R 755 /var/www/bootstrap/cache


# Expose port 80 for the Nginx server
EXPOSE 80


# Copy the Nginx configuration file to the container
COPY ./nginx/laravel.conf /etc/nginx/sites-available/laravel
RUN ln -s /etc/nginx/sites-available/laravel /etc/nginx/sites-enabled/


# Copy supervisord config and start supervisor
COPY ./supervisord.conf /etc/supervisor/conf.d/supervisord.conf


CMD ["/usr/bin/supervisord"]