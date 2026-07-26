<?php

declare(strict_types=1);

namespace Misaf\VendraCustomPageApi\JsonApi\V1\CustomPages;

use LaravelJsonApi\Core\Resources\JsonApiResource;
use Misaf\VendraCustomPage\Models\CustomPage;

/**
 * @mixin CustomPage
 */
final class CustomPageResource extends JsonApiResource
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
            'active'      => $this->active,
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
            $this->relation('customPageCategory'),
            $this->relation('multimedia'),
        ];
    }
}
