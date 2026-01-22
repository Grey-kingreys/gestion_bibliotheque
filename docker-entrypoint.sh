#!/bin/bash
set -e

echo "Running migrations..."
php artisan migrate --force

echo "Starting Apache..."
apache2-foreground