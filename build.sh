#!/usr/bin/env bash
set -e

echo "Installing Composer dependencies..."
composer install --no-dev --optimize-autoloader --no-interaction

echo "Installing NPM dependencies..."
npm ci

echo "Building assets with Vite..."
npm run build

echo "Optimizing Laravel..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Build completed!"