# Vendra Newsletter

Tenant-aware newsletter management for Vendra applications.

## Features

- Newsletters with a draft / scheduled / sent lifecycle
- Newsletter subscribers with subscribe and unsubscribe tracking
- Filament resources on the `admin` panel, grouped under `Marketing`

## Requirements

- PHP 8.3+
- Laravel 13
- Filament 5
- Livewire 4
- Pest 4
- Tailwind CSS 4
- `misaf/vendra-support`

## Installation

```bash
composer require misaf/vendra-newsletter
php artisan vendor:publish --tag=vendra-newsletter-migrations
php artisan migrate
```

Optional translations publish:

```bash
php artisan vendor:publish --tag=vendra-newsletter-translations
```

The service provider and Filament plugin are auto-registered.

## Usage

Create a newsletter:

```php
use Misaf\VendraNewsletter\Enums\NewsletterStatusEnum;
use Misaf\VendraNewsletter\Models\Newsletter;

Newsletter::query()->create([
    'subject' => 'Monthly Update',
    'content' => '<p>Hello world.</p>',
    'status' => NewsletterStatusEnum::Draft,
]);
```

Add a subscriber (`subscribed_at` and the unsubscribe token are assigned automatically):

```php
use Misaf\VendraNewsletter\Models\NewsletterSubscriber;

NewsletterSubscriber::query()->create([
    'email' => 'reader@example.com',
    'name' => 'Reader',
]);
```

## Configuration

`config/vendra-newsletter.php` controls the Filament panels, navigation group, and the queue/batch settings used when sending a newsletter:

- `batch_chunk_size` — subscribers per dispatched batch job (`NEWSLETTER_BATCH_CHUNK_SIZE`)
- `queue.tries` — retry attempts for send jobs (`NEWSLETTER_QUEUE_TRIES`)
- `queue.timeout` — batch fan-out job timeout (`NEWSLETTER_QUEUE_TIMEOUT`)
- `queue.email_timeout` — per-recipient delivery job timeout (`NEWSLETTER_EMAIL_QUEUE_TIMEOUT`)

## Filament

Resources are available in the `Newsletters` cluster on the `admin` panel:

- Newsletters
- Subscribers

## Testing

```bash
composer test
```

## License

MIT. See [LICENSE](LICENSE).
