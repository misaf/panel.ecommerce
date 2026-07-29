<?php

declare(strict_types=1);

namespace Misaf\VendraAffiliateApi\State;

use ApiPlatform\Laravel\Eloquent\Extension\FilterQueryExtension;
use ApiPlatform\Laravel\Eloquent\Paginator;
use ApiPlatform\Metadata\CollectionOperationInterface;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\Pagination\Pagination;
use ApiPlatform\State\ProviderInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Misaf\VendraAffiliate\Enums\AffiliateStatusEnum;
use Misaf\VendraAffiliate\Models\Affiliate;
use Misaf\VendraAffiliateApi\ApiResource\AffiliateResource;

/**
 * @implements ProviderInterface<Paginator<AffiliateResource>|AffiliateResource>
 */
final class AffiliateResourceProvider implements ProviderInterface
{
    public function __construct(
        private readonly Pagination $pagination,
        private readonly FilterQueryExtension $filters,
    ) {}

    /**
     * @return Paginator<AffiliateResource>|AffiliateResource|array<int, AffiliateResource>|null
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
                return $query->get()->map(fn(Model $model): AffiliateResource => $this->toResource($model, $operation))->all();
            }

            $paginator = $query->paginate(
                perPage: $this->pagination->getLimit($operation, $context),
                page: $this->pagination->getPage($context),
            );
            $paginator->through(fn(Model $model): AffiliateResource => $this->toResource($model, $operation));

            return new Paginator($paginator);
        }

        $mcpData = $context['mcp_data'] ?? [];
        $identifier = $uriVariables['id'] ?? (is_array($mcpData) ? ($mcpData['id'] ?? null) : null);
        $model = $query->whereKey($identifier)->first();

        return $model instanceof Affiliate ? $this->toResource($model, $operation) : null;
    }

    protected function query(Operation $operation): Builder
    {
        return Affiliate::query()
            ->select(['id', 'code', 'created_at'])
            ->where('status', AffiliateStatusEnum::Active);
    }

    protected function toResource(Model $model, Operation $operation): AffiliateResource
    {
        /** @var Affiliate $model */
        return new AffiliateResource(
            id: $model->id,
            code: $model->code,
            createdAt: $model->created_at->toAtomString(),
        );
    }
}
