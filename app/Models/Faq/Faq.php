<?php

declare(strict_types=1);

namespace App\Models\Faq;

use App\Traits\HasSlugOptionsTrait;
use App\Traits\ThumbnailTableRecord;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Sluggable\HasTranslatableSlug;
use Spatie\Translatable\HasTranslations;

final class Faq extends Model implements HasMedia, Sortable
{
    use HasFactory;

    use HasSlugOptionsTrait;

    use HasTranslatableSlug;

    use HasTranslations;

    use InteractsWithMedia, ThumbnailTableRecord {
        ThumbnailTableRecord::registerMediaCollections insteadof InteractsWithMedia;
        ThumbnailTableRecord::registerMediaConversions insteadof InteractsWithMedia;
    }

    use SoftDeletes;

    use SortableTrait;

    public $translatable = ['name', 'description', 'slug'];

    protected $casts = [
        'id'              => 'integer',
        'faq_category_id' => 'integer',
        'name'            => 'array',
        'description'     => 'array',
        'slug'            => 'array',
        'position'        => 'integer',
        'status'          => 'boolean',
    ];

    protected $fillable = [
        'faq_category_id',
        'name',
        'description',
        'slug',
        'position',
        'status',
    ];

    public function faqCategory(): BelongsTo
    {
        return $this->belongsTo(FaqCategory::class);
    }

    public function scopeFilter(Builder $builder, array $filter): Builder
    {
        $builder->when($filter['search'] ?? false, fn(Builder $builder) => $builder->where('name', 'LIKE', "%{$filter['search']}%")->orWhere('description', 'LIKE', "%{$filter['search']}%"));

        $builder->when($filter['category'] ?? false, fn(Builder $builder) => $builder->where('faq_category_id', $filter['category']));

        return $builder;
    }
}
