FROM php:8.3-apache AS base-container

ENV APACHE_DOCUMENT_ROOT=/var/mapbender/application/public
ENV API_UPLOAD_DIR=/var/mapbender/application/uploads/

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
RUN docker-php-ext-configure gd --with-jpeg --with-freetype
RUN docker-php-ext-install curl gd intl mbstring zip bz2 xml pdo_sqlite ldap
RUN docker-php-ext-install opcache

RUN rm /etc/apache2/sites-enabled/*
COPY ./docker/mapbender_apache.conf /etc/apache2/sites-enabled/
COPY ./docker/php.ini /usr/local/etc/php/php.ini

RUN sed -ri -e 's!80!8080!g' /etc/apache2/ports.conf
RUN a2enmod rewrite remoteip

RUN chown www-data:www-data -R /var/www

WORKDIR /var/mapbender/

EXPOSE 8080

COPY --chown=www-data:www-data . /var/mapbender/

FROM base-container AS build-container

RUN apt-get update && apt-get install -y \
        git \
        && rm -rf /var/lib/apt/lists/*

USER www-data
# required to create a complete mapbender application container image including all dependencies
RUN git config --global --add safe.directory /var/mapbender
RUN rm application/composer.lock && ./bootstrap


FROM base-container AS mapbender

USER www-data

COPY --from=build-container --chown=www-data:www-data /var/mapbender /var/mapbender

CMD ["apache2-foreground"]

FROM base-container AS mapbender-puppeteer

RUN apt-get update && apt-get install -y \
        npm \
        nodejs \
        libasound2 libatk1.0-0 libc6 libcairo2 libcups2 libdbus-1-3 libexpat1 libfontconfig1 libgbm1 libgcc1 libgdk-pixbuf-xlib-2.0-0 libglib2.0-0 libgtk-3-0 libnspr4 libpango-1.0-0 libpangocairo-1.0-0 libstdc++6 libx11-6 libx11-xcb1 libxcb1 libxcomposite1 libxcursor1 libxdamage1 libxext6 libxfixes3 libxi6 libxrender1 libxss1 libxtst6 ca-certificates fonts-liberation libnss3 lsb-release xdg-utils wget \
        && rm -rf /var/lib/apt/lists/*

RUN npm install -g puppeteer
RUN chown www-data:www-data -R /usr/local/lib/node_modules/

USER www-data

RUN puppeteer browsers install

COPY --from=build-container --chown=www-data:www-data /var/mapbender /var/mapbender

CMD ["apache2-foreground"]