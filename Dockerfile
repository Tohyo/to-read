FROM dunglas/frankenphp as base

COPY . /app

WORKDIR /app

ENV COMPOSER_ALLOW_SUPERUSER=1
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

RUN install-php-extensions intl pdo_pgsql
