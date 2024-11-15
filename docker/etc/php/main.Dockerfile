FROM php:8.2-fpm

# Install dependencies
# -q flag is used to reduce the output of apt-get, is stands for quiet
# -y flag is used to automatically answer yes to prompts is stands for yes
# xdebug is required for debugging
# pdo is required for database connections
# pdo_mysql is required for mysql connections
# libzip-dev is required for zip extension
# libicu-dev is required for intl extension
# zip is required for composer
# git is requried for grumphp
RUN apt-get -q update && apt-get -qy install \
    zip \
    cron \
    libzip-dev \
    libicu-dev \
    git \
    && pecl install pcov \
    && docker-php-ext-enable pcov \
    && docker-php-ext-install intl opcache pdo pdo_mysql \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug

WORKDIR /var/www/server

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY ./server /var/www/server

# Copy configuration files
COPY server/.php-cs-fixer.php /var/www/server/.php-cs-fixer.php
COPY server/phpstan.neon /var/www/server/phpstan.neon

RUN ./vendor/bin/grumphp git:init || true
