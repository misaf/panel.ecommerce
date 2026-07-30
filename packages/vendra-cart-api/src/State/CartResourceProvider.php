<?php

declare(strict_types=1);

namespace Misaf\VendraCartApi\State;

use ApiPlatform\Laravel\Eloquent\Paginator;
use ApiPlatform\Laravel\Eloquent\State\CollectionProvider;
use ApiPlatform\Laravel\Eloquent\State\ItemProvider;
use ApiPlatform\Laravel\Eloquent\State\LinksHandlerInterface;
use ApiPlatform\Metadata\CollectionOperationInterface;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\Pagination\PaginatorInterface;
use ApiPlatform\State\ProviderInterface;
use Generator;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Misaf\VendraCart\Models\Cart;
use Misaf\VendraCart\Models\CartItem;
use Misaf\VendraCartApi\ApiResource\CartLine;
use Misaf\VendraCartApi\ApiResource\CartResource;

/**
 * @implements LinksHandlerInterface<Cart>
 * @implements ProviderInterface<object>
 */
final class CartResourceProvider implements LinksHandlerInterface, ProviderInterface
{
    /**
     * @param Builder<Cart> $builder
     *
     * @return Builder<Cart>
     */
    public function handleLinks(Builder $builder, array $uriVariables, array $context): Builder
    {
        $user = Auth::user();

        if ( ! $user instanceof Authenticatable) {
            return $builder->whereRaw('1 = 0');
        }

        $builder
            ->with('items')
            ->where('owner_type', $user->getMorphClass())
            ->where('owner_id', $user->getAuthIdentifier());

        if ( ! ($context['operation'] ?? null) instanceof CollectionOperationInterface) {
            $mcpData = $context['mcp_data'] ?? [];
            $builder->whereKey($uriVariables['id'] ?? (is_array($mcpData) ? ($mcpData['id'] ?? null) : null));
        }

        return $builder;
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        if ($operation instanceof CollectionOperationInterface) {
            $models = app(CollectionProvider::class)->provide($operation, $uriVariables, $context);

            if ($models instanceof PaginatorInterface) {
                // Re-wrap as an Eloquent Paginator (not a generic TraversablePaginator)
                // so ApiPlatform's access checker recognises the collection and resolves
                // the operation policy against CartResource rather than the paginator object.
                return new Paginator(new LengthAwarePaginator(
                    iterator_to_array($this->mapCollection($models), false),
                    (int) $models->getTotalItems(),
                    (int) $models->getItemsPerPage(),
                    (int) $models->getCurrentPage(),
                ));
            }

            return is_iterable($models) ? iterator_to_array($this->mapCollection($models), false) : [];
        }

        $model = app(ItemProvider::class)->provide($operation, $uriVariables, $context);

        return $model instanceof Cart ? $this->toResource($model) : null;
    }

    /**
     * @param iterable<object> $models
     *
     * @return Generator<int, CartResource>
     */
    private function mapCollection(iterable $models): Generator
    {
        foreach ($models as $model) {
            if ($model instanceof Cart) {
                yield $this->toResource($model);
            }
        }
    }

    private function toResource(Cart $model): CartResource
    {
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
