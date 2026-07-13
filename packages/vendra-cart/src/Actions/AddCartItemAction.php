<?php

declare(strict_types=1);

namespace Misaf\VendraCart\Actions;

use Illuminate\Database\Eloquent\Model;
use Misaf\VendraCart\Models\Cart;
use Misaf\VendraCart\Models\CartItem;

final class AddCartItemAction
{
    /**
     * Add a sellable to the cart, merging quantities when it is already present.
     *
     * @param array<string, mixed>|null $metadata
     */
    public function execute(Cart $cart, Model $sellable, int $quantity = 1, ?array $metadata = null): CartItem
    {
        /** @var CartItem $item */
        $item = $cart->items()->firstOrNew([
            'sellable_type' => $sellable->getMorphClass(),
            'sellable_id'   => $sellable->getKey(),
        ]);

        $item->quantity = $item->exists ? $item->quantity + $quantity : $quantity;

        if (null !== $metadata) {
            $item->metadata = $metadata;
        }

        $item->save();

        return $item;
    }
}
