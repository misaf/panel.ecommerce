<?php

declare(strict_types=1);

namespace Misaf\VendraAttributeApi\State;

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
use Misaf\VendraAttribute\Models\Attribute;
use Misaf\VendraAttribute\Models\AttributeValue;
use Misaf\VendraAttributeApi\ApiResource\AttributeValueResource;
use UnexpectedValueException;

/**
 * @implements ProviderInterface<Paginator<AttributeValueResource>|AttributeValueResource>
 */
final class AttributeValueResourceProvider implements ProviderInterface
{
    use NormalizesResourceValues;

    public function __construct(
        private readonly Pagination $pagination,
        private readonly FilterQueryExtension $filters,
    ) {}

    /**
     * @return Paginator<AttributeValueResource>|AttributeValueResource|array<int, AttributeValueResource>|null
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
                return $query->get()->map(fn(Model $model): AttributeValueResource => $this->toResource($model, $operation))->all();
            }

            $paginator = $query->paginate(
                perPage: $this->pagination->getLimit($operation, $context),
                page: $this->pagination->getPage($context),
            );
            $paginator->through(fn(Model $model): AttributeValueResource => $this->toResource($model, $operation));

            return new Paginator($paginator);
        }

        $mcpData = $context['mcp_data'] ?? [];
        $identifier = $uriVariables['id'] ?? (is_array($mcpData) ? ($mcpData['id'] ?? null) : null);
        $model = $query->whereKey($identifier)->first();

        return $model instanceof AttributeValue ? $this->toResource($model, $operation) : null;
    }

    protected function query(Operation $operation): Builder
    {
        return AttributeValue::query()
            ->with('attribute:id,name')
            ->whereHas('attribute', fn(Builder $query): Builder => $query->where('active', true));
    }

    protected function toResource(Model $model, Operation $operation): AttributeValueResource
    {
        /** @var AttributeValue $model */
        $attribute = $model->attribute;

        if ( ! $attribute instanceof Attribute) {
            throw new UnexpectedValueException('An attribute value must belong to an attribute.');
        }

        return new AttributeValueResource(
            id: $model->id,
            value: $model->value,
            attribute: new ResourceReference(
                $attribute->id,
                'Attribute',
                $attribute->name,
            ),
        );
    }
}
