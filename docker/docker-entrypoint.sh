#!/bin/sh
set -e

app_path="/app"
env_file="${LARAVEL_ENV_FILE:-/run/vendra/.env}"

# Copy the mounted env file, dropping Compose-only infrastructure settings so
# secrets like the MySQL root password never enter Laravel's environment.
if [ -f "${env_file}" ]; then
    grep -vE '^(DB_ROOT_PASSWORD|VENDRA_IMAGE|VENDRA_VERSION|COMPOSE_PROFILES|MYSQL_BIND_ADDRESS|MYSQL_PORT|ALPINE_MIRROR)=' \
        "${env_file}" > "${app_path}/.env"
fi

# The tenant domain is a bare hostname; BASE_APP_URL may carry a scheme,
# port, or path that must be stripped.
base_host="${BASE_APP_URL#*://}"
base_host="${base_host%%/*}"
base_host="${base_host%%:*}"

run_migrations() {
    php artisan migrate --force --isolated
}

ensure_default_tenant() {
    if [ -n "${TENANT_ADMIN_PASSWORD}" ]; then
        set -- --password="${TENANT_ADMIN_PASSWORD}"
    else
        set --
    fi

    php artisan vendra-subscription:provision --if-missing "$@" \
        "${TENANT_NAME:-vendra}" \
        "${base_host}" \
        "${TENANT_ADMIN_USERNAME:-admin}" \
        "${TENANT_ADMIN_EMAIL:-admin@gmail.com}"
}

warm_application_caches() {
    php artisan storage:link --force
    php artisan filament:optimize
    php artisan config:cache
    php artisan event:cache
    php artisan view:cache
}

cd "${app_path}"

if [ "$1" = "frankenphp" ]; then
    run_migrations
    ensure_default_tenant
    warm_application_caches
fi

exec "$@"
