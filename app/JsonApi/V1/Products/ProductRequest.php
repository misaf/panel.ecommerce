<?php

declare(strict_types=1);

namespace App\JsonApi\V1\Products;

use LaravelJsonApi\Laravel\Http\Requests\ResourceRequest;

final class ProductRequest extends ResourceRequest
{
    /**
     * Get the validation rules for the resource.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            // @TODO
        ];
    }
}
