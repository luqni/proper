# Stage 1: Build Frontend Assets
FROM node:20-alpine AS build

WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci
COPY . .

# Add build argument for VAPID key (if needed)
ARG VITE_VAPID_PUBLIC_KEY
ENV VITE_VAPID_PUBLIC_KEY=$VITE_VAPID_PUBLIC_KEY

# Ensure PWA icons exist during build (fallback to logo if missing)
RUN mkdir -p public/icons && \
    cp public/images/logo.png public/icons/icon-192x192.png || true && \
    cp public/images/logo.png public/icons/icon-512x512.png || true

RUN npm run build

# Stage 2: Build PHP App
FROM php:8.3-fpm

# Install dependencies (GD, zip, zlib, PostgreSQL, cron)
RUN apt-get update && apt-get install -y \
    nginx git unzip curl libzip-dev zip zlib1g-dev \
    libpng-dev libjpeg-dev libfreetype6-dev \
    libpq-dev supervisor cron libicu-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install zip pdo_mysql pdo_pgsql gd pcntl bcmath intl

RUN pecl install redis \
    && docker-php-ext-enable redis

# Copy Composer from the official image
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Set working directory
WORKDIR /var/www

# Copy project files
COPY . .

# Ensure important folders exist
RUN mkdir -p storage bootstrap/cache

# Install PHP dependencies
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Copy frontend assets from Stage 1
COPY --from=build /app/public/build /var/www/public/build
COPY --from=build /app/public/sw.js /var/www/public/
COPY --from=build /app/public/workbox-*.js /var/www/public/

# Laravel permissions
RUN chown -R www-data:www-data /var/www && chmod -R 755 /var/www/storage

# Supervisor config (Using paths from user's sample)
COPY docker/supervisord.conf /etc/supervisor/supervisord.conf
# Note: User mentioned docker/laravel.conf, using it if it exists or fallback to supervisord.conf
# For now, we will create a basic laravel.conf if user doesn't have it.

# Entrypoint
COPY docker/entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

EXPOSE 80

ENTRYPOINT ["docker-entrypoint.sh"]
