# Vendra Socialite

Optional OAuth social login (Google, GitHub) for Vendra user panels, built on
[`dutchcodingcompany/filament-socialite`](https://github.com/DutchCodingCompany/filament-socialite).

Requiring this package is all it takes to add social login — `misaf/vendra-user`
has no dependency on it. Social buttons render on the login form of the panels
listed in `config/vendra-socialite.php` and only appear once their provider
credentials are set.

## Requirements

- PHP 8.3+
- Laravel 13
- Filament 5
- `misaf/vendra-user`

## Configuration

Publish the config with `php artisan vendor:publish --tag=vendra-socialite-config`
and set the OAuth credentials in `config/services.php` / `.env`:

```
GOOGLE_CLIENT_ID / GOOGLE_CLIENT_SECRET / GOOGLE_REDIRECT_URI=https://<app>/admin/oauth/callback/google
GITHUB_CLIENT_ID / GITHUB_CLIENT_SECRET / GITHUB_REDIRECT_URI=https://<app>/admin/oauth/callback/github
```

Optional environment values:

- `VENDRA_SOCIALITE_REGISTRATION=true` — allow unknown OAuth identities to create
  an account (default `false`: only existing users, matched by email, may sign in).
- `VENDRA_SOCIALITE_DOMAINS=example.com,foo.com` — restrict sign-in by email domain.

## Testing

```bash
composer test
```

## License

MIT. See [LICENSE](LICENSE).
