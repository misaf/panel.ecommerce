<?php

declare(strict_types=1);

namespace Misaf\VendraProductApi\JsonApi\V1\ProductCategories;

use LaravelJsonApi\Core\Resources\JsonApiResource;
use Misaf\VendraProduct\Models\ProductCategory;
use Misaf\VendraSupport\Support\AttributeApiIntegration;

/**
 * @mixin ProductCategory
 */
final class ProductCategoryResource extends JsonApiResource
{
    /**
     * @return iterable<string, mixed>
     */
    public function attributes($request): iterable
    {
        return [
            'name'        => $this->name,
            'description' => $this->description,
            'slug'        => $this->slug,
            'position'    => $this->position,
            'status'      => $this->status,
            'created_at'  => $this->created_at,
            'updated_at'  => $this->updated_at,
        ];
    }

    /**
     * @return iterable<int, mixed>
     */
    public function relationships($request): iterable
    {
        $relations = [
            $this->relation('products'),
            $this->relation('productPrices'),
            $this->relation('multimedia'),
        ];

        if (AttributeApiIntegration::isAvailable()) {
            $relations[] = $this->relation('attributeValues');
        }

        return $relations;
    }
}
