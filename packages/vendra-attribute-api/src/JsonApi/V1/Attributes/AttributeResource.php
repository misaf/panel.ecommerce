<?php

declare(strict_types=1);

namespace Misaf\VendraAttributeApi\JsonApi\V1\Attributes;

use LaravelJsonApi\Core\Resources\JsonApiResource;
use Misaf\VendraAttribute\Models\Attribute;

/** @mixin Attribute */
final class AttributeResource extends JsonApiResource
{
    /**
     * @return iterable<string, mixed>
     */
    public function attributes($request): iterable
    {
        return [
            'name'        => $this->name,
            'description' => $this->description,
            'unit'        => $this->unit,
            'position'    => $this->position,
            'status'      => $this->status,
            'created_at'  => $this->created_at,
            'updated_at'  => $this->updated_at,
        ];
    }
}
