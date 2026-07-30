<?php

declare(strict_types=1);

namespace Misaf\VendraMultimediaApi\State;

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
use Misaf\VendraMultimediaApi\ApiResource\MultimediaResource;

/**
 * @implements LinksHandlerInterface<PublicMultimedia>
 * @implements ProviderInterface<object>
 */
final class MultimediaResourceProvider implements LinksHandlerInterface, ProviderInterface
{
    /**
     * @param Builder<PublicMultimedia> $builder
     *
     * @return Builder<PublicMultimedia>
     */
    public function handleLinks(Builder $builder, array $uriVariables, array $context): Builder
    {
        $builder->select([
            'id', 'uuid', 'name', 'file_name', 'collection_name', 'mime_type', 'size',
            'disk', 'conversions_disk', 'manipulations', 'custom_properties', 'generated_conversions', 'responsive_images',
        ]);

        PublicMultimedia::scope($builder);

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

        return $model instanceof PublicMultimedia ? MultimediaResourceFactory::make($model) : null;
    }

    /**
     * @param iterable<object> $models
     *
     * @return Generator<int, MultimediaResource>
     */
    private function mapCollection(iterable $models): Generator
    {
        foreach ($models as $model) {
            if ($model instanceof PublicMultimedia) {
                yield MultimediaResourceFactory::make($model);
            }
        }
    }
}
