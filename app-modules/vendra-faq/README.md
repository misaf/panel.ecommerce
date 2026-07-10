# Vendra FAQ

Tenant-aware FAQ management for Vendra applications.

## Features

- FAQ categories
- FAQs with translatable content
- Filament resources on the `admin` panel

## Requirements

- PHP 8.2+
- Laravel 12
- Filament 5
- Livewire 4
- Pest 4
- Tailwind CSS 4
- `misaf/vendra-tenant`
- `misaf/vendra-user`
- `misaf/vendra-activity-log`

## Installation

```bash
composer require misaf/vendra-faq
php artisan vendor:publish --tag=vendra-faq-migrations
php artisan migrate
```

Optional translations publish:

```bash
php artisan vendor:publish --tag=vendra-faq-translations
```

The service provider and Filament plugin are auto-registered.

## Usage

Create an FAQ category:

```php
use Misaf\VendraFaq\Models\FaqCategory;

$category = FaqCategory::query()->create([
    'name' => ['en' => 'General'],
    'description' => ['en' => 'General questions'],
    'slug' => ['en' => 'general'],
    'position' => 1,
    'status' => true,
]);
```

Create an FAQ:

```php
use Misaf\VendraFaq\Models\Faq;

Faq::query()->create([
    'faq_category_id' => $category->id,
    'name' => ['en' => 'How do I create an account?'],
    'description' => ['en' => 'Use the register page and verify your email.'],
    'slug' => ['en' => 'how-do-i-create-an-account'],
    'position' => 1,
    'status' => true,
]);
```

Load FAQs with their category:

```php
$faqs = Faq::query()
    ->with('faqCategory')
    ->where('status', true)
    ->get();
```

## Filament

Resources are available in the `Faqs` cluster on the `admin` panel:

- FAQ Categories
- FAQs

## Testing

```bash
composer test
```

## License

MIT. See [LICENSE](LICENSE).
