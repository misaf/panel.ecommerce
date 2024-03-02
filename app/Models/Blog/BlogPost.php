<?php

declare(strict_types=1);

namespace App\Models\Blog;

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
use Spatie\Translatable\HasTranslations;

final class BlogPost extends Model implements HasMedia, Sortable
{
    use HasFactory;

    use HasTranslations;

    use InteractsWithMedia, ThumbnailTableRecord {
        ThumbnailTableRecord::registerMediaCollections insteadof InteractsWithMedia;
        ThumbnailTableRecord::registerMediaConversions insteadof InteractsWithMedia;
    }

    use SoftDeletes;

    use SortableTrait;

    public $translatable = ['name', 'description', 'slug'];

    protected $casts = [
        'id'                    => 'integer',
        'blog_post_category_id' => 'integer',
        'name'                  => 'array',
        'description'           => 'array',
        'slug'                  => 'array',
        'position'              => 'integer',
        'status'                => 'boolean',
    ];

    protected $fillable = [
        'blog_post_category_id',
        'name',
        'description',
        'slug',
        'position',
        'status',
    ];

    public function blogPostCategory(): BelongsTo
    {
        return $this->belongsTo(BlogPostCategory::class);
    }

    public function resolveRouteBinding($value, $field = null)
    {
        if (in_array($field, $this->translatable)) {
            return $this->where($field . '->' . app()->getLocale(), $value)->first();
        }
    }

    public function scopeFilter(Builder $builder, array $filter): Builder
    {
        $builder->when($filter['blog'] ?? false, fn(Builder $builder) => $builder->whereRelation('blog', 'blog_id', $filter['blog']));

        $builder->when($filter['post_category'] ?? false, fn(Builder $builder) => $builder->where('blog_post_category_id', $filter['post_category']));

        $builder->when($filter['user'] ?? false, fn(Builder $builder) => $builder->where('user_id', $filter['user']));

        return $builder;
    }
}
