FROM php:8.3-fpm-alpine AS base

COPY --from=mlocati/php-extension-installer:latest /usr/bin/install-php-extensions /usr/local/bin/

RUN apk add --no-cache \
        nginx \
        supervisor \
    && install-php-extensions \
        curl \
        pdo_mysql \
        mbstring \
        exif \
        pcntl \
        bcmath \
        sockets \
        gd \
        opcache \
    && rm -rf /var/cache/apk/*


# -- Download PHP dependencies
FROM base AS php_dependencies

COPY --from=composer:2 /usr/bin/composer /usr/local/bin/

WORKDIR /var/www/html

COPY composer.json composer.lock ./
COPY . .

RUN composer install --no-dev --optimize-autoloader --no-interaction


# -- Setup Python environment & Python dependencies
FROM base AS setup_python

COPY --from=ghcr.io/astral-sh/uv:latest /uv /uvx /usr/local/bin/

WORKDIR /var/www/html/app

COPY app/.python-version app/pyproject.toml app/uv.lock ./

RUN rm -rf .venv \
    && uv sync --locked --no-cache

ENV PATH="/var/www/html/app/.venv/bin:$PATH"
ENV GOOGLE_APPLICATION_CREDENTIALS="/var/www/html/app/gcs-service-account.json"
ENV FLASK_APP=main.py
ENV FLASK_RUN_HOST=0.0.0.0
ENV FLASK_RUN_PORT=5000
ENV FLASK_ENV=production

EXPOSE 5000


# -- Final & setup Laravel app
FROM setup_python AS production

ARG ENV_KEY

WORKDIR /var/www/html

COPY . .
COPY --from=php_dependencies /var/www/html/vendor /var/www/html/vendor
COPY --from=setup_python /var/www/html/app/.venv /var/www/html/app/.venv

RUN chown -R www-data:www-data \
        /var/www/html/storage \
        /var/www/html/bootstrap/cache \
    && chmod -R 775 \
        /var/www/html/storage \
        /var/www/html/bootstrap/cache

RUN rm -f .env .env.production \
    && php artisan env:decrypt --key="$ENV_KEY" --env=production \
    && ln -s .env.production .env

COPY docker/php-fpm/zzz-listen-unix-socket.conf /usr/local/etc/php-fpm.d/zzz-listen-unix-socket.conf
COPY docker/nginx/default.conf /etc/nginx/http.d/default.conf
COPY docker/supervisor/supervisord.conf /etc/supervisord.conf

EXPOSE 80

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]
