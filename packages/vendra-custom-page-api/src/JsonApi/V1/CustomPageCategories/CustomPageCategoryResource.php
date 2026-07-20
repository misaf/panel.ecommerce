<?php

declare(strict_types=1);

namespace Misaf\VendraCustomPageApi\JsonApi\V1\CustomPageCategories;

use LaravelJsonApi\Core\Resources\JsonApiResource;
use Misaf\VendraCustomPage\Models\CustomPageCategory;

/**
 * @mixin CustomPageCategory
 */
final class CustomPageCategoryResource extends JsonApiResource
{
    /**
     * @return iterable<string, mixed>
     */
    public function attributes($request): iterable
    {
        return [
            'name'        => $this->name,
            'description' => $this->description,
            'slug'        => $this->slug,
            'position'    => $this->position,
            'status'      => $this->status,
            'created_at'  => $this->created_at,
            'updated_at'  => $this->updated_at,
        ];
    }

    /**
     * @return iterable<int, mixed>
     */
    public function relationships($request): iterable
    {
        return [
            $this->relation('customPages'),
            $this->relation('multimedia'),
        ];
    }
}
