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
use Misaf\Geographical\Database\Factories\GeographicalStateFactory;
use Misaf\Geographical\Observers\GeographicalStateObserver;
use Misaf\Tenant\Traits\BelongsToTenant;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Znck\Eloquent\Relations\BelongsToThrough;
use Znck\Eloquent\Traits\BelongsToThrough as TraitBelongsToThrough;

/**
 * @property int $id
 * @property int $tenant_id
 * @property int $geographical_country_id
 * @property string $name
 * @property string $description
 * @property string $slug
 * @property bool $status
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 */
#[ObservedBy([GeographicalStateObserver::class])]
final class GeographicalState extends Model implements HasMedia
{
    use BelongsToTenant;
    /** @use HasFactory<GeographicalStateFactory> */
    use HasFactory;
    use HasSlugOptionsTrait;
    use InteractsWithMedia, ThumbnailTableRecord {
        ThumbnailTableRecord::registerMediaCollections insteadof InteractsWithMedia;
        ThumbnailTableRecord::registerMediaConversions insteadof InteractsWithMedia;
    }
    use LogsActivity;
    use SoftDeletes;
    use TraitBelongsToThrough;

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'id'                      => 'integer',
        'tenant_id'               => 'integer',
        'geographical_country_id' => 'integer',
        'name'                    => 'string',
        'description'             => 'string',
        'slug'                    => 'string',
        'status'                  => 'boolean',
    ];

    /**
     * @var list<string>
     */
    protected $fillable = [
        'geographical_country_id',
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
     * @return BelongsToThrough<GeographicalZone, $this>
     */
    public function geographicalZone(): BelongsToThrough
    {
        return $this->belongsToThrough(GeographicalZone::class, GeographicalCountry::class);
    }

    /**
     * @return BelongsTo<GeographicalCountry, $this>
     */
    public function geographicalCountry(): BelongsTo
    {
        return $this->belongsTo(GeographicalCountry::class);
    }

    /**
     * @return HasMany<GeographicalCity, $this>
     */
    public function geographicalCities(): HasMany
    {
        return $this->hasMany(GeographicalCity::class);
    }

    /**
     * @return HasManyThrough<GeographicalNeighborhood, GeographicalCity, $this>
     */
    public function geographicalNeighborhoods(): HasManyThrough
    {
        return $this->hasManyThrough(GeographicalNeighborhood::class, GeographicalCity::class);
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
