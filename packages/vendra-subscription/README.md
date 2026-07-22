# Vendra Subscription

Generic plans and polymorphic subscriptions for Vendra applications.

## Features

- Defines plans with periods, pricing, trials, grace windows, feature entitlements, and usage limits
- Stores price and currency snapshots for each subscription period
- Persists provider-neutral subscription payment operations with stable idempotency keys and lifecycle states
- Supports any Eloquent subscriber through a polymorphic relationship
- Enforces one active subscription per subscriber
- Provides pending-payment, active, past-due, lapsed, and expiry-reminder lifecycle primitives

Subscriber-specific orchestration such as provisioning, quota enforcement, queued payment processing, provider webhooks, and notifications belongs to the host application. Provider adapters implement the `SubscriptionCharger` contract exposed by `misaf/vendra-support`; they must never collect more than once for the same idempotency key and financial payload.

## Requirements

- PHP 8.3+
- Laravel 13
- `misaf/vendra-support`

## Installation

```bash
composer require misaf/vendra-subscription
```

Publish and run the package migration through the host application's normal deployment workflow.

The host application defines the inverse `morphMany` relationship and registers stable morph aliases for its subscriber models.

## Testing

```bash
composer test
```

## License

MIT. See [LICENSE](LICENSE).
