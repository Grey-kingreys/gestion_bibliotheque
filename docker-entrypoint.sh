#!/bin/bash

echo "🚀 Démarrage Laravel..."

# Attendre que la base de données soit accessible (optionnel pour Aiven)
# Décommente si besoin d'attendre la connexion
# echo "⏳ Attente de la base de données..."
# sleep 10

# Copier .env.example vers .env si .env n'existe pas
if [ ! -f .env ]; then
    echo "📝 Création du fichier .env..."
    cp .env.example .env
fi

# Générer la clé d'application si elle n'existe pas
if ! grep -q "APP_KEY=base64" .env || [ "$(grep APP_KEY .env | cut -d '=' -f2)" = "" ]; then
    echo "🔑 Génération de la clé d'application..."
    php artisan key:generate --force
fi

# Vider le cache de configuration
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Exécuter les migrations (sans seed pour éviter de dupliquer)
echo "📦 Migration..."
php artisan migrate --force

# Seed UNIQUEMENT si la table users est vide
USER_COUNT=$(php artisan tinker --execute="echo App\\Models\\User::count();" 2>/dev/null || echo "0")
if [ "$USER_COUNT" = "0" ]; then
    echo "🌱 Seed de la base de données..."
    php artisan db:seed --force
else
    echo "✅ Base de données déjà initialisée, pas de seed"
fi

echo "🔗 Storage link..."
php artisan storage:link

echo "✅ Laravel prêt !"

# Démarrer PHP-FPM
exec php-fpm