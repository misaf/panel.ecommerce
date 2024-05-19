<?php

declare(strict_types=1);

namespace App\JsonApi\V1\ProductPrices;

use LaravelJsonApi\Laravel\Http\Requests\ResourceRequest;

final class ProductPriceRequest extends ResourceRequest
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
