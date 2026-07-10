<?php

declare(strict_types=1);

namespace Misaf\VendraGeo\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Misaf\VendraGeo\Database\Factories\StateFactory;
use Misaf\VendraGeo\Observers\StateObserver;
use Misaf\VendraSupport\Contracts\ShouldLogActivity;
use Misaf\VendraSupport\Traits\BelongsToTenant;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;
use Spatie\Sluggable\SlugOptions;
use Spatie\Translatable\HasTranslations;

/**
 * @property int $id
 * @property int $tenant_id
 * @property int $country_id
 * @property array<string, string> $name
 * @property array<string, string> $slug
 * @property string|null $code
 * @property string $type
 * @property string|null $latitude
 * @property string|null $longitude
 * @property int $position
 * @property bool $status
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon|null $deleted_at
 */
#[Fillable(['country_id', 'name', 'slug', 'code', 'type', 'latitude', 'longitude', 'position', 'status'])]
#[Hidden(['tenant_id'])]
#[ObservedBy([StateObserver::class])]
#[UseFactory(StateFactory::class)]
final class State extends Model implements Sortable, ShouldLogActivity
{
    use BelongsToTenant;

    /** @use HasFactory<StateFactory> */
    use HasFactory;
    use HasTranslations;
    use SoftDeletes;
    use SortableTrait;

    /**
     * @var list<string>
     */
    public array $translatable = ['name', 'slug'];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'id'         => 'integer',
            'tenant_id'  => 'integer',
            'country_id' => 'integer',
            'name'       => 'array',
            'slug'       => 'array',
            'position'   => 'integer',
            'status'     => 'boolean',
        ];
    }

    /**
     * @return BelongsTo<Country, $this>
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * @return HasMany<City, $this>
     */
    public function cities(): HasMany
    {
        return $this->hasMany(City::class);
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug')
            ->preventOverwrite();
    }
}
