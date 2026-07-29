<?php

declare(strict_types=1);

namespace Misaf\VendraBlogApi\State;

use ApiPlatform\Laravel\Eloquent\Extension\FilterQueryExtension;
use ApiPlatform\Laravel\Eloquent\Paginator;
use ApiPlatform\Metadata\CollectionOperationInterface;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\Pagination\Pagination;
use ApiPlatform\State\ProviderInterface;
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

/**
 * @implements ProviderInterface<Paginator<BlogPostCategoryResource>|BlogPostCategoryResource>
 */
final class BlogPostCategoryResourceProvider implements ProviderInterface
{
    use NormalizesResourceValues;

    public function __construct(
        private readonly Pagination $pagination,
        private readonly FilterQueryExtension $filters,
    ) {}

    /**
     * @return Paginator<BlogPostCategoryResource>|BlogPostCategoryResource|array<int, BlogPostCategoryResource>|null
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
                return $query->get()->map(fn(Model $model): BlogPostCategoryResource => $this->toResource($model, $operation))->all();
            }

            $paginator = $query->paginate(
                perPage: $this->pagination->getLimit($operation, $context),
                page: $this->pagination->getPage($context),
            );
            $paginator->through(fn(Model $model): BlogPostCategoryResource => $this->toResource($model, $operation));

            return new Paginator($paginator);
        }

        $mcpData = $context['mcp_data'] ?? [];
        $identifier = $uriVariables['id'] ?? (is_array($mcpData) ? ($mcpData['id'] ?? null) : null);
        $model = $query->whereKey($identifier)->first();

        return $model instanceof BlogPostCategory ? $this->toResource($model, $operation) : null;
    }

    protected function query(Operation $operation): Builder
    {
        return BlogPostCategory::query()
            ->with([
                'blogPosts' => function (Relation $relation): void {
                    $relation->getQuery()
                        ->select(['id', 'blog_post_category_id', 'name'])
                        ->where('active', true);
                },
                'multimedia',
            ])
            ->where('active', true);
    }

    protected function toResource(Model $model, Operation $operation): BlogPostCategoryResource
    {
        /** @var BlogPostCategory $model */
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
                    ->map(fn(Model $media): MultimediaResource => MultimediaResourceFactory::make($media))
                    ->all()
                : [],
            createdAt: $model->created_at->toAtomString(),
            updatedAt: $model->updated_at->toAtomString(),
        );
    }
}
