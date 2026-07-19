<?php

declare(strict_types=1);

namespace Misaf\VendraCurrency\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Misaf\VendraCurrency\Database\Factories\CurrencyFactory;
use Misaf\VendraMultimedia\Concerns\HasDefaultMediaConversions;
use Misaf\VendraSupport\Contracts\ShouldLogActivity;
use Misaf\VendraSupport\Traits\BelongsToTenant;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * @property int $id
 * @property int $tenant_id
 * @property int $currency_category_id
 * @property string $name
 * @property string $description
 * @property string $slug
 * @property string $iso_code
 * @property float $conversion_rate
 * @property int $decimal_place
 * @property int $buy_price
 * @property int $sell_price
 * @property bool $is_default
 * @property int $position
 * @property bool $status
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon|null $deleted_at
 */
#[Fillable(['currency_category_id', 'name', 'description', 'slug', 'iso_code', 'conversion_rate', 'decimal_place', 'buy_price', 'sell_price', 'is_default', 'position', 'status'])]
#[Hidden(['tenant_id', 'active_slug_guard'])]
#[UseFactory(CurrencyFactory::class)]
final class Currency extends Model implements HasMedia, Sortable, ShouldLogActivity
{
    use BelongsToTenant;

    use HasDefaultMediaConversions, InteractsWithMedia {
        HasDefaultMediaConversions::registerMediaConversions insteadof InteractsWithMedia;
    }

    /** @use HasFactory<CurrencyFactory> */
    use HasFactory;

    use HasSlug;
    use SoftDeletes;
    use SortableTrait;
    public const string MEDIA_COLLECTION = 'currencies';

    /**
     * Pin sortable behavior regardless of the global `eloquent-sortable`
     * configuration values: order on the `position` column and always assign
     * the next position when creating.
     *
     * Note: `ignore_timestamps` cannot be pinned here because the package reads
     * it directly from config (no per-model override), and it already defaults
     * to `false` both in config and in the package.
     *
     * @var array{order_column_name: string, sort_when_creating: bool}
     */
    public array $sortable = [
        'order_column_name'  => 'position',
        'sort_when_creating' => true,
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'id'                   => 'integer',
            'tenant_id'            => 'integer',
            'currency_category_id' => 'integer',
            'name'                 => 'string',
            'description'          => 'string',
            'slug'                 => 'string',
            'iso_code'             => 'string',
            'conversion_rate'      => 'float',
            'decimal_place'        => 'integer',
            'buy_price'            => 'integer',
            'sell_price'           => 'integer',
            'is_default'           => 'boolean',
            'position'             => 'integer',
            'status'               => 'boolean',
        ];
    }

    /**
     * @return Attribute<string, string>
     */
    protected function isoCode(): Attribute
    {
        return Attribute::make(
            set: fn(string $value): string => Str::upper($value),
        );
    }

    /**
     * @return BelongsTo<CurrencyCategory, $this>
     */
    public function currencyCategory(): BelongsTo
    {
        return $this->belongsTo(CurrencyCategory::class);
    }

    /**
     * @return MorphMany<Media, $this>
     */
    public function multimedia(): MorphMany
    {
        return $this->media();
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug')
            ->preventOverwrite();
    }
}
