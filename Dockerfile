FROM php:8.4-apache

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

# Ensure single MPM module (mpm_prefork) and enable mod_rewrite
RUN a2dismod mpm_event mpm_worker || true && a2enmod mpm_prefork rewrite

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY . .

# Install Composer dependencies
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader --ignore-platform-req=php+

# Set Apache document root to public/
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/000-default.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

# Adjust Apache port binding for dynamic $PORT environment variable (Render/Railway)
RUN sed -i 's/80/${PORT:-80}/g' /etc/apache2/ports.conf /etc/apache2/sites-available/000-default.conf

# Set permissions for storage, cache & database
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database

# Create startup script
RUN echo '#!/bin/bash\n\
touch /var/www/html/database/database.sqlite\n\
chown www-data:www-data /var/www/html/database/database.sqlite\n\
php artisan migrate:fresh --seed --force\n\
php artisan config:cache\n\
php artisan route:cache\n\
php artisan view:cache\n\
exec apache2-foreground' > /usr/local/bin/start-container && chmod +x /usr/local/bin/start-container

EXPOSE 80

CMD ["/usr/local/bin/start-container"]
