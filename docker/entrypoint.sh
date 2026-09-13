#!/bin/bash
set -e

mkdir -p /var/www/storage/framework/sessions \
         /var/www/storage/framework/views \
         /var/www/storage/framework/cache \
         /var/www/storage/logs \
         /var/www/public/uploads \
         /var/log/supervisor

chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache /var/www/public/uploads /var/log/supervisor 2>/dev/null || true
chmod -R 775 /var/www/storage /var/www/bootstrap/cache 2>/dev/null || true

php /var/www/artisan storage:link --force 2>/dev/null || true

if [ "$APP_ENV" = "production" ] || [ "$APP_ENV" = "prod" ]; then
    php /var/www/artisan config:cache 2>/dev/null || true
    php /var/www/artisan route:cache 2>/dev/null || true
    php /var/www/artisan view:cache 2>/dev/null || true
fi

exec "$@"
