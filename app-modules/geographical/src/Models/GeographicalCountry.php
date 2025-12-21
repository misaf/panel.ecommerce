<?php

declare(strict_types=1);

namespace Misaf\Geographical\Models;

use App\Traits\HasSlugOptionsTrait;
use App\Traits\ThumbnailTableRecord;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Misaf\Geographical\Database\Factories\GeographicalCountryFactory;
use Misaf\Geographical\Observers\GeographicalCountryObserver;
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
 * @property int $geographical_zone_id
 * @property string $name
 * @property string $description
 * @property string $slug
 * @property bool $status
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 */
#[ObservedBy([GeographicalCountryObserver::class])]
final class GeographicalCountry extends Model implements HasMedia
{
    use BelongsToTenant;
    /** @use HasFactory<GeographicalCountryFactory> */
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
        'id'                   => 'integer',
        'tenant_id'            => 'integer',
        'geographical_zone_id' => 'integer',
        'name'                 => 'string',
        'description'          => 'string',
        'slug'                 => 'string',
        'status'               => 'boolean',
    ];

    /**
     * @var list<string>
     */
    protected $fillable = [
        'geographical_zone_id',
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
     * @return BelongsTo<GeographicalZone, $this>
     */
    public function geographicalZone(): BelongsTo
    {
        return $this->belongsTo(GeographicalZone::class);
    }

    /**
     * @return HasMany<GeographicalState, $this>
     */
    public function geographicalStates(): HasMany
    {
        return $this->hasMany(GeographicalState::class);
    }

    /**
     * @return HasManyThrough<GeographicalCity, GeographicalState, $this>
     */
    public function geographicalCities(): HasManyThrough
    {
        return $this->hasManyThrough(GeographicalCity::class, GeographicalState::class);
    }

    /**
     * @return HasManyDeep<GeographicalNeighborhood, $this>
     */
    public function geographicalNeighborhoods(): HasManyDeep
    {
        return $this->hasManyDeep(GeographicalNeighborhood::class, [
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
