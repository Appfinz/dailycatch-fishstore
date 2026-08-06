FROM php:8.4-cli

# Install system dependencies & PHP extensions
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    git \
    sqlite3 \
    libsqlite3-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_sqlite pdo_mysql bcmath

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY . .

# Install Composer dependencies
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader --ignore-platform-req=php+

# Set permissions for storage, cache & database
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database

# Create startup script
RUN echo '#!/bin/bash\n\
touch /var/www/html/database/database.sqlite\n\
chown -R www-data:www-data /var/www/html/database /var/www/html/storage /var/www/html/bootstrap/cache\n\
php artisan migrate:fresh --seed --force\n\
php artisan config:cache\n\
php artisan route:cache\n\
php artisan view:cache\n\
exec php artisan serve --host=0.0.0.0 --port=${PORT:-8080}' > /usr/local/bin/start-container && chmod +x /usr/local/bin/start-container

EXPOSE 8080

CMD ["/usr/local/bin/start-container"]
