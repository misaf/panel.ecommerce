#!/bin/sh
set -e

run_migrations() {
    php artisan migrate --force --isolated --seed
}

warm_application_caches() {
    php artisan storage:link --force
    php artisan filament:optimize
    php artisan config:cache
    php artisan event:cache
    php artisan view:cache
}

cd /app

if [ "$1" = "frankenphp" ]; then
    run_migrations
    warm_application_caches
fi

exec "$@"
