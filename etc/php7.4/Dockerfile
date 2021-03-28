FROM php:7.4-fpm-alpine

ARG USER_ID
ARG GROUP_ID

RUN apk --no-cache add openssh git shadow && groupmod -g ${GROUP_ID} www-data && usermod -u ${USER_ID} www-data

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN chmod +x /usr/local/bin/install-php-extensions && sync

RUN install-php-extensions xdebug @composer

RUN printf "alias ll='ls -alF'\nalias composer='XDEBUG_MODE=off composer'\nexport COMPOSER_MEMORY_LIMIT=-1" > /etc/profile.d/aliases.sh \
    && chown -R www-data:www-data /home/www-data

RUN mv $PHP_INI_DIR/php.ini-development $PHP_INI_DIR/php.ini
