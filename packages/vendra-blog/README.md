# Vendra Blog

Tenant-aware blog management for Vendra applications.

## Features

- Blog post categories
- Blog posts
- Filament resources on the `admin` panel

## Requirements

- PHP 8.2+
- Laravel 12
- Filament 5
- Livewire 4
- Pest 4
- Tailwind CSS 4
- `misaf/vendra-tenant`
- `misaf/vendra-activity-log`

Optional:

- `misaf/vendra-tagger` — enables assigning `blog`-typed tags to posts through `misaf/vendra-support`

## Installation

```bash
composer require misaf/vendra-blog
php artisan vendor:publish --tag=vendra-blog-migrations
php artisan migrate
```

Optional translations publish:

```bash
php artisan vendor:publish --tag=vendra-blog-translations
```

The service provider and Filament plugin are auto-registered.

## Usage

Create a category:

```php
use Misaf\VendraBlog\Models\BlogPostCategory;

$category = BlogPostCategory::query()->create([
    'name' => ['en' => 'Announcements'],
    'status' => true,
]);
```

Create a post:

```php
use Misaf\VendraBlog\Models\BlogPost;

BlogPost::query()->create([
    'blog_post_category_id' => $category->id,
    'name' => ['en' => 'Welcome'],
    'status' => true,
]);
```

When Tagger is installed, blog post forms and tables expose tags automatically. Create tags with the reserved `blog` type:

```php
use Misaf\VendraTagger\Models\Tagger;

Tagger::findOrCreate('Announcement', type: 'blog', locale: 'en');
```

Blog imports neither Vendra Tagger nor Spatie Tags; the optional relationship is resolved through Support.

## Filament

Resources are available in the `Blogs` cluster on the `admin` panel:

- Blog Post Categories
- Blog Posts

## Testing

```bash
composer test
```

## License

MIT. See [LICENSE](LICENSE).
