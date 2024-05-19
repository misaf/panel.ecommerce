<?php

declare(strict_types=1);

namespace App\JsonApi\V1\ProductPrices;

use LaravelJsonApi\Core\Resources\JsonApiResource;

final class ProductPriceResource extends JsonApiResource
{
    /**
     * Get the resource's attributes.
     *
     * @param Request|null $request
     * @return iterable
     */
    public function attributes($request): iterable
    {
        return [
            'price'      => $this->price,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    /**
     * Get the resource's relationships.
     *
     * @param Request|null $request
     * @return iterable
     */
    public function relationships($request): iterable
    {
        return [
            $this->relation('currency'),
            $this->relation('product'),
        ];
    }
}
