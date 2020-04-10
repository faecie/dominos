#!/usr/bin/env bash

set -euo pipefail
IFS=$'\n\t'

if php -v | grep 7.4 &> /dev/null && which composer &> /dev/null; then
   rm -rf ./vendor && \
   composer install --no-dev --no-interaction && \
   composer dump-autoload --optimize && \
   php ./public/auto_game.php; \
else echo "This helper command needs a PHP 7.4 and Composer to run this program. Please install them and come back later" ; \
fi
