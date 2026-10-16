FROM php:8.4-fpm

# Instalando  as dependências do sistema e extensões do PHP
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libicu-dev \
    libpng-dev \
    libzip-dev \
    && docker-php-ext-install intl pdo_mysql gd zip

# Aqui copia o Composer para o container
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html