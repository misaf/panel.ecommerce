<?php

declare(strict_types=1);

namespace Misaf\VendraCartApi\JsonApi\V1\Carts;

use LaravelJsonApi\Core\Resources\JsonApiResource;
use Misaf\VendraCart\Models\Cart;

/** @mixin Cart */
final class CartResource extends JsonApiResource
{
    /**
     * @return iterable<string, mixed>
     */
    public function attributes($request): iterable
    {
        return [
            'token'       => $this->token,
            'owner_label' => $this->owner_label,
            'expires_at'  => $this->expires_at,
            'created_at'  => $this->created_at,
            'updated_at'  => $this->updated_at,
        ];
    }

    /**
     * @return iterable<int, mixed>
     */
    public function relationships($request): iterable
    {
        return [
            $this->relation('items'),
        ];
    }
}
