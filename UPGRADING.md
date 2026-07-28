# Upgrading Vendra

This guide covers the Vendra host application and its first-party
`misaf/vendra-*` packages. Version-specific manual steps will be added here when
a release requires them.

## Before Upgrading

1. Review the target release notes for breaking changes and package-specific
   instructions.
2. Back up the application database and persistent storage.
3. Confirm that the target release supports the host application's PHP,
   Laravel, Filament, Livewire, and database versions.
4. Keep all directly required Vendra packages on the same release line. The
   package graph uses aligned `self.version` constraints.

## Upgrade

1. Update the intended Composer constraints and resolve the complete dependency
   graph:

   ```bash
   composer update --with-all-dependencies
   ```

2. Run database migrations:

   ```bash
   php artisan migrate --force
   ```

3. Clear cached application state:

   ```bash
   php artisan optimize:clear
   ```

4. Republish package configuration or assets only when the release notes
   explicitly require it. Do not overwrite customized published files
   automatically.

## Verify

Run the host checks and the checks for every package affected by the upgrade:

```bash
php artisan test --parallel
composer stan
vendor/bin/pint --dirty --format agent
```

Verify tenant resolution, queued work, scheduled commands, and each configured
Filament panel in a non-production environment before deployment.
