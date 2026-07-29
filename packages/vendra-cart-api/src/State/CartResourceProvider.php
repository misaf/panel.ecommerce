<?php

declare(strict_types=1);

namespace Misaf\VendraCartApi\State;

use ApiPlatform\Laravel\Eloquent\Extension\FilterQueryExtension;
use ApiPlatform\Laravel\Eloquent\Paginator;
use ApiPlatform\Metadata\CollectionOperationInterface;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\Pagination\Pagination;
use ApiPlatform\State\ProviderInterface;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Misaf\VendraCart\Models\Cart;
use Misaf\VendraCart\Models\CartItem;
use Misaf\VendraCartApi\ApiResource\CartLine;
use Misaf\VendraCartApi\ApiResource\CartResource;

/**
 * @implements ProviderInterface<Paginator<CartResource>|CartResource>
 */
final class CartResourceProvider implements ProviderInterface
{
    public function __construct(
        private readonly Pagination $pagination,
        private readonly FilterQueryExtension $filters,
    ) {}

    /**
     * @return Paginator<CartResource>|CartResource|array<int, CartResource>|null
     */
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $query = $this->query($operation);

        if ($operation instanceof CollectionOperationInterface) {
            $query = $this->filters->apply($query, $uriVariables, $operation, $context);

            foreach ($operation->getOrder() ?? ['id' => 'DESC'] as $property => $direction) {
                $query->orderBy(is_int($property) ? $direction : $property, is_int($property) ? 'ASC' : $direction);
            }

            if (false === $this->pagination->isEnabled($operation, $context)) {
                return $query->get()->map(fn(Model $model): CartResource => $this->toResource($model, $operation))->all();
            }

            $paginator = $query->paginate(
                perPage: $this->pagination->getLimit($operation, $context),
                page: $this->pagination->getPage($context),
            );
            $paginator->through(fn(Model $model): CartResource => $this->toResource($model, $operation));

            return new Paginator($paginator);
        }

        $mcpData = $context['mcp_data'] ?? [];
        $identifier = $uriVariables['id'] ?? (is_array($mcpData) ? ($mcpData['id'] ?? null) : null);
        $model = $query->whereKey($identifier)->first();

        return $model instanceof Cart ? $this->toResource($model, $operation) : null;
    }

    protected function query(Operation $operation): Builder
    {
        $user = Auth::user();

        if ( ! $user instanceof Authenticatable) {
            return Cart::query()->whereRaw('1 = 0');
        }

        return Cart::query()
            ->with('items')
            ->where('owner_type', $user->getMorphClass())
            ->where('owner_id', $user->getAuthIdentifier());
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
