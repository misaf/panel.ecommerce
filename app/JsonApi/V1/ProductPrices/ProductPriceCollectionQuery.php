<?php

declare(strict_types=1);

namespace App\JsonApi\V1\ProductPrices;

use LaravelJsonApi\Laravel\Http\Requests\ResourceQuery;
use LaravelJsonApi\Validation\Rule as JsonApiRule;

final class ProductPriceCollectionQuery extends ResourceQuery
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
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
            'filter.id'                 => 'array',
            'filter.id.*'               => 'integer',
            'filter.exclude'            => 'array',
            'filter.exclude.*'          => 'integer',
            'filter.product'            => 'integer',
            'filter.currency'           => 'integer',
            'filter.with-product'       => 'array',
            'filter.with-product.*'     => 'string',
            'filter.without-product'    => 'array',
            'filter.without-product.*'  => 'string',
            'filter.in-product.*'       => 'integer',
            'filter.not-in-product.*'   => 'integer',
            'filter.with-currency'      => 'array',
            'filter.with-currency.*'    => 'string',
            'filter.without-currency'   => 'array',
            'filter.without-currency.*' => 'string',
            'filter.in-currency.*'      => 'integer',
            'filter.not-in-currency.*'  => 'integer',
            'filter.with-trashed'       => 'boolean',
            'filter.only-trashed'       => 'boolean',
            'include'                   => [
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
