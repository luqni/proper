#!/bin/sh

# Exit on error
set -e

# Wait for database if needed (optional)
# sleep 5

# Create storage link if not exists
if [ ! -d "/var/www/public/storage" ]; then
    php artisan storage:link
fi

# Run migrations (careful in production, usually better to run manually or via CD)
php artisan migrate --force

# Optimize Laravel
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Start supervisor
exec supervisord -c /etc/supervisor/supervisord.conf
