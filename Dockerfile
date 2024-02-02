FROM php:8.1-apache

RUN apt-get update && apt-get install -y \
        libpq-dev \
        unzip \
        openssl \
        bzip2 \
        sqlite3 \
        curl \
        libcurl4-gnutls-dev \
        libgd-dev \
        libicu-dev \
        libzip-dev \
        libbz2-dev \
        libxml2-dev \
        libsqlite3-dev \
        libldap2-dev \
        libonig-dev \
        && docker-php-ext-install pdo_pgsql

RUN docker-php-ext-install pdo_pgsql
RUN docker-php-ext-install curl gd intl mbstring zip bz2 xml pdo_sqlite ldap
RUN docker-php-ext-install opcache

RUN pecl install xdebug-3.2.1 && docker-php-ext-enable xdebug

RUN echo "xdebug.mode=debug" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.start_with_request=yes" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.use_compression=0" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.client_port=9003" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.client_host=host.docker.internal" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

RUN cp /usr/local/etc/php/php.ini-development /usr/local/etc/php/php.ini
RUN echo "zend_extension=xdebug.so" >> /usr/local/etc/php/php.ini

WORKDIR /var/www/html

COPY . /var/www/html/

ENV APACHE_DOCUMENT_ROOT /var/www/html/application/web
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

RUN a2enmod rewrite

# required to create a complete mapbender application container image including all dependencies
#RUN ./bootstrap

CMD ["apache2-foreground"]
