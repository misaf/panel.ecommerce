# Vendra

Vendra is a modular Laravel 13 application for commerce, content, customer
management, and multi-tenant platform development. This repository contains the
host application and every first-party Vendra package in one Composer monorepo.

## Requirements

- PHP 8.3+
- Laravel 13
- Composer
- Node.js and npm
- MySQL or another Laravel-supported database

## Local Development

```bash
composer setup
composer dev
```

`composer setup` installs PHP and JavaScript dependencies, creates `.env`,
generates the application key, runs migrations, and builds frontend assets.
`composer dev` starts the web server, queue listener, logs, and Vite in watch
mode.

## Package Catalog

Packages are independently installable through Composer and are auto-discovered
by Laravel unless their README says otherwise.

| Area | Packages |
| --- | --- |
| Foundation | [Support](packages/vendra-support), [API](packages/vendra-api), [Tenant](packages/vendra-tenant), [Testing](packages/vendra-testing) |
| Standalone dependencies | [Email Validation](https://github.com/misaf/laravel-email-validation), [Emailable Driver](https://github.com/misaf/laravel-email-validation-emailable) |
| Catalog and sales | [Product](packages/vendra-product), [Attribute](packages/vendra-attribute), [Currency](packages/vendra-currency), [Cart](packages/vendra-cart), [Transaction](packages/vendra-transaction), [Subscription](packages/vendra-subscription) |
| Content and marketing | [Blog](packages/vendra-blog), [Custom Page](packages/vendra-custom-page), [FAQ](packages/vendra-faq), [Multimedia](packages/vendra-multimedia), [Tagger](packages/vendra-tagger), [Newsletter](packages/vendra-newsletter), [Affiliate](packages/vendra-affiliate) |
| Customers and access | [User](packages/vendra-user), [User Profile](packages/vendra-user-profile), [Address](packages/vendra-address), [Phone](packages/vendra-phone), [Document](packages/vendra-document), [Verification](packages/vendra-verification), [Permission](packages/vendra-permission), [Socialite](packages/vendra-socialite) |
| Operations and localization | [Activity Log](packages/vendra-activity-log), [Authify Log](packages/vendra-authify-log), [Developer Logins](packages/vendra-developer-logins), [Language](packages/vendra-language), [Localization](packages/vendra-localization) |
| JSON:API modules | [Affiliate API](packages/vendra-affiliate-api), [Attribute API](packages/vendra-attribute-api), [Blog API](packages/vendra-blog-api), [Cart API](packages/vendra-cart-api), [Custom Page API](packages/vendra-custom-page-api), [FAQ API](packages/vendra-faq-api), [Multimedia API](packages/vendra-multimedia-api), [Product API](packages/vendra-product-api) |

Domain packages depend on the provider-neutral contracts in Vendra Support.
Installing Vendra Tenant activates tenant awareness by binding the concrete
resolver; domain and API packages do not depend on the tenant provider.

## Docker

The `docker/Dockerfile` builds the production image (FrankenPHP,
Composer install from the committed lock file, Vite asset build). Pushing a
`MAJOR.MINOR.PATCH` tag publishes `linux/amd64` and `linux/arm64` images to
`ghcr.io/misaf/vendra` (`:1.0.0`, `:1.0`, `:latest`).

A full local stack (php, MySQL, Redis, Horizon, scheduler, Pulse) lives in
`docker/`. The `php` container loads the root `.env` for application config
(`env_file: ../.env`); database credentials default to insecure placeholders
in `docker-compose.yml` — create `docker/.env` to override them if needed.

```bash
cp .env.example .env
# Set APP_KEY (echo "base64:$(openssl rand -base64 32)") and other settings.

cd docker
docker compose up -d --build
# or, from the repo root: docker compose -f docker/docker-compose.yml up -d --build
```

See `docker/README.md` for details.

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

Modules are developed locally through symlinked Composer path repositories in
`packages/*`.

Typical workflow:

1. Edit the module inside `packages/<module-name>`.
2. Ensure the package is required in root `composer.json`.
3. Run `composer update <vendor/package>` or `composer dump-autoload` when needed.
4. Run the package's tests and static analysis as documented in its README.

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
