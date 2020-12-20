FROM php:latest
RUN pecl install redis \
    && pecl install xdebug \
    && docker-php-ext-enable redis xdebug