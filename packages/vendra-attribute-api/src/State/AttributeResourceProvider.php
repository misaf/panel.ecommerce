<?php

declare(strict_types=1);

namespace Misaf\VendraAttributeApi\State;

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
use Misaf\VendraApi\ApiResource\ResourceReference;
use Misaf\VendraAttribute\Models\Attribute;
use Misaf\VendraAttribute\Models\AttributeValue;
use Misaf\VendraAttributeApi\ApiResource\AttributeResource;

/**
 * @implements LinksHandlerInterface<Attribute>
 * @implements ProviderInterface<object>
 */
final class AttributeResourceProvider implements LinksHandlerInterface, ProviderInterface
{
    /**
     * @param Builder<Attribute> $builder
     *
     * @return Builder<Attribute>
     */
    public function handleLinks(Builder $builder, array $uriVariables, array $context): Builder
    {
        $builder
            ->with('values:id,attribute_id,value')
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

        return $model instanceof Attribute ? $this->toResource($model) : null;
    }

    /**
     * @param iterable<object> $models
     *
     * @return Generator<int, AttributeResource>
     */
    private function mapCollection(iterable $models): Generator
    {
        foreach ($models as $model) {
            if ($model instanceof Attribute) {
                yield $this->toResource($model);
            }
        }
    }

    private function toResource(Attribute $model): AttributeResource
    {
        return new AttributeResource(
            id: $model->id,
            name: $model->name,
            description: $model->description,
            unit: $model->unit,
            active: $model->active,
            values: $model->values
                ->map(fn(AttributeValue $value): ResourceReference => new ResourceReference($value->id, 'AttributeValue', $value->value))
                ->all(),
        );
    }
}
