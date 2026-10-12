FROM php:8.5-fpm-alpine

RUN apk add --no-cache bash ffmpeg git rabbitmq-c-dev postgresql-dev supervisor unzip icu-dev oniguruma-dev libxml2-dev linux-headers $PHPIZE_DEPS \
    && docker-php-ext-install pdo_mysql pdo_pgsql intl \
    && pecl install amqp redis xdebug \
    && docker-php-ext-enable amqp redis xdebug \
    && git config --system --add safe.directory /var/www/html

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
WORKDIR /var/www/html
CMD ["php-fpm"]
