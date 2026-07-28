# Vendra Affiliate API

API Platform resources for public referral lookup and visit recording.

The server intentionally exposes only what referral landing pages need: an
affiliate's `code` and `created_at`. Suspended affiliates are invisible, and
no user, commission, or payout data is ever serialized.

## Features

- Read-only referral-code collection and detail operations
- Active-affiliate filtering with code lookup
- Validated referral-visit writes delegated to the domain action
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

The service provider registers its resources, provider, and processor automatically.

## Endpoints

| Method | URI | Description |
| --- | --- | --- |
| GET | `/api/marketing/affiliates` | List active referral codes (`code`, pagination). |
| GET | `/api/marketing/affiliates/{id}` | Show one active referral code. |
| POST | `/api/marketing/affiliate-clicks` | Record a validated referral visit. |

## Testing

Run the package checks from the package directory:

```bash
composer test
composer analyse
```

## License

MIT. See [LICENSE](LICENSE).
