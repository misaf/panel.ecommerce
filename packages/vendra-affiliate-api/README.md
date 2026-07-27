# Vendra Affiliate API

Public read-only JSON:API endpoints for the `misaf/vendra-affiliate` module.

The server intentionally exposes only what referral landing pages need: an
affiliate's `code` and `created_at`. Suspended affiliates are invisible, and
no user, commission, or payout data is ever serialized.

## Features

- Read-only affiliate collection and detail endpoints
- Active-affiliate filtering with code lookup
- Deliberately restricted serialization with no user or financial data

## Requirements

- PHP 8.3+
- Laravel 13
- `misaf/vendra-affiliate`
- `misaf/vendra-api`

## Installation

```bash
composer require misaf/vendra-affiliate-api
```

The service provider registers the `vendra-affiliate` JSON:API server and the
`api`-middleware routes automatically.

## Endpoints

| Method | URI | Description |
| --- | --- | --- |
| GET | `/v1/affiliates` | List active affiliates (`filter[code]`). |
| GET | `/v1/affiliates/{id}` | Show one active affiliate. |

## Testing

Run the package checks from the package directory:

```bash
composer test
composer analyse
```

## License

MIT. See [LICENSE](LICENSE).
