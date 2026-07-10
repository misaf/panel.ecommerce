<?php

declare(strict_types=1);

namespace Misaf\VendraGeo\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Misaf\VendraGeo\Database\Factories\CountryFactory;
use Misaf\VendraGeo\Observers\CountryObserver;
use Misaf\VendraSupport\Contracts\ShouldLogActivity;
use Misaf\VendraSupport\Traits\BelongsToTenant;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;
use Spatie\Sluggable\SlugOptions;
use Spatie\Translatable\HasTranslations;

/**
 * @property int $id
 * @property int $tenant_id
 * @property array<string, string> $name
 * @property array<string, string> $slug
 * @property string $iso2
 * @property string|null $iso3
 * @property string|null $numeric_code
 * @property string|null $phone_code
 * @property string|null $currency_code
 * @property string|null $latitude
 * @property string|null $longitude
 * @property int $position
 * @property bool $status
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon|null $deleted_at
 */
#[Fillable(['name', 'slug', 'iso2', 'iso3', 'numeric_code', 'phone_code', 'currency_code', 'latitude', 'longitude', 'position', 'status'])]
#[Hidden(['tenant_id'])]
#[ObservedBy([CountryObserver::class])]
#[UseFactory(CountryFactory::class)]
final class Country extends Model implements Sortable, ShouldLogActivity
{
    use BelongsToTenant;

    /** @use HasFactory<CountryFactory> */
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
            'id'            => 'integer',
            'tenant_id'     => 'integer',
            'name'          => 'array',
            'slug'          => 'array',
            'position'      => 'integer',
            'status'        => 'boolean',
        ];
    }

    /**
     * @return HasMany<State, $this>
     */
    public function states(): HasMany
    {
        return $this->hasMany(State::class);
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
