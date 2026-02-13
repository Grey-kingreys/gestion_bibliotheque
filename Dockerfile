FROM php:8.3-fpm-alpine

# Installer les dépendances système
RUN apk add --no-cache \
    nodejs \
    npm \
    git \
    zip \
    unzip \
    curl \
    libzip-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    oniguruma-dev \
    bash

# Installer les extensions PHP nécessaires
RUN docker-php-ext-install \
    pdo_mysql \
    mysqli \
    mbstring \
    zip \
    exif \
    pcntl \
    bcmath \
    gd

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Définir le répertoire de travail
WORKDIR /code

# Copier les fichiers du projet
COPY . .

# Copier le script d'entrée personnalisé
COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Installer les dépendances PHP
RUN composer install --no-interaction --optimize-autoloader

# Installer les dépendances Node.js et compiler les assets
RUN npm install && npm run build || true

# Donner les permissions
RUN chown -R www-data:www-data /code/storage /code/bootstrap/cache && \
    chmod -R 775 /code/storage /code/bootstrap/cache

EXPOSE 9000

ENTRYPOINT ["docker-entrypoint.sh"]