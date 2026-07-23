# Vendra Local Docker Stack

Local Docker Compose stack building the image from the repository root.
Production servers should use the published image through
[vendra-deploy](https://github.com/misaf/vendra-deploy).

## Stack

- `php`: FrankenPHP application server
- `mysql`: MySQL 8.4
- `redis`: cache, session, maintenance, and queue backend
- `horizon`: queue worker for every named Vendra queue
- `scheduler`: Laravel task scheduler
- `pulse`: Pulse server monitor
- `pulse-worker`: Pulse Redis ingest worker

Application files, database data, Redis data, and Caddy TLS state persist in
named volumes.

## Setup

```sh
cd docker
cp .env.example .env
echo "base64:$(openssl rand -base64 32)"
# Put the generated value in APP_KEY, then review passwords and domains.

docker compose build
docker compose up -d
```

`APP_KEY`, database credentials, and other settings are documented in
`.env.example`. Compose refuses to start without the required values.

The image is built from the repository's committed `composer.lock`, so a
given commit always produces the same image.

## Startup

The `php` container waits for MySQL and Redis, then:

1. Runs `php artisan migrate --force --isolated --seed`.
2. Warms Laravel and Filament caches; a warmup failure stops the container.

Horizon, the scheduler, and Pulse start only after the
application reports ready. `/up` answers liveness; `/ready` also verifies MySQL
and Redis connectivity and backs the container healthcheck.

## Laravel Drivers

Redis is part of the standard stack and backs maintenance, sessions, cache, and
queues. Horizon consumes the `default`, `transactional-email`,
`marketing-email`, `bulk-email`, and `process-affiliate-commission` queues with
workload-separated supervisors defined in `config/horizon.php`.

## Domains and TLS

Set the primary URL in `APP_URL` and one or more space-separated domains in
`SERVER_NAME`.

```env
APP_URL=https://example.com
SERVER_NAME="example.com www.example.com"
SERVER_TLS_MODE=you@example.com
```

Use `SERVER_TLS_MODE=internal` for local domains or servers behind another TLS
terminator. Public automatic TLS requires ports 80 and 443 to be reachable.

Unknown tenant domains use Caddy on-demand TLS. The application authorizes
issuance through `/caddy/domain-check`; create the tenant domain and point its
DNS to the server before visiting it.

## Releases

Images are published by tagging this repository: pushing a
`MAJOR.MINOR.PATCH` tag builds `linux/amd64` and `linux/arm64` images and
publishes `:1.0.0`, `:1.0`, and `:latest` to `ghcr.io/misaf/vendra`.

## Common Commands

```sh
docker compose logs -f php horizon scheduler pulse pulse-worker
docker compose exec php php artisan horizon:terminate
docker compose exec php php artisan pulse:restart
docker compose exec php php artisan reload
docker compose exec php php artisan down --with-secret
docker compose exec php php artisan up
```

Application logs go to container stderr; read them with `docker compose logs`.
The `/horizon` and `/pulse` dashboards require an authenticated user with the
configured super-admin role. Pulse ingest runs over Redis
(`PULSE_INGEST_DRIVER=redis` on the cache connection); the monitor's stable
server name comes from `PULSE_SERVER_NAME`.

MySQL is bound to `127.0.0.1` by default. Use an SSH tunnel for remote access:

```sh
ssh -L 3307:127.0.0.1:3306 user@server
```

## Existing Storage Volumes

Older stacks mounted `storage-data` at `storage/app/public`. After updating the
Compose file, run this once before starting the stack:

```sh
docker compose run --rm --no-deps --entrypoint sh php -c \
  'mkdir -p /app/storage/app/public && find /app/storage/app -mindepth 1 -maxdepth 1 ! -name public -exec mv {} /app/storage/app/public/ \;'
docker compose up -d
```

Skip this for new installations or if it has already been run.
