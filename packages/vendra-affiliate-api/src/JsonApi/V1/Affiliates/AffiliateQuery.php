<?php

declare(strict_types=1);

namespace Misaf\VendraAffiliateApi\JsonApi\V1\Affiliates;

use LaravelJsonApi\Laravel\Http\Requests\ResourceQuery;
use LaravelJsonApi\Validation\Rule as JsonApiRule;

final class AffiliateQuery extends ResourceQuery
{
    /**
     * Get the validation rules that apply to the request query parameters.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'fields' => [
                'nullable',
                'array',
                JsonApiRule::fieldSets(),
            ],
            'filter' => [
                'nullable',
                'array',
                JsonApiRule::filter()->forget('id'),
            ],
            'include'   => JsonApiRule::notSupported(),
            'page'      => JsonApiRule::notSupported(),
            'sort'      => JsonApiRule::notSupported(),
            'withCount' => JsonApiRule::notSupported(),
        ];
    }
}
