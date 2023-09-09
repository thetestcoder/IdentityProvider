FROM node:16.17.0 AS node
FROM php:8.1-fpm

ARG user
ARG uid
ARG server_name

COPY --from=node /usr/local/lib/node_modules /usr/local/lib/node_modules
COPY --from=node /usr/local/bin/node /usr/local/bin/node
RUN ln -s /usr/local/lib/node_modules/npm/bin/npm-cli.js /usr/local/bin/npm

RUN apt-get update && apt-get install -y \
    git \
    curl \
    libxml2-dev \
    zip \
    unzip \
    libpng-dev \
    libzip-dev \
    libsodium-dev

RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN apt-get update \
    && docker-php-ext-install mysqli pdo pdo_mysql sodium gd zip exif intl \
    && docker-php-ext-enable pdo_mysql gd exif intl


# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Create system user to run Composer and Artisan Commands
RUN useradd -G www-data,root -u $uid -d /home/$user $user
RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user

# Set working directory
WORKDIR /var/www/html
COPY . /var/www/html/
RUN chown -R $user:$user /var/www/
RUN chmod -R 755 /var/www/

USER $user

