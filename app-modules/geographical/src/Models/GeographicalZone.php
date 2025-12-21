<?php

declare(strict_types=1);

namespace Misaf\Geographical\Models;

use App\Traits\HasSlugOptionsTrait;
use App\Traits\ThumbnailTableRecord;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Misaf\Geographical\Database\Factories\GeographicalZoneFactory;
use Misaf\Geographical\Observers\GeographicalZoneObserver;
use Misaf\Tenant\Traits\BelongsToTenant;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Staudenmeir\EloquentHasManyDeep\HasManyDeep;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;

/**
 * @property int $id
 * @property int $tenant_id
 * @property string $name
 * @property string $description
 * @property string $slug
 * @property bool $status
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 */
#[ObservedBy([GeographicalZoneObserver::class])]
final class GeographicalZone extends Model implements HasMedia
{
    use BelongsToTenant;
    /** @use HasFactory<GeographicalZoneFactory> */
    use HasFactory;
    use HasRelationships;
    use HasSlugOptionsTrait;
    use InteractsWithMedia, ThumbnailTableRecord {
        ThumbnailTableRecord::registerMediaCollections insteadof InteractsWithMedia;
        ThumbnailTableRecord::registerMediaConversions insteadof InteractsWithMedia;
    }
    use LogsActivity;
    use SoftDeletes;

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'id'          => 'integer',
        'tenant_id'   => 'integer',
        'name'        => 'string',
        'description' => 'string',
        'slug'        => 'string',
        'status'      => 'boolean',
    ];

    /**
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'description',
        'slug',
        'status',
    ];

    /**
     * @var list<string>
     */
    protected $hidden = [
        'tenant_id',
    ];

    /**
     * @return HasMany<GeographicalCountry, $this>
     */
    public function geographicalCountries(): HasMany
    {
        return $this->hasMany(GeographicalCountry::class);
    }

    /**
     * @return HasManyThrough<GeographicalState, GeographicalCountry, $this>
     */
    public function geographicalStates(): HasManyThrough
    {
        return $this->hasManyThrough(GeographicalState::class, GeographicalCountry::class);
    }

    /**
     * @return HasManyDeep<GeographicalCity, $this>
     */
    public function geographicalCities(): HasManyDeep
    {
        return $this->hasManyDeep(GeographicalCity::class, [
            GeographicalCountry::class,
            GeographicalState::class,
        ]);
    }

    /**
     * @return HasManyDeep<GeographicalNeighborhood, $this>
     */
    public function geographicalNeighborhoods(): HasManyDeep
    {
        return $this->hasManyDeep(GeographicalNeighborhood::class, [
            GeographicalCountry::class,
            GeographicalState::class,
            GeographicalCity::class,
        ]);
    }

    /**
     * @return MorphMany<Media, $this>
     */
    public function multimedia(): MorphMany
    {
        return $this->media();
    }

    /**
     * @return LogOptions
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->logExcept(['id']);
    }
}
