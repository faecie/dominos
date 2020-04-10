#!/usr/bin/env bash

set -euo pipefail
IFS=$'\n\t'

if which docker &> /dev/null; then
   docker build -t faecie/dominoes:latest . &>/dev/null && \
   docker run -it --rm faecie/dominoes:latest && \
   docker rmi faecie/dominoes:latest --force &>/dev/null ; \
else echo "This helper command needs a Docker to run this program. Please install docker and try again https://docs.docker.com/get-started/#download-and-install-docker-desktop" ; \
fi
