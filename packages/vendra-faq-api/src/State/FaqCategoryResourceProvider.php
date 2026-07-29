<?php

declare(strict_types=1);

namespace Misaf\VendraFaqApi\State;

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
use Misaf\VendraFaq\Models\Faq;
use Misaf\VendraFaq\Models\FaqCategory;
use Misaf\VendraFaqApi\ApiResource\FaqCategoryResource;
use Misaf\VendraMultimediaApi\ApiResource\MultimediaResource;
use Misaf\VendraMultimediaApi\State\MultimediaResourceFactory;

/**
 * @implements ProviderInterface<Paginator<FaqCategoryResource>|FaqCategoryResource>
 */
final class FaqCategoryResourceProvider implements ProviderInterface
{
    use NormalizesResourceValues;

    public function __construct(
        private readonly Pagination $pagination,
        private readonly FilterQueryExtension $filters,
    ) {}

    /**
     * @return Paginator<FaqCategoryResource>|FaqCategoryResource|array<int, FaqCategoryResource>|null
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
                return $query->get()->map(fn(Model $model): FaqCategoryResource => $this->toResource($model, $operation))->all();
            }

            $paginator = $query->paginate(
                perPage: $this->pagination->getLimit($operation, $context),
                page: $this->pagination->getPage($context),
            );
            $paginator->through(fn(Model $model): FaqCategoryResource => $this->toResource($model, $operation));

            return new Paginator($paginator);
        }

        $mcpData = $context['mcp_data'] ?? [];
        $identifier = $uriVariables['id'] ?? (is_array($mcpData) ? ($mcpData['id'] ?? null) : null);
        $model = $query->whereKey($identifier)->first();

        return $model instanceof FaqCategory ? $this->toResource($model, $operation) : null;
    }

    protected function query(Operation $operation): Builder
    {
        return FaqCategory::query()
            ->with([
                'faqs' => function (Relation $relation): void {
                    $relation->getQuery()
                        ->select(['id', 'faq_category_id', 'name'])
                        ->where('active', true);
                },
                'multimedia',
            ])
            ->where('active', true);
    }

    protected function toResource(Model $model, Operation $operation): FaqCategoryResource
    {
        /** @var FaqCategory $model */
        return new FaqCategoryResource(
            id: $model->id,
            title: $this->normalizeTranslations($model->getTranslations('name')),
            slugs: $this->normalizeTranslations($model->getTranslations('slug')),
            description: $this->normalizeTranslations($model->getTranslations('description')),
            position: $model->position,
            active: $model->active,
            faqs: $model->faqs
                ->map(function (Faq $faq): ResourceReference {
                    $name = $faq->getTranslation('name', app()->getLocale());

                    return new ResourceReference(
                        $faq->id,
                        'Faq',
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
