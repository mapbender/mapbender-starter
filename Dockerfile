FROM php:7.4-apache





RUN apt-get update \
    && DEBIAN_FRONTEND=noninteractive apt-get install --no-install-recommends -y \
		zlib1g-dev \
    libzip-dev \
		libbz2-dev \
    libxml2-dev \
		libwebp-dev \
		libjpeg-dev \
		libpng-dev \
		libxmp-dev \
		libicu-dev \
		libsqlite3-dev \
    libpq-dev \
	git \
	ca-certificates \
    && docker-php-ext-install zip \
	  && docker-php-ext-install bz2 \
	  && docker-php-ext-install xml \
	  && docker-php-ext-install gd \
	  && docker-php-ext-install intl \
	  && docker-php-ext-install pdo_sqlite \
	  && docker-php-ext-install pdo \
	  && docker-php-ext-install pdo_pgsql \
	  && docker-php-ext-install pdo_mysql \
    && docker-php-ext-install opcache


ENV APACHE_DOCUMENT_ROOT /var/www/html/starter/application/web

ENV DATABASE_PASSWORD ''
ENV DATABASE_DRIVER 'pdo_sqlite'
ENV DATABASE_HOST ''
ENV DATABASE_PORT ''
ENV DATABASE_NAME ''
ENV DATABASE_USER ''

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf



WORKDIR /var/www/html

COPY  /docker/starter.conf  /etc/apache2/conf-available/starter.conf
RUN ln -s /etc/apache2/conf-available/starter.conf /etc/apache2/conf-enabled/starter.conf
COPY --chown=www-data:www-data . ./starter

RUN cd /var/www/html/starter/application && chmod +x bin/composer app/console
# Github action does not support the user command and so we need to substitute this
# First we chmod vendor and web to www-data 
# Second we delete the cache which is created by the root user
#USER www-data


WORKDIR /var/www/html/starter/application
COPY --chown=www-data:www-data /application/app/config/parameters.yml.dist ./app/config/parameters.yml
RUN bin/composer install --prefer-dist   --no-dev --ignore-platform-reqs --no-cache --verbose --no-interaction
RUN chmod +x ./vendor/wheregroup/sassc-binaries/dist/sassc   
RUN app/console doctrine:schema:update -f
RUN app/console mapbender:database:init
RUN app/console assets:install
RUN chown -R  www-data:www-data ./vendor
RUN chown -R  www-data:www-data ./web
RUN rm -rf /application/app/cache

COPY --chown=www-data:www-data  /docker/parameters.yml ./starter/application/app/config/parameters.yml
COPY --chown=www-data:www-data  /docker/config_prod.yml ./starter/application/app/config/config_prod.yml
