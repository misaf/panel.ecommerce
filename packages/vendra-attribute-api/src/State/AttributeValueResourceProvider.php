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
use Misaf\VendraAttributeApi\ApiResource\AttributeValueResource;
use UnexpectedValueException;

/**
 * @implements LinksHandlerInterface<AttributeValue>
 * @implements ProviderInterface<object>
 */
final class AttributeValueResourceProvider implements LinksHandlerInterface, ProviderInterface
{
    /**
     * @param Builder<AttributeValue> $builder
     *
     * @return Builder<AttributeValue>
     */
    public function handleLinks(Builder $builder, array $uriVariables, array $context): Builder
    {
        $builder
            ->with('attribute:id,name')
            ->whereHas('attribute', fn(Builder $query): Builder => $query->where('active', true));

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

        return $model instanceof AttributeValue ? $this->toResource($model) : null;
    }

    /**
     * @param iterable<object> $models
     *
     * @return Generator<int, AttributeValueResource>
     */
    private function mapCollection(iterable $models): Generator
    {
        foreach ($models as $model) {
            if ($model instanceof AttributeValue) {
                yield $this->toResource($model);
            }
        }
    }

    private function toResource(AttributeValue $model): AttributeValueResource
    {
        $attribute = $model->attribute;

        if ( ! $attribute instanceof Attribute) {
            throw new UnexpectedValueException('An attribute value must belong to an attribute.');
        }

        return new AttributeValueResource(
            id: $model->id,
            value: $model->value,
            attribute: new ResourceReference(
                $attribute->id,
                'Attribute',
                $attribute->name,
            ),
        );
    }
}
