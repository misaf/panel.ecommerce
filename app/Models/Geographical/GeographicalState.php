<?php

declare(strict_types=1);

namespace App\Models\Geographical;

use App\Traits\HasSlugOptionsTrait;
use App\Traits\ThumbnailTableRecord;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Znck\Eloquent\Relations\BelongsToThrough;
use Znck\Eloquent\Traits\BelongsToThrough as TraitsBelongsToThrough;

final class GeographicalState extends Model implements HasMedia
{
    use HasFactory;

    use HasSlugOptionsTrait;

    use InteractsWithMedia, ThumbnailTableRecord {
        ThumbnailTableRecord::registerMediaCollections insteadof InteractsWithMedia;
        ThumbnailTableRecord::registerMediaConversions insteadof InteractsWithMedia;
    }

    use LogsActivity;

    use SoftDeletes;

    use TraitsBelongsToThrough;

    protected $casts = [
        'id'                      => 'integer',
        'geographical_country_id' => 'integer',
        'name'                    => 'string',
        'description'             => 'string',
        'slug'                    => 'string',
        'status'                  => 'boolean',
    ];

    protected $fillable = [
        'geographical_country_id',
        'name',
        'description',
        'slug',
        'status',
    ];

    public function geographicalCities(): HasMany
    {
        return $this->hasMany(GeographicalCity::class);
    }

    public function geographicalCountry(): BelongsTo
    {
        return $this->belongsTo(GeographicalCountry::class);
    }

    public function geographicalNeighborhoods(): HasManyThrough
    {
        return $this->hasManyThrough(GeographicalNeighborhood::class, GeographicalCity::class);
    }

    public function geographicalZone(): BelongsToThrough
    {
        return $this->belongsToThrough(GeographicalZone::class, GeographicalCountry::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logExcept(['id']);
    }
}
