<?php

declare(strict_types=1);

namespace Misaf\VendraCustomPageApi\State;

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
use Illuminate\Database\Eloquent\Model;
use Misaf\VendraApi\ApiResource\ResourceReference;
use Misaf\VendraApi\State\Concerns\NormalizesResourceValues;
use Misaf\VendraCustomPage\Models\CustomPage;
use Misaf\VendraCustomPage\Models\CustomPageCategory;
use Misaf\VendraCustomPageApi\ApiResource\CustomPageResource;
use Misaf\VendraMultimediaApi\ApiResource\MultimediaResource;
use Misaf\VendraMultimediaApi\State\MultimediaResourceFactory;
use Misaf\VendraMultimediaApi\State\PublicMultimedia;
use UnexpectedValueException;

/**
 * @implements LinksHandlerInterface<CustomPage>
 * @implements ProviderInterface<object>
 */
final class CustomPageResourceProvider implements LinksHandlerInterface, ProviderInterface
{
    use NormalizesResourceValues;

    /**
     * @param Builder<CustomPage> $builder
     *
     * @return Builder<CustomPage>
     */
    public function handleLinks(Builder $builder, array $uriVariables, array $context): Builder
    {
        $builder
            ->with(['customPageCategory:id,name', 'multimedia'])
            ->whereHas('customPageCategory', fn(Builder $query): Builder => $query->where('active', true))
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

        return $model instanceof CustomPage ? $this->toResource($model) : null;
    }

    /**
     * @param iterable<object> $models
     *
     * @return Generator<int, CustomPageResource>
     */
    private function mapCollection(iterable $models): Generator
    {
        foreach ($models as $model) {
            if ($model instanceof CustomPage) {
                yield $this->toResource($model);
            }
        }
    }

    private function toResource(CustomPage $model): CustomPageResource
    {
        $category = $model->customPageCategory;

        if ( ! $category instanceof CustomPageCategory) {
            throw new UnexpectedValueException('A custom page must belong to a category.');
        }

        $categoryName = $category->getTranslation('name', app()->getLocale());

        return new CustomPageResource(
            id: $model->id,
            title: $this->normalizeTranslations($model->getTranslations('name')),
            body: $this->normalizeTranslations($model->getTranslations('description')),
            slugs: $this->normalizeTranslations($model->getTranslations('slug')),
            customPageCategory: new ResourceReference(
                $category->id,
                'CustomPageCategory',
                is_string($categoryName) ? $categoryName : null,
            ),
            multimedia: $model->multimedia
                ->filter(fn(Model $media): bool => PublicMultimedia::isPublic($media))
                ->map(fn(Model $media): MultimediaResource => MultimediaResourceFactory::make($media))
                ->values()
                ->all(),
        );
    }
}
