#!/bin/bash

echo "🚀 Démarrage Laravel..."

# Copier .env.example vers .env si .env n'existe pas
if [ ! -f .env ]; then
    cp .env.example .env
fi

# Générer la clé d'application
php artisan key:generate --force

# Supprimer la base de données existante et tout recréer
rm -f database/database.sqlite
touch database/database.sqlite

echo "📦 Migration + Seed..."
php artisan migrate --force --seed

echo "🔗 Storage link..."
php artisan storage:link

echo "🌐 Lancement Apache..."
apache2-foreground