#!/bin/bash

echo "🚀 Démarrage de l'application Laravel sur Render..."

# Vérifier que APP_KEY existe
if [ -z "$APP_KEY" ] || [ "$APP_KEY" = "base64:" ]; then
    echo "🔑 Génération de la clé d'application..."
    php artisan key:generate --force
fi

# Attendre que la base de données soit prête
echo "⏳ Attente de la base de données..."
max_attempts=30
attempt=0
while ! php artisan db:show &>/dev/null; do
    attempt=$((attempt + 1))
    if [ $attempt -eq $max_attempts ]; then
        echo "❌ Impossible de se connecter à la base de données après $max_attempts tentatives"
        exit 1
    fi
    echo "Tentative $attempt/$max_attempts..."
    sleep 2
done

echo "✅ Base de données accessible"

# Vider les caches
echo "🧹 Nettoyage des caches..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Exécuter les migrations
echo "📦 Migration de la base de données..."
php artisan migrate --force

# Seed UNIQUEMENT si la table users est vide
USER_COUNT=$(php artisan tinker --execute="echo App\\Models\\User::count();" 2>/dev/null || echo "0")
if [ "$USER_COUNT" = "0" ]; then
    echo "🌱 Seed de la base de données..."
    php artisan db:seed --force
else
    echo "✅ Base de données déjà initialisée, pas de seed"
fi

# Créer le lien symbolique storage
echo "🔗 Création du lien symbolique storage..."
php artisan storage:link --force

# Optimiser pour la production
echo "⚡ Optimisation pour la production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Créer les répertoires de logs si nécessaire
mkdir -p /var/log/supervisor

echo "✅ Application Laravel prête !"
echo "🌐 Écoute sur le port 10000"

# Démarrer Supervisor
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf