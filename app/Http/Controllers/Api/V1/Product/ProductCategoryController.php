<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Product;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\Product\ProductCategoryResource;
use App\Models\Product\ProductCategory;
use Spatie\QueryBuilder\QueryBuilder;

final class ProductCategoryController extends Controller
{
    public function index()
    {
        $query = QueryBuilder::for(ProductCategory::class)
            ->allowedIncludes([
                'media'
            ])
            ->allowedFilters(['name', 'slug', 'status'])
            ->allowedSorts('position')
            ->defaultSort('-position');

        $paginatedPosts = $query->jsonPaginate()->appends(request()->all());

        return ProductCategoryResource::collection($paginatedPosts);
    }
}
