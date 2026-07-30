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
use Illuminate\Database\Eloquent\Relations\Relation;
use Misaf\VendraApi\ApiResource\ResourceReference;
use Misaf\VendraApi\State\Concerns\NormalizesResourceValues;
use Misaf\VendraBlog\Models\BlogPost;
use Misaf\VendraBlog\Models\BlogPostCategory;
use Misaf\VendraBlogApi\ApiResource\BlogPostCategoryResource;
use Misaf\VendraMultimediaApi\ApiResource\MultimediaResource;
use Misaf\VendraMultimediaApi\State\MultimediaResourceFactory;
use Misaf\VendraMultimediaApi\State\PublicMultimedia;

/**
 * @implements LinksHandlerInterface<BlogPostCategory>
 * @implements ProviderInterface<object>
 */
final class BlogPostCategoryResourceProvider implements LinksHandlerInterface, ProviderInterface
{
    use NormalizesResourceValues;

    /**
     * @param Builder<BlogPostCategory> $builder
     *
     * @return Builder<BlogPostCategory>
     */
    public function handleLinks(Builder $builder, array $uriVariables, array $context): Builder
    {
        $builder
            ->with([
                'blogPosts' => function (Relation $relation): void {
                    $relation->getQuery()
                        ->select(['id', 'blog_post_category_id', 'name'])
                        ->where('active', true);
                },
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

        return $model instanceof BlogPostCategory ? $this->toResource($model) : null;
    }

    /**
     * @param iterable<object> $models
     *
     * @return Generator<int, BlogPostCategoryResource>
     */
    private function mapCollection(iterable $models): Generator
    {
        foreach ($models as $model) {
            if ($model instanceof BlogPostCategory) {
                yield $this->toResource($model);
            }
        }
    }

    private function toResource(BlogPostCategory $model): BlogPostCategoryResource
    {
        return new BlogPostCategoryResource(
            id: $model->id,
            title: $this->normalizeTranslations($model->getTranslations('name')),
            slugs: $this->normalizeTranslations($model->getTranslations('slug')),
            description: $this->normalizeTranslations($model->getTranslations('description')),
            position: $model->position,
            active: $model->active,
            blogPosts: $model->blogPosts
                ->map(function (BlogPost $post): ResourceReference {
                    $name = $post->getTranslation('name', app()->getLocale());

                    return new ResourceReference(
                        $post->id,
                        'BlogPost',
                        is_string($name) ? $name : null,
                    );
                })
                ->all(),
            multimedia: $model->relationLoaded('multimedia')
                ? $model->multimedia
                    ->filter(fn(Model $media): bool => PublicMultimedia::isPublic($media))
                    ->map(fn(Model $media): MultimediaResource => MultimediaResourceFactory::make($media))
                    ->values()
                    ->all()
                : [],
            createdAt: $model->created_at->toAtomString(),
            updatedAt: $model->updated_at->toAtomString(),
        );
    }
}
