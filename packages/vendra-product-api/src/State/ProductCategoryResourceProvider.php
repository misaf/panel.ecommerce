<?php

declare(strict_types=1);

namespace Misaf\VendraProductApi\State;

use ApiPlatform\Laravel\Eloquent\State\CollectionProvider;
use ApiPlatform\Laravel\Eloquent\State\ItemProvider;
use ApiPlatform\Laravel\Eloquent\State\LinksHandlerInterface;
use ApiPlatform\Metadata\CollectionOperationInterface;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\Pagination\PaginatorInterface;
use ApiPlatform\State\Pagination\TraversablePaginator;
use ApiPlatform\State\ProviderInterface;
use Generator;
use Illuminate\Database\Eloquent\Builder;
use Misaf\VendraProduct\Models\ProductCategory;
use Misaf\VendraProductApi\ApiResource\ProductCategoryResource;
use Misaf\VendraProductApi\State\Concerns\MapsCatalogResources;

/**
 * @implements LinksHandlerInterface<ProductCategory>
 * @implements ProviderInterface<object>
 */
final class ProductCategoryResourceProvider implements LinksHandlerInterface, ProviderInterface
{
    use MapsCatalogResources;

    /**
     * @param Builder<ProductCategory> $builder
     *
     * @return Builder<ProductCategory>
     */
    public function handleLinks(Builder $builder, array $uriVariables, array $context): Builder
    {
        $builder
            ->with([
                'products:id,product_category_id,name',
                'multimedia',
            ])
            ->where('active', true);

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
                return new TraversablePaginator(
                    $this->mapCollection($models),
                    $models->getCurrentPage(),
                    $models->getItemsPerPage(),
                    $models->getTotalItems(),
                );
            }

            return is_iterable($models) ? iterator_to_array($this->mapCollection($models), false) : [];
        }

        $model = app(ItemProvider::class)->provide($operation, $uriVariables, $context);

        return $model instanceof ProductCategory ? $this->toCategoryResource($model) : null;
    }

    /**
     * @param iterable<object> $models
     *
     * @return Generator<int, ProductCategoryResource>
     */
    private function mapCollection(iterable $models): Generator
    {
        foreach ($models as $model) {
            if ($model instanceof ProductCategory) {
                yield $this->toCategoryResource($model);
            }
        }
    }
}
