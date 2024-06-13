FROM php:8.3-apache as base-container

ENV APACHE_DOCUMENT_ROOT /var/mapbender/application/public

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
        postgresql-client \
        && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo_pgsql
RUN docker-php-ext-install curl gd intl mbstring zip bz2 xml pdo_sqlite ldap
RUN docker-php-ext-install opcache

RUN rm /etc/apache2/sites-enabled/*
COPY ./docker/mapbender_apache.conf /etc/apache2/sites-enabled/

RUN sed -ri -e 's!80!8080!g' /etc/apache2/ports.conf
RUN a2enmod rewrite remoteip

RUN chown www-data:www-data -R /var/www

WORKDIR /var/mapbender/

EXPOSE 8080

COPY --chown=www-data:www-data . /var/mapbender/

FROM base-container as build-container

RUN apt-get update && apt-get install -y \
        git \
        && rm -rf /var/lib/apt/lists/*

USER www-data
# required to create a complete mapbender application container image including all dependencies
RUN ./bootstrap


FROM base-container

USER www-data

COPY --from=build-container --chown=www-data:www-data /var/mapbender /var/mapbender

CMD ["apache2-foreground"]
