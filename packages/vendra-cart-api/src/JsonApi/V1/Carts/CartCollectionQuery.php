<?php

declare(strict_types=1);

namespace Misaf\VendraCartApi\JsonApi\V1\Carts;

use LaravelJsonApi\Laravel\Http\Requests\ResourceQuery;
use LaravelJsonApi\Validation\Rule as JsonApiRule;

final class CartCollectionQuery extends ResourceQuery
{
    /**
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
            'filter.id'              => 'array',
            'filter.id.*'            => 'integer',
            'filter.exclude'         => 'array',
            'filter.exclude.*'       => 'integer',
            'filter.token'           => 'string',
            'filter.expires-before'  => JsonApiRule::dateTime(),
            'filter.expires-after'   => JsonApiRule::dateTime(),
            'filter.has-items'       => 'boolean',
            'filter.with-items'      => 'array',
            'filter.with-items.*'    => 'string',
            'filter.without-items'   => 'array',
            'filter.without-items.*' => 'string',
            'include'                => [
                'nullable',
                'string',
                JsonApiRule::includePaths(),
            ],
            'page' => [
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
            'withCount' => [
                'nullable',
                'string',
                JsonApiRule::countable(),
            ],
        ];
    }
}
