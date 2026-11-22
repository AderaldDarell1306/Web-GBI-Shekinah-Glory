#!/bin/bash
set -e

# Handle Render dynamic PORT (Render sets $PORT, default is 10000)
if [ -n "$PORT" ]; then
    sed -i "s/Listen 80/Listen $PORT/" /etc/apache2/ports.conf
    sed -i "s/:80/:$PORT/" /etc/apache2/sites-available/000-default.conf
fi

# Ensure database directory and SQLite file exist with proper permissions
mkdir -p /var/www/html/database
if [ ! -f /var/www/html/database/database.sqlite ]; then
    touch /var/www/html/database/database.sqlite
fi
chown -R www-data:www-data /var/www/html/database
chmod -R 775 /var/www/html/database

# Ensure storage directories exist with proper permissions
mkdir -p /var/www/html/storage/framework/cache/data
mkdir -p /var/www/html/storage/framework/sessions
mkdir -p /var/www/html/storage/framework/views
mkdir -p /var/www/html/storage/app/public
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Generate APP_KEY if not already set in environment
if [ -z "$APP_KEY" ]; then
    echo "Warning: APP_KEY is not set. Generating a temporary key..."
    php artisan key:generate --force || true
fi

# Link public storage if not linked
php artisan storage:link || true

# Run database migrations
php artisan migrate --force || true

# Cache configurations and routes for production speed
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

exec "$@"
