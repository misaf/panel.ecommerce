<?php

declare(strict_types=1);

namespace App\Models\Geographical;

use App\Traits\ThumbnailTableRecord;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Znck\Eloquent\Relations\BelongsToThrough;
use Znck\Eloquent\Traits\BelongsToThrough as TraitsBelongsToThrough;

final class GeographicalCity extends Model implements HasMedia
{
    use HasFactory;

    use InteractsWithMedia, ThumbnailTableRecord {
        ThumbnailTableRecord::registerMediaCollections insteadof InteractsWithMedia;
        ThumbnailTableRecord::registerMediaConversions insteadof InteractsWithMedia;
    }

    use LogsActivity;

    use SoftDeletes;

    use TraitsBelongsToThrough;

    protected $casts = [
        'id'                    => 'integer',
        'geographical_state_id' => 'integer',
        'name'                  => 'string',
        'description'           => 'string',
        'slug'                  => 'string',
        'status'                => 'boolean',
    ];

    protected $fillable = [
        'geographical_state_id',
        'name',
        'description',
        'slug',
        'status',
    ];

    public function geographicalCountry(): BelongsToThrough
    {
        return $this->belongsToThrough(GeographicalCountry::class, GeographicalState::class);
    }

    public function geographicalNeighborhoods(): HasMany
    {
        return $this->hasMany(GeographicalNeighborhood::class);
    }

    public function geographicalState(): BelongsTo
    {
        return $this->belongsTo(GeographicalState::class);
    }

    public function geographicalZone(): BelongsToThrough
    {
        return $this->belongsToThrough(GeographicalZone::class, [GeographicalCountry::class, GeographicalState::class]);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logExcept(['id']);
    }
}
