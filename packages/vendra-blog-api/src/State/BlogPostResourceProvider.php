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
use Misaf\VendraApi\ApiResource\ResourceReference;
use Misaf\VendraApi\State\Concerns\NormalizesResourceValues;
use Misaf\VendraBlog\Models\BlogPost;
use Misaf\VendraBlog\Models\BlogPostCategory;
use Misaf\VendraBlogApi\ApiResource\BlogPostResource;
use Misaf\VendraMultimediaApi\ApiResource\MultimediaResource;
use Misaf\VendraMultimediaApi\State\MultimediaResourceFactory;
use UnexpectedValueException;

/**
 * @implements ProviderInterface<Paginator<BlogPostResource>|BlogPostResource>
 */
final class BlogPostResourceProvider implements ProviderInterface
{
    use NormalizesResourceValues;

    public function __construct(
        private readonly Pagination $pagination,
        private readonly FilterQueryExtension $filters,
    ) {}

    /**
     * @return Paginator<BlogPostResource>|BlogPostResource|array<int, BlogPostResource>|null
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
                return $query->get()->map(fn(Model $model): BlogPostResource => $this->toResource($model, $operation))->all();
            }

            $paginator = $query->paginate(
                perPage: $this->pagination->getLimit($operation, $context),
                page: $this->pagination->getPage($context),
            );
            $paginator->through(fn(Model $model): BlogPostResource => $this->toResource($model, $operation));

            return new Paginator($paginator);
        }

        $mcpData = $context['mcp_data'] ?? [];
        $identifier = $uriVariables['id'] ?? (is_array($mcpData) ? ($mcpData['id'] ?? null) : null);
        $model = $query->whereKey($identifier)->first();

        return $model instanceof BlogPost ? $this->toResource($model, $operation) : null;
    }

    protected function query(Operation $operation): Builder
    {
        return BlogPost::query()
            ->with([
                'blogPostCategory:id,name,slug,description,position,active,created_at,updated_at',
                'multimedia',
            ])
            ->whereHas('blogPostCategory', fn(Builder $query): Builder => $query->where('active', true))
            ->where('active', true);
    }

    protected function toResource(Model $model, Operation $operation): BlogPostResource
    {
        /** @var BlogPost $model */
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
                ->map(fn(Model $media): MultimediaResource => MultimediaResourceFactory::make($media))
                ->all(),
            publishedAt: $model->created_at->toAtomString(),
            createdAt: $model->created_at->toAtomString(),
            updatedAt: $model->updated_at->toAtomString(),
        );
    }
}
