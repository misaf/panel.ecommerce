FROM dunglas/frankenphp:1-php8.4-alpine AS base

ARG ALPINE_MIRROR=https://dl-cdn.alpinelinux.org/alpine

RUN sed -i "s|https://dl-cdn.alpinelinux.org/alpine|${ALPINE_MIRROR}|g" /etc/apk/repositories && \
    apk add --no-cache tzdata && \
    install-php-extensions gd pdo_mysql intl exif zip bcmath opcache ftp mbstring pcntl redis-^6.3.0

# ---------------------------------------------------------------------------

# Local development runtime only. Application source, Composer dependencies,
# and built frontend assets come from the host bind mount, so this target does
# not copy or build the application.
FROM base AS development

WORKDIR /app

COPY docker/99-custom.ini /usr/local/etc/php/conf.d/99-custom.ini
COPY docker/Caddyfile /etc/frankenphp/Caddyfile
COPY docker/docker-entrypoint.sh /usr/local/bin/vendra-entrypoint

RUN mv "${PHP_INI_DIR}/php.ini-development" "${PHP_INI_DIR}/php.ini" && \
    chmod +x /usr/local/bin/vendra-entrypoint

EXPOSE 8080

HEALTHCHECK --interval=15s --timeout=5s --start-period=180s --retries=5 \
    CMD wget -q --spider http://127.0.0.1:8080/up || exit 1

ENTRYPOINT ["vendra-entrypoint"]
CMD ["frankenphp", "run", "--config", "/etc/frankenphp/Caddyfile"]

# ---------------------------------------------------------------------------

FROM base AS vendor

COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer

RUN apk add --no-cache unzip

WORKDIR /app

COPY . .

# The composer scripts (package:discover, filament:upgrade) boot the app, so
# the full source tree must be present before install. packages/* are path
# repositories symlinked into vendor; both live under /app so the links stay
# valid in the final image. Booting also loads API Platform's routes, which
# introspect each Eloquent resource's columns against the default (sqlite)
# connection; an empty database file keeps that introspection from failing on
# the absent file during the build.
RUN touch database/database.sqlite

RUN --mount=type=cache,target=/root/.composer/cache \
    composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

# ---------------------------------------------------------------------------

FROM node:22-alpine AS assets

WORKDIR /app

# Tailwind scans blade templates in app/, packages/, and vendor/, so the
# asset build needs the composer-installed tree, not just resources/.
COPY --from=vendor /app /app

RUN npm ci --ignore-scripts && npm run build

# ---------------------------------------------------------------------------

FROM base AS app

LABEL maintainer="Ehsan Mahmoodi <misaf.1990@gmail.com>"

WORKDIR /app

COPY --from=vendor /app /app
COPY --from=assets /app/public/build /app/public/build

RUN mkdir -p storage/logs storage/framework/views storage/framework/cache storage/framework/sessions storage/app/public bootstrap/cache && \
    chown -R www-data:www-data storage bootstrap/cache && \
    chmod -R ug+rwX storage bootstrap/cache

COPY docker/99-custom.ini /usr/local/etc/php/conf.d/99-custom.ini
COPY docker/Caddyfile /etc/frankenphp/Caddyfile
COPY docker/docker-entrypoint.sh /usr/local/bin/vendra-entrypoint

RUN mv "${PHP_INI_DIR}/php.ini-production" "${PHP_INI_DIR}/php.ini" && \
    chmod +x /usr/local/bin/vendra-entrypoint

EXPOSE 80 443 443/udp

# Caddy serves every host on :8080 (see Caddyfile) and Laravel's health endpoint
# is /up, so this is the readiness signal `bin/vendra up --wait` blocks on. The
# long start period covers the entrypoint's migrations on a first boot. The
# services that run a worker instead of the server disable it in compose.
HEALTHCHECK --interval=15s --timeout=5s --start-period=180s --retries=5 \
    CMD wget -q --spider http://127.0.0.1:8080/up || exit 1

ENTRYPOINT ["vendra-entrypoint"]
CMD ["frankenphp", "run", "--config", "/etc/frankenphp/Caddyfile"]
