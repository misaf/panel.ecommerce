<?php

declare(strict_types=1);

namespace Misaf\VendraAffiliateApi\JsonApi\V1\Affiliates;

use LaravelJsonApi\Laravel\Http\Requests\ResourceQuery;
use LaravelJsonApi\Validation\Rule as JsonApiRule;

final class AffiliateCollectionQuery extends ResourceQuery
{
    /**
     * Get the validation rules that apply to the request.
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
                JsonApiRule::filter(),
            ],
            'filter.id'   => 'array',
            'filter.id.*' => 'integer',
            'filter.code' => 'string',
            'include'     => JsonApiRule::notSupported(),
            'page'        => [
                'nullable',
                'array',
                JsonApiRule::page(),
            ],
            'page.number' => ['integer', 'min:1'],
            'page.size'   => ['integer', 'between:1,100'],
            'sort'        => [
                'nullable',
                'string',
                JsonApiRule::sort(),
            ],
            'withCount' => JsonApiRule::notSupported(),
        ];
    }
}
