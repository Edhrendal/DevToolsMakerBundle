FROM php:8.1-cli

COPY --from=composer:2.3 /usr/bin/composer /usr/local/bin/composer

RUN apt-get update -y \
    && apt-get install -y libpq-dev libzip-dev libicu-dev libpng-dev \
    && docker-php-ext-install intl

# Extra php lib : APCu, Xdebug
RUN pecl install apcu \
    && pecl install xdebug \
    && docker-php-ext-enable apcu xdebug

# Extra composer lib : git
RUN apt-get install -y git zip \
    && docker-php-ext-install zip

# Drop xdebug output from CLI
RUN echo "alias php='php -d xdebug.mode=off $@'" >> /root/.bashrc
RUN echo "alias composer='php /usr/local/bin/composer $@'" >> /root/.bashrc

# Add custom configuration files
COPY ./docker/php/conf/*.ini /usr/local/etc/php/conf.d/

# For bin/ scripts
ENV COMPOSER_HOME=/composer

RUN \
    mkdir /composer \
    && php /usr/local/bin/composer global require \
        steevanb/php-parallel-processes "^0.2" \
        symfony/console "5.4.*" \
    && chmod -R 777 ${COMPOSER_HOME}
