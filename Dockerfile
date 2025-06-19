FROM php:8.3-fpm-alpine AS base

COPY --from=ghcr.io/mlocati/php-extension-installer:latest /usr/bin/install-php-extensions /usr/local/bin/

RUN install-php-extensions \
        curl \
        pdo_mysql \
        mbstring \
        exif \
        pcntl \
        bcmath \
        sockets \
        gd \
        opcache \
    # Clean up apk cache
    && rm -rf /var/cache/apk/*

# Stage 1: Build dependencies
FROM base AS builder

# Copy PHP extension installer and Composer
COPY --from=composer:2 /usr/bin/composer /usr/local/bin/

WORKDIR /var/www/html

# Copy only composer files to leverage Docker caching
COPY composer.json composer.lock ./

# Install Composer dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Copy uv for Python dependency management
COPY --from=ghcr.io/astral-sh/uv:latest /uv /uvx /usr/local/bin/

WORKDIR /var/www/html/app

COPY app/.python-version app/pyproject.toml app/uv.lock ./

RUN uv sync --locked

# Stage 2: Production image
FROM base AS production

ARG ENV_KEY

# Install necessary build tools and dependencies
RUN apk add --no-cache \
        nginx \
        supervisor \
    && rm -rf /var/cache/apk/*

WORKDIR /var/www/html

# Copy application code from the current directory
COPY . .

# Copy Composer dependencies from the builder stage
COPY --from=builder /var/www/html/vendor /var/www/html/vendor
COPY --from=builder /var/www/html/app/.venv /var/www/html/app/.venv

ENV PATH="/var/www/html/app/.venv/bin:$PATH"
ENV GOOGLE_APPLICATION_CREDENTIALS="/var/www/html/app/gcs-service-account.json"
ENV FLASK_APP=main.py
ENV FLASK_RUN_HOST=0.0.0.0
ENV FLASK_RUN_PORT=5000
ENV FLASK_ENV=production

# Set appropriate permissions for Laravel storage and bootstrap/cache directories
RUN chown -R www-data:www-data \
        /var/www/html/storage \
        /var/www/html/bootstrap/cache \
    && chmod -R 775 \
        /var/www/html/storage \
        /var/www/html/bootstrap/cache

# Decrypt .env and link to production
# Combine these commands to reduce layers
RUN rm .env .env.production \
    && php artisan env:decrypt --key="$ENV_KEY" --env=production \
    && ln -s .env.production .env

# Copy Nginx configuration
COPY docker/nginx/default.conf /etc/nginx/http.d/default.conf

# Copy supervisor configuration for PHP-FPM and Nginx
COPY docker/supervisor/supervisord.conf /etc/supervisord.conf

# Expose ports
EXPOSE 80
EXPOSE 5000

# Command to run Supervisor
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]
