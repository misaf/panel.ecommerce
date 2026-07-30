<?php

declare(strict_types=1);

namespace Misaf\VendraBlogApi\State;

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
use Misaf\VendraBlog\Models\BlogPost;
use Misaf\VendraBlog\Models\BlogPostCategory;
use Misaf\VendraBlogApi\ApiResource\BlogPostResource;
use Misaf\VendraMultimediaApi\ApiResource\MultimediaResource;
use Misaf\VendraMultimediaApi\State\MultimediaResourceFactory;
use Misaf\VendraMultimediaApi\State\PublicMultimedia;
use UnexpectedValueException;

/**
 * @implements LinksHandlerInterface<BlogPost>
 * @implements ProviderInterface<object>
 */
final class BlogPostResourceProvider implements LinksHandlerInterface, ProviderInterface
{
    use NormalizesResourceValues;

    /**
     * @param Builder<BlogPost> $builder
     *
     * @return Builder<BlogPost>
     */
    public function handleLinks(Builder $builder, array $uriVariables, array $context): Builder
    {
        $builder
            ->with([
                'blogPostCategory:id,name,slug,description,position,active,created_at,updated_at',
                'multimedia',
            ])
            ->whereHas('blogPostCategory', fn(Builder $query): Builder => $query->where('active', true))
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

        return $model instanceof BlogPost ? $this->toResource($model) : null;
    }

    /**
     * @param iterable<object> $models
     *
     * @return Generator<int, BlogPostResource>
     */
    private function mapCollection(iterable $models): Generator
    {
        foreach ($models as $model) {
            if ($model instanceof BlogPost) {
                yield $this->toResource($model);
            }
        }
    }

    private function toResource(BlogPost $model): BlogPostResource
    {
        $category = $model->blogPostCategory;

        if ( ! $category instanceof BlogPostCategory) {
            throw new UnexpectedValueException('A blog post must belong to a category.');
        }

        $categoryName = $category->getTranslation('name', app()->getLocale());

        return new BlogPostResource(
            id: $model->id,
            title: $this->normalizeTranslations($model->getTranslations('name')),
            content: $this->normalizeTranslations($model->getTranslations('description')),
            slugs: $this->normalizeTranslations($model->getTranslations('slug')),
            active: $model->active,
            position: $model->position,
            blogPostCategory: new ResourceReference(
                $category->id,
                'BlogPostCategory',
                is_string($categoryName) ? $categoryName : null,
            ),
            multimedia: $model->multimedia
                ->filter(fn(Model $media): bool => PublicMultimedia::isPublic($media))
                ->map(fn(Model $media): MultimediaResource => MultimediaResourceFactory::make($media))
                ->values()
                ->all(),
            publishedAt: $model->created_at->toAtomString(),
            createdAt: $model->created_at->toAtomString(),
            updatedAt: $model->updated_at->toAtomString(),
        );
    }
}
