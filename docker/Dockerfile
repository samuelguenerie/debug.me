FROM php:apache

RUN apt-get update -y && \
    apt-get upgrade -y

COPY 000-default.conf /etc/apache2/sites-available/000-default.conf

RUN a2enmod rewrite && \
    service apache2 restart

RUN apt-get install -y libicu-dev

RUN docker-php-ext-configure intl && \
    docker-php-ext-install opcache pdo pdo_mysql intl

RUN pecl install xdebug && \
    docker-php-ext-enable xdebug && \
    echo 'xdebug.mode=develop,debug' >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
    echo 'xdebug.client_host=host.docker.internal' >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
    echo 'xdebug.discover_client_host=On' >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
