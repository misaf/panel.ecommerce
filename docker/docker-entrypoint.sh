#!/bin/sh
set -e

ensure_app_key() {
    if [ -z "${APP_KEY}" ] || [ "${APP_KEY}" = "base64:" ]; then
        export APP_KEY="base64:$(openssl rand -base64 32)"
    fi
}

# Seeders run once, on a first boot. `migrate:status` fails while the migrations
# table is absent, which is the only reliable "this database is empty" signal
# available before anything has run. Seeding on every boot instead would make
# each restart of php and php-api replay every seeder against live data.
run_migrations() {
    if php artisan migrate:status >/dev/null 2>&1; then
        php artisan migrate --force --isolated
    else
        php artisan migrate --force --isolated --seed
    fi
}

# A bare `route:clear` only deletes the single-tenant
# bootstrap/cache/routes-v7.php. spatie/laravel-multitenancy points
# APP_ROUTES_CACHE at a per-tenant file (SwitchRouteCacheTask), so clearing has
# to happen once per tenant — `tenants:artisan` switches each tenant in before
# running the command, which is what makes route:clear delete the right path.
#
# Without this a release that changes routes, or any class serialized into them,
# leaves every tenant serving a cache built against the previous release; it
# unserializes into __PHP_Incomplete_Class objects and 500s far from the cause.
clear_tenant_route_caches() {
    php artisan tenants:artisan "route:clear"
}

warm_application_caches() {
    php artisan storage:link --force
    php artisan filament:optimize
    php artisan config:cache
    php artisan event:cache
    php artisan view:cache
}

prepare_local_application() {
    if [ "${VENDRA_SKIP_STORAGE_LINK:-false}" != "true" ]; then
        php artisan storage:link --force
    fi

    php artisan optimize:clear
}

cd /app

ensure_app_key

if [ "$1" = "frankenphp" ]; then
    run_migrations
    if [ "${APP_ENV:-production}" = "local" ]; then
        prepare_local_application
    else
        # After the migrations: enumerating tenants needs the database, and on a
        # first boot the table it reads does not exist until they have run.
        clear_tenant_route_caches
        warm_application_caches
    fi
fi

exec "$@"
