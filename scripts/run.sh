#!/usr/bin/env bash

set -euo pipefail
IFS=$'\n\t'

if php -v | grep 7.4 &> /dev/null && which composer &> /dev/null; then \
   rm -rf ./vendor && \
   composer install --no-dev --no-interaction && \
   composer dump-autoload --optimize && \
   php ./public/auto_game.php; \
elif which docker &> /dev/null; then \
   docker build -t faecie/dominoes:latest . &>/dev/null && \
   docker run -it --rm faecie/dominoes:latest && \
   docker rmi faecie/dominoes:latest --force &>/dev/null ; \
else echo "This helper command needs either a Docker or PHP 7.4 with Composer to run this program. Please make sure you have any of them and try again. (Docker https://docs.docker.com/get-started/#download-and-install-docker-desktop)" ; \
fi
