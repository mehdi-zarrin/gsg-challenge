FROM php:8.2-fpm

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN apt-get update
RUN apt-get install -y zlib1g-dev g++ git libicu-dev zip libzip-dev zip \
    && docker-php-ext-install intl opcache pdo pdo_mysql \
    && docker-php-ext-install zip \
    && docker-php-ext-configure zip

RUN yes | pecl install xdebug

WORKDIR /app
RUN chmod 777 -R /app
RUN chown -R www-data:www-data /app
COPY --chown=www-data:www-data . /app
