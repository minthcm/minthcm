###########################################################################################
FROM php:7.1-apache as builder
###########################################################################################
# Install MintHCM system dependencies
RUN apt-get update && apt-get install -y \
  libfreetype6-dev \
  libjpeg62-turbo-dev \
  libpng-dev \
  libzip-dev \
  zlib1g-dev \
  zip \
  && docker-php-ext-configure gd --with-freetype --with-jpeg \
  && docker-php-ext-configure zip --with-libzip \
  && docker-php-ext-install -j$(nproc) gd \
  && docker-php-ext-install zip mysqli

WORKDIR /source

RUN <<EOT bash
  mkdir -p cache/images
  mkdir -p cache/layout
  mkdir -p cache/pdf
  mkdir -p cache/xml
  mkdir -p cache/include/javascript
EOT

COPY --chown=www-data:www-data ./MintHCM .

RUN <<EOT bash
  chmod -R 775 cache upload modules
  chown www-data:www-data cache upload modules
EOT

# Copy in PHP customizations
WORKDIR /usr/local/etc/php/conf.d/
COPY .docker/minthcm/php-config.ini minthcm.ini

# Reduce image size
RUN apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

###########################################################################################
FROM builder as runner
###########################################################################################
WORKDIR /var/www/html

COPY --from=builder --chown=www-data:www-data /source .

RUN a2enmod headers
