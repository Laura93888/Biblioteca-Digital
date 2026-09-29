FROM php:8.4-cli-bookworm

RUN apt-get update \
    && apt-get install -y mariadb-server \
    && docker-php-ext-install pdo_mysql \
    && rm -rf /var/lib/apt/lists/*
