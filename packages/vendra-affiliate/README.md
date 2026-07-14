# Vendra Affiliate

A full affiliate program for Vendra: referral codes and links, click tracking,
signup attribution, a per-conversion commission ledger, and payout settlement
into the `misaf/vendra-transaction` wallet — with a Filament admin cluster and
stats widgets.

## How it works

1. Each affiliate gets a unique referral code and a `/r/{code}` redirect link.
2. Visiting the link records an `AffiliateClick` and drops an attribution
   cookie (`code|clickId`).
3. When a visitor registers, the referral cookie binds them to the affiliate
   (`AffiliateReferral`) — each user is attributed at most once and
   self-referrals are ignored.
4. Conversions credit `AffiliateCommission` ledger entries idempotently:
   - **Deposit** — a referred user's approved deposit credits
     `commission_percent` of the amount; leaving the approved state reverses
     the unpaid commission.
   - **Signup** — a fixed bounty per attributed registration.
   - **Checkout** — host applications call `RecordCartConversion` from their
     checkout flow (vendra-cart has no checkout event yet).
5. `ProcessAffiliatePayout` settles approved commissions into an
   `AffiliatePayout` and creates a pending Commission transaction.

Each conversion type is toggled in `config/vendra-affiliate.php`, alongside
the attribution cookie, payout minimum, and defaults.

## Optional tags

Install `misaf/vendra-tagger` to assign `affiliate`-typed tags from the affiliate form and display them in the table. Affiliate imports neither Vendra Tagger nor Spatie Tags; the optional relationship is resolved through `misaf/vendra-support`.

```php
use Misaf\VendraTagger\Models\Tagger;

Tagger::findOrCreate('Top performer', type: 'affiliate', locale: 'en');
```

## Installation

```bash
composer require misaf/vendra-affiliate
php artisan vendor:publish --tag=vendra-affiliate-migrations
php artisan migrate
php artisan vendra-affiliate:seed
```

## Testing

```bash
composer test
```

## License

MIT. See [LICENSE](LICENSE).
