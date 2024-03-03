<?php

declare(strict_types=1);

namespace App\Models\Product;

use App\Models\Order\OrderProduct;
use App\Traits\ThumbnailTableRecord;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;
use Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;

final class ProductCategory extends Model implements HasMedia, Sortable
{
    use HasFactory;

    use HasRecursiveRelationships;

    use HasTranslations;

    use InteractsWithMedia, ThumbnailTableRecord {
        ThumbnailTableRecord::registerMediaCollections insteadof InteractsWithMedia;
        ThumbnailTableRecord::registerMediaConversions insteadof InteractsWithMedia;
    }

    use SoftDeletes;

    use SortableTrait;

    public $translatable = ['name', 'description', 'slug'];

    protected $casts = [
        'id'          => 'integer',
        'parent_id'   => 'integer',
        'name'        => 'array',
        'description' => 'array',
        'slug'        => 'array',
        'position'    => 'integer',
        'status'      => 'boolean',
    ];

    protected $fillable = [
        'parent_id',
        'name',
        'description',
        'slug',
        'position',
        'status',
    ];

    public function orderProducts(): HasManyThrough
    {
        return $this->hasManyThrough(OrderProduct::class, Product::class);
    }

    public function productPrices(): HasManyThrough
    {
        return $this->hasManyThrough(ProductPrice::class, Product::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function resolveRouteBinding($value, $field = null)
    {
        if (in_array($field, $this->translatable)) {
            return $this->where($field . '->' . app()->getLocale(), $value)->first();
        }
    }
}
