FROM php:7.4-cli

ARG DEV

WORKDIR /usr/src/domino

COPY . /usr/src/domino
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && apt update && apt -y autoremove

RUN if [ "$DEV" = true ] ; \
        then \
             # install xdebug only in development environment
             pecl install xdebug \
             && apt -y install git zip \
             && docker-php-ext-enable xdebug \
             && composer global require hirak/prestissimo \
             && composer install; \
        else composer install --no-dev \
             && composer dump-autoload --optimize ; \
        fi

CMD [ "php", "./public/auto_game.php" ]
