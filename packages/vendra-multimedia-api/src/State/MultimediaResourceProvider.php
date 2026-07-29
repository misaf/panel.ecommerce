<?php

declare(strict_types=1);

namespace Misaf\VendraMultimediaApi\State;

use ApiPlatform\Laravel\Eloquent\Extension\FilterQueryExtension;
use ApiPlatform\Laravel\Eloquent\Paginator;
use ApiPlatform\Metadata\CollectionOperationInterface;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\Pagination\Pagination;
use ApiPlatform\State\ProviderInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Misaf\VendraMultimedia\Models\Multimedia;
use Misaf\VendraMultimediaApi\ApiResource\MultimediaResource;

/**
 * @implements ProviderInterface<Paginator<MultimediaResource>|MultimediaResource>
 */
final class MultimediaResourceProvider implements ProviderInterface
{
    public function __construct(
        private readonly Pagination $pagination,
        private readonly FilterQueryExtension $filters,
    ) {}

    /**
     * @return Paginator<MultimediaResource>|MultimediaResource|array<int, MultimediaResource>|null
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
                return $query->get()->map(fn(Model $model): MultimediaResource => $this->toResource($model, $operation))->all();
            }

            $paginator = $query->paginate(
                perPage: $this->pagination->getLimit($operation, $context),
                page: $this->pagination->getPage($context),
            );
            $paginator->through(fn(Model $model): MultimediaResource => $this->toResource($model, $operation));

            return new Paginator($paginator);
        }

        $mcpData = $context['mcp_data'] ?? [];
        $identifier = $uriVariables['id'] ?? (is_array($mcpData) ? ($mcpData['id'] ?? null) : null);
        $model = $query->whereKey($identifier)->first();

        return $model instanceof Multimedia ? $this->toResource($model, $operation) : null;
    }

    protected function query(Operation $operation): Builder
    {
        return Multimedia::query()->select([
            'id', 'uuid', 'name', 'file_name', 'collection_name', 'mime_type', 'size',
            'disk', 'conversions_disk', 'manipulations', 'custom_properties', 'generated_conversions', 'responsive_images',
        ]);
    }

    protected function toResource(Model $model, Operation $operation): MultimediaResource
    {
        /** @var Multimedia $model */
        return MultimediaResourceFactory::make($model);
    }
}
