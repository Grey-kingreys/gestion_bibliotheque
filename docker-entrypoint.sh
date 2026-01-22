#!/bin/bash
set -e

echo "Generating app key if not exists..."
php artisan key:generate --force --no-interaction

echo "Running migrations..."
php artisan migrate --force

echo "Starting Apache..."
apache2-foreground