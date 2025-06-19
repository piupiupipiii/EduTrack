FROM php:8.3-fpm-alpine

COPY --from=composer:2 /usr/bin/composer /usr/local/bin/
COPY --from=ghcr.io/mlocati/php-extension-installer:latest /usr/bin/install-php-extensions /usr/local/bin/
COPY --from=ghcr.io/astral-sh/uv:latest /uv /uvx /usr/local/bin/

# Install common PHP extensions required by Laravel
# gd for image manipulation, pdo_mysql for database, mbstring for multi-byte strings,
# exif for image metadata, bcmath for arbitrary precision mathematics,
# opcache for PHP performance, pcntl and sockets for queue workers/websockets
RUN apk add --no-cache \
    nginx \
    supervisor \
    # Install system dependencies for PHP extensions
    && install-php-extensions curl pdo_mysql mbstring exif pcntl bcmath sockets gd opcache \
    # Clean up apk cache to reduce image size
    && rm -rf /var/cache/apk/*

# Set working directory for the application
WORKDIR /var/www/html

# Copy application code (excluding .git, vendor, etc. which will be handled)
COPY composer.json composer.lock ./
COPY . .

# Copy Composer dependencies from the composer_install stage
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Set appropriate permissions for Laravel storage and bootstrap/cache directories
# These directories need to be writable by the web server
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

ARG ENV_KEY
    
RUN rm .env .env.production \
    && php artisan env:decrypt --key="$ENV_KEY" --env=production \
    && ln -s .env.production .env

WORKDIR /var/www/html/app/

RUN rm -rf /var/www/html/app/.venv \
    && uv sync --locked

ENV PATH="/var/www/html/app/.venv/bin:$PATH"
ENV GOOGLE_APPLICATION_CREDENTIALS="/var/www/html/app/gcs-service-account.json"
ENV FLASK_APP=main.py
ENV FLASK_RUN_HOST=0.0.0.0
ENV FLASK_RUN_PORT=5000
ENV FLASK_ENV=production

WORKDIR /var/www/html/

# Copy Nginx configuration
COPY docker/nginx/default.conf /etc/nginx/http.d/default.conf

# Copy supervisor configuration for PHP-FPM and Nginx
COPY docker/supervisor/supervisord.conf /etc/supervisord.conf

# Expose port 80 for Nginx
EXPOSE 80

EXPOSE 5000

# Command to run Supervisor, which in turn starts Nginx and PHP-FPM
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]
