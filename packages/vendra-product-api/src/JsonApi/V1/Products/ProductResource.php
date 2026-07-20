<?php

declare(strict_types=1);

namespace Misaf\VendraProductApi\JsonApi\V1\Products;

use LaravelJsonApi\Core\Resources\JsonApiResource;
use Misaf\VendraProduct\Models\Product;
use Misaf\VendraProductApi\Support\AttributeApiIntegration;

/** @mixin Product */
final class ProductResource extends JsonApiResource
{
    /**
     * @return iterable<string, mixed>
     */
    public function attributes($request): iterable
    {
        return [
            'name'              => $this->name,
            'description'       => $this->description,
            'slug'              => $this->slug,
            'token'             => $this->token,
            'quantity'          => $this->quantity,
            'stock_threshold'   => $this->stock_threshold,
            'in_stock'          => $this->in_stock,
            'position'          => $this->position,
            'available_soon'    => $this->available_soon,
            'availability_date' => $this->availability_date,
            'created_at'        => $this->created_at,
            'updated_at'        => $this->updated_at,
        ];
    }

    /**
     * @return iterable<int, mixed>
     */
    public function relationships($request): iterable
    {
        $relations = [
            $this->relation('productCategory'),
            $this->relation('productPrices'),
            $this->relation('latestProductPrice'),
            $this->relation('oldestProductPrice'),
            $this->relation('multimedia'),
        ];

        if (AttributeApiIntegration::isAvailable()) {
            $relations[] = $this->relation('attributeValues');
        }

        return $relations;
    }
}
