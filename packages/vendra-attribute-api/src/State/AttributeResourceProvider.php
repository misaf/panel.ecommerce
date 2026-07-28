<?php

declare(strict_types=1);

namespace Misaf\VendraAttributeApi\State;

use ApiPlatform\Metadata\Operation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Misaf\VendraApi\ApiResource\ResourceReference;
use Misaf\VendraApi\State\EloquentResourceProvider;
use Misaf\VendraAttribute\Models\Attribute;
use Misaf\VendraAttribute\Models\AttributeValue;
use Misaf\VendraAttributeApi\ApiResource\AttributeResource;
use Misaf\VendraAttributeApi\ApiResource\AttributeValueResource;

/**
 * @extends EloquentResourceProvider<Model, AttributeResource|AttributeValueResource>
 */
final class AttributeResourceProvider extends EloquentResourceProvider
{
    protected function query(Operation $operation): Builder
    {
        if (AttributeValueResource::class === $operation->getClass()) {
            return AttributeValue::query()
                ->with('attribute:id,name')
                ->whereHas('attribute', fn(Builder $query): Builder => $query->where('active', true));
        }

        return Attribute::query()
            ->with('values:id,attribute_id,value')
            ->where('active', true);
    }

    protected function toResource(Model $model, Operation $operation): AttributeResource|AttributeValueResource
    {
        if ($model instanceof AttributeValue) {
            return new AttributeValueResource(
                id: $model->id,
                value: $model->value,
                attribute: new ResourceReference($model->attribute->id, 'Attribute', $model->attribute->name),
            );
        }

        /** @var Attribute $model */
        return new AttributeResource(
            id: $model->id,
            name: $model->name,
            description: $model->description,
            unit: $model->unit,
            active: $model->active,
            options: $model->values
                ->map(fn(AttributeValue $value): ResourceReference => new ResourceReference($value->id, 'AttributeValue', $value->value))
                ->all(),
        );
    }
}
