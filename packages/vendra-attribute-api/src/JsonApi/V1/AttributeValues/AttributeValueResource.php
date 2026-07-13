<?php

declare(strict_types=1);

namespace Misaf\VendraAttributeApi\JsonApi\V1\AttributeValues;

use LaravelJsonApi\Core\Resources\JsonApiResource;
use Misaf\VendraAttribute\Models\AttributeValue;

/** @mixin AttributeValue */
final class AttributeValueResource extends JsonApiResource
{
    /**
     * @return iterable<string, mixed>
     */
    public function attributes($request): iterable
    {
        return [
            'attribute_id' => $this->attribute_id,
            'value'        => $this->value,
            'position'     => $this->position,
        ];
    }
}
