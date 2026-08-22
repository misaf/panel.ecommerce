# Vendra Developer Logins

Local-only developer login shortcuts for Vendra Filament panels.

## Features

- Registers developer login options on configured Filament panels
- Restricts selectable users by role
- Supports switching users during local development
- Remains disabled outside Laravel's `local` environment

## Requirements

- PHP 8.4+
- Laravel 13
- Filament 5
- `misaf/vendra-user`
- `misaf/vendra-permission`
- `misaf/vendra-support`

## Installation

```bash
composer require --dev misaf/vendra-developer-logins
php artisan vendor:publish --tag=vendra-developer-logins-config
```

Configure the eligible panels, role, credential column, and label column in `config/vendra-developer-logins.php`.

## Testing

Run the package checks from the project root:

```bash
php artisan test --compact --testsuite=vendra-developer-logins
composer stan
```

## License

MIT. See [LICENSE](LICENSE).
