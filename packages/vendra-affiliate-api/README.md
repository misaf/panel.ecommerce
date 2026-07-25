# Vendra Affiliate API

Public read-only JSON:API endpoints for the `misaf/vendra-affiliate` module.

The server intentionally exposes only what referral landing pages need: an
affiliate's `code` and `created_at`. Suspended affiliates are invisible, and
no user, commission, or payout data is ever serialized.

## Requirements

- PHP 8.3+
- Laravel 13
- `misaf/vendra-affiliate`
- `misaf/vendra-api`

## Endpoints

| Method | URI                  | Description                              |
| ------ | -------------------- | ---------------------------------------- |
| GET    | `/v1/affiliates`     | List active affiliates (`filter[code]`). |
| GET    | `/v1/affiliates/{id}`| Show one active affiliate.               |

## Installation

```bash
composer require misaf/vendra-affiliate-api
```

The service provider registers the `vendra-affiliate` JSON:API server and the
`api`-middleware routes automatically.

## Testing

```bash
composer test
composer analyse
```

## License

MIT. See [LICENSE](LICENSE).
