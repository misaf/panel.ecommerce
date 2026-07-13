# Vendra

Vendra is a modular Laravel application for e-commerce and marketplace use cases.

## Requirements

- PHP 8.2+
- Composer
- Node.js and npm
- MySQL or another Laravel-supported database

## Local Development

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
npm install
npm run build
composer dev
```

`composer dev` starts the web server, queue listener, logs, and Vite in watch mode.

## Docker

The `docker/Dockerfile` builds the production image (FrankenPHP,
Composer install from the committed lock file, Vite asset build). Pushing a
`MAJOR.MINOR.PATCH` tag publishes `linux/amd64` and `linux/arm64` images to
`ghcr.io/misaf/vendra` (`:1.0.0`, `:1.0`, `:latest`).

A full local stack (php, MySQL, Redis, Horizon, scheduler, Pulse) lives in
`docker/`; its `.env` holds compose and infrastructure settings and is
separate from the root application `.env`:

```bash
cd docker
cp .env.example .env
# Set APP_KEY (echo "base64:$(openssl rand -base64 32)") and passwords.
docker compose up -d --build
```

Startup runs migrations, provisions the default tenant, and warms caches via
the container entrypoint; no manual setup commands are required inside the
container. Production servers should use the published image through
[vendra-deploy](https://github.com/misaf/vendra-deploy).

## Configuration

Settings cache can be enabled with:

```env
SETTINGS_CACHE_ENABLED=true
```

## Module Development

Modules are developed locally through Composer path repositories in `packages/*`.

Typical workflow:

1. Edit the module inside `packages/<module-name>`.
2. Ensure the package is required in root `composer.json`.
3. Run `composer update <vendor/package>` or `composer dump-autoload` when needed.
4. Run the relevant tests or static analysis.

For production builds, rely on installed Composer packages rather than local path repository workflows.

## Useful Commands

```bash
composer test
php artisan test --compact
vendor/bin/pint --dirty --format agent
vendor/bin/phpstan analyse
```

## Troubleshooting

- If package changes are not reflected, run `composer dump-autoload`.
- If provider discovery seems stale, run `php artisan package:discover`.
- If configuration values look outdated, run `php artisan config:clear`.
- If frontend changes do not appear, run `npm run dev` or `npm run build`.

## License

MIT. See [LICENSE](LICENSE).
