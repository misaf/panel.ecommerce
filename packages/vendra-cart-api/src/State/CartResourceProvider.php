<?php

declare(strict_types=1);

namespace Misaf\VendraCartApi\State;

use ApiPlatform\Metadata\Operation;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Misaf\VendraApi\State\EloquentResourceProvider;
use Misaf\VendraCart\Models\Cart;
use Misaf\VendraCart\Models\CartItem;
use Misaf\VendraCartApi\ApiResource\CartLine;
use Misaf\VendraCartApi\ApiResource\CartResource;

/**
 * @extends EloquentResourceProvider<Cart, CartResource>
 */
final class CartResourceProvider extends EloquentResourceProvider
{
    protected function query(Operation $operation): Builder
    {
        $user = Auth::user();

        return Cart::query()
            ->with('items')
            ->when(
                $user instanceof Authenticatable && $user instanceof Model,
                fn(Builder $query): Builder => $query
                    ->where('owner_type', $user->getMorphClass())
                    ->where('owner_id', $user->getAuthIdentifier()),
                fn(Builder $query): Builder => $query->whereRaw('1 = 0'),
            );
    }

    protected function toResource(Model $model, Operation $operation): CartResource
    {
        /** @var Cart $model */
        return new CartResource(
            id: $model->id,
            token: $model->token,
            expiresAt: $model->expires_at?->toAtomString(),
            lines: $model->items
                ->map(fn(CartItem $item): CartLine => new CartLine(
                    id: $item->id,
                    sellableType: $item->sellable_type,
                    sellableId: $item->sellable_id,
                    quantity: $item->quantity,
                    metadata: $item->metadata,
                ))
                ->all(),
            ownerType: $model->owner_type,
            ownerId: $model->owner_id,
        );
    }
}
