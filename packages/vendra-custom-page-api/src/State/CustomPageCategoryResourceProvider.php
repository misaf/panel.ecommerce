<?php

declare(strict_types=1);

namespace Misaf\VendraCustomPageApi\State;

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
use Misaf\VendraCustomPage\Models\CustomPage;
use Misaf\VendraCustomPage\Models\CustomPageCategory;
use Misaf\VendraCustomPageApi\ApiResource\CustomPageCategoryResource;

/**
 * @implements ProviderInterface<Paginator<CustomPageCategoryResource>|CustomPageCategoryResource>
 */
final class CustomPageCategoryResourceProvider implements ProviderInterface
{
    use NormalizesResourceValues;

    public function __construct(
        private readonly Pagination $pagination,
        private readonly FilterQueryExtension $filters,
    ) {}

    /**
     * @return Paginator<CustomPageCategoryResource>|CustomPageCategoryResource|array<int, CustomPageCategoryResource>|null
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
                return $query->get()->map(fn(Model $model): CustomPageCategoryResource => $this->toResource($model, $operation))->all();
            }

            $paginator = $query->paginate(
                perPage: $this->pagination->getLimit($operation, $context),
                page: $this->pagination->getPage($context),
            );
            $paginator->through(fn(Model $model): CustomPageCategoryResource => $this->toResource($model, $operation));

            return new Paginator($paginator);
        }

        $mcpData = $context['mcp_data'] ?? [];
        $identifier = $uriVariables['id'] ?? (is_array($mcpData) ? ($mcpData['id'] ?? null) : null);
        $model = $query->whereKey($identifier)->first();

        return $model instanceof CustomPageCategory ? $this->toResource($model, $operation) : null;
    }

    protected function query(Operation $operation): Builder
    {
        return CustomPageCategory::query()
            ->with([
                'customPages' => function (Relation $relation): void {
                    $relation->getQuery()
                        ->select(['id', 'custom_page_category_id', 'name'])
                        ->where('active', true);
                },
            ])
            ->where('active', true);
    }

    protected function toResource(Model $model, Operation $operation): CustomPageCategoryResource
    {
        /** @var CustomPageCategory $model */
        return new CustomPageCategoryResource(
            id: $model->id,
            title: $this->normalizeTranslations($model->getTranslations('name')),
            customPages: $model->customPages
                ->map(function (CustomPage $page): ResourceReference {
                    $name = $page->getTranslation('name', app()->getLocale());

                    return new ResourceReference(
                        $page->id,
                        'CustomPage',
                        is_string($name) ? $name : null,
                    );
                })
                ->all(),
        );
    }
}
