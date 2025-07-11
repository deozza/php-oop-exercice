FROM php:8.3-apache

# PHP
RUN apt-get update -y && apt-get upgrade -y
RUN apt-get install -y zlib1g-dev libwebp-dev libpng-dev && docker-php-ext-install gd
RUN apt-get install libzip-dev -y && docker-php-ext-install zip
RUN docker-php-ext-install pdo pdo_mysql

# Composer
#COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Apache
RUN a2enmod rewrite
RUN service apache2 restart

WORKDIR /var/www/html
COPY ./app /var/www/html

RUN composer install
RUN composer dump-autoload

EXPOSE 80
