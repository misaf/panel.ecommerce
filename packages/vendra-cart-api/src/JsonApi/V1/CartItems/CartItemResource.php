<?php

declare(strict_types=1);

namespace Misaf\VendraCartApi\JsonApi\V1\CartItems;

use LaravelJsonApi\Core\Resources\JsonApiResource;
use Misaf\VendraCart\Models\CartItem;

/** @mixin CartItem */
final class CartItemResource extends JsonApiResource
{
    /**
     * @return iterable<string, mixed>
     */
    public function attributes($request): iterable
    {
        return [
            'sellable_type' => $this->sellable_type,
            'sellable_id'   => $this->sellable_id,
            'quantity'      => $this->quantity,
            'metadata'      => $this->metadata,
            'created_at'    => $this->created_at,
            'updated_at'    => $this->updated_at,
        ];
    }

    /**
     * @return iterable<int, mixed>
     */
    public function relationships($request): iterable
    {
        return [
            $this->relation('cart'),
        ];
    }
}
