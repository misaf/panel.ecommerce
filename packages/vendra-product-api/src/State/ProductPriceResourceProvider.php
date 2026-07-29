<?php

declare(strict_types=1);

namespace Misaf\VendraProductApi\State;

use ApiPlatform\Laravel\Eloquent\Extension\FilterQueryExtension;
use ApiPlatform\Laravel\Eloquent\Paginator;
use ApiPlatform\Metadata\CollectionOperationInterface;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\Pagination\Pagination;
use ApiPlatform\State\ProviderInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Misaf\VendraProduct\Models\ProductPrice;
use Misaf\VendraProductApi\ApiResource\ProductPriceResource;
use Misaf\VendraProductApi\State\Concerns\MapsCatalogResources;

/**
 * @implements ProviderInterface<Paginator<ProductPriceResource>|ProductPriceResource>
 */
final class ProductPriceResourceProvider implements ProviderInterface
{
    use MapsCatalogResources;

    public function __construct(
        private readonly Pagination $pagination,
        private readonly FilterQueryExtension $filters,
    ) {}

    /**
     * @return Paginator<ProductPriceResource>|ProductPriceResource|array<int, ProductPriceResource>|null
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
                return $query->get()->map(fn(Model $model): ProductPriceResource => $this->toResource($model, $operation))->all();
            }

            $paginator = $query->paginate(
                perPage: $this->pagination->getLimit($operation, $context),
                page: $this->pagination->getPage($context),
            );
            $paginator->through(fn(Model $model): ProductPriceResource => $this->toResource($model, $operation));

            return new Paginator($paginator);
        }

        $mcpData = $context['mcp_data'] ?? [];
        $identifier = $uriVariables['id'] ?? (is_array($mcpData) ? ($mcpData['id'] ?? null) : null);
        $model = $query->whereKey($identifier)->first();

        return $model instanceof ProductPrice ? $this->toResource($model, $operation) : null;
    }

    protected function query(Operation $operation): Builder
    {
        return ProductPrice::query()->with('product:id,name');
    }

    protected function toResource(Model $model, Operation $operation): ProductPriceResource
    {
        /** @var ProductPrice $model */
        return $this->toPriceResource($model);
    }
}
