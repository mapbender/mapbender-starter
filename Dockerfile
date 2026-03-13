# Stage: compile PHP extensions with dev dependencies
FROM php:8.3-apache AS php-ext-builder

RUN apt-get update && apt-get install -y --no-install-recommends \
        libpq-dev \
        libcurl4-gnutls-dev \
        libgd-dev \
        libicu-dev \
        libzip-dev \
        libbz2-dev \
        libxml2-dev \
        libsqlite3-dev \
        libldap2-dev \
        libonig-dev \
        && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo_pgsql
RUN docker-php-ext-configure gd --with-jpeg --with-freetype
RUN docker-php-ext-install curl gd intl mbstring zip bz2 xml pdo_sqlite ldap
RUN docker-php-ext-install opcache

# Stage: runtime base (no -dev packages, only runtime shared libs)
FROM php:8.3-apache AS base-container

ENV APACHE_DOCUMENT_ROOT=/var/mapbender/application/public
ENV API_UPLOAD_DIR=/var/mapbender/application/uploads/

# Copy compiled PHP extension binaries and ini configs from builder
COPY --from=php-ext-builder /usr/local/lib/php/extensions/ /usr/local/lib/php/extensions/
COPY --from=php-ext-builder /usr/local/etc/php/conf.d/ /usr/local/etc/php/conf.d/

# Install only runtime libraries (no -dev / header packages)
RUN apt-get update && apt-get install -y --no-install-recommends \
        libpq5 \
        libcurl3-gnutls \
        libgd3 \
        libicu72 \
        libzip4 \
        libonig5 \
        libldap-2.5-0 \
        unzip \
        openssl \
        curl \
        && rm -rf /var/lib/apt/lists/*

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

# Replace VectorTilesBundle files with patched versions
RUN curl -fsSL https://raw.githubusercontent.com/mapbender/mapbender/b762f26fa0439aa9a5c4e1a620f36718fd5845ce/src/Mapbender/VectorTilesBundle/Component/VectorTilesRenderer.php \
        -o application/mapbender/src/Mapbender/VectorTilesBundle/Component/VectorTilesRenderer.php \
    && curl -fsSL https://raw.githubusercontent.com/mapbender/mapbender/b762f26fa0439aa9a5c4e1a620f36718fd5845ce/src/Mapbender/VectorTilesBundle/Resources/js/print-vectortile.js \
        -o application/mapbender/src/Mapbender/VectorTilesBundle/Resources/js/print-vectortile.js


FROM base-container AS mapbender

USER www-data

COPY --from=build-container --chown=www-data:www-data /var/mapbender /var/mapbender

CMD ["apache2-foreground"]

FROM base-container AS mapbender-puppeteer-build

RUN apt-get update && apt-get install -y --no-install-recommends \
        npm \
        nodejs \
        && rm -rf /var/lib/apt/lists/*

RUN npm install -g puppeteer
RUN chown www-data:www-data -R /usr/local/lib/node_modules/

USER www-data

RUN puppeteer browsers install

FROM base-container AS mapbender-puppeteer

RUN apt-get update && apt-get install -y --no-install-recommends \
        nodejs \
        libasound2 libatk1.0-0 libc6 libcairo2 libcups2 libdbus-1-3 libexpat1 libfontconfig1 libgbm1 libgcc1 libgdk-pixbuf-xlib-2.0-0 libglib2.0-0 libgtk-3-0 libnspr4 libpango-1.0-0 libpangocairo-1.0-0 libstdc++6 libx11-6 libx11-xcb1 libxcb1 libxcomposite1 libxcursor1 libxdamage1 libxext6 libxfixes3 libxi6 libxrender1 libxss1 libxtst6 ca-certificates fonts-liberation libnss3 lsb-release xdg-utils wget \
        && rm -rf /var/lib/apt/lists/*

COPY --from=mapbender-puppeteer-build /usr/local/lib/node_modules/ /usr/local/lib/node_modules/
COPY --from=mapbender-puppeteer-build --chown=www-data:www-data /var/www/.cache/puppeteer/ /var/www/.cache/puppeteer/
RUN ln -sf /usr/local/lib/node_modules/.bin/puppeteer /usr/local/bin/puppeteer
# Symlink so Node's standard module resolution finds puppeteer from any script under /var/mapbender
RUN mkdir -p /var/mapbender/node_modules && ln -sf /usr/local/lib/node_modules/puppeteer /var/mapbender/node_modules/puppeteer

ENV NODE_PATH=/usr/local/lib/node_modules

USER www-data

COPY --from=build-container --chown=www-data:www-data /var/mapbender /var/mapbender

CMD ["apache2-foreground"]