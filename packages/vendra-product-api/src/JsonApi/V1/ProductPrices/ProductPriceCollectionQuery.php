<?php

declare(strict_types=1);

namespace Misaf\VendraProductApi\JsonApi\V1\ProductPrices;

use LaravelJsonApi\Laravel\Http\Requests\ResourceQuery;
use LaravelJsonApi\Validation\Rule as JsonApiRule;

final class ProductPriceCollectionQuery extends ResourceQuery
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
            'filter.id'                         => 'array',
            'filter.id.*'                       => 'integer',
            'filter.exclude'                    => 'array',
            'filter.exclude.*'                  => 'integer',
            'filter.currency_code'              => 'string',
            'filter.price'                      => 'integer',
            'filter.gt-price'                   => 'integer',
            'filter.gte-price'                  => 'integer',
            'filter.lt-price'                   => 'integer',
            'filter.lte-price'                  => 'integer',
            'filter.with-product'               => 'array',
            'filter.with-product.*'             => 'string',
            'filter.without-product'            => 'array',
            'filter.without-product.*'          => 'string',
            'filter.with-product-category'      => 'array',
            'filter.with-product-category.*'    => 'string',
            'filter.without-product-category'   => 'array',
            'filter.without-product-category.*' => 'string',
            'filter.with-trashed'               => 'boolean',
            'filter.only-trashed'               => 'boolean',
            'include'                           => [
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
