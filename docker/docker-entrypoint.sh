#!/bin/sh
set -e

ensure_app_key() {
    if [ -z "${APP_KEY}" ] || [ "${APP_KEY}" = "base64:" ]; then
        export APP_KEY="base64:$(openssl rand -base64 32)"
    fi
}

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
    ensure_app_key
    run_migrations
    warm_application_caches
fi

exec "$@"
