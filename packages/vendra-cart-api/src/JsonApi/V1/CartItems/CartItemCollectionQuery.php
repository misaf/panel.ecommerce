<?php

declare(strict_types=1);

namespace Misaf\VendraCartApi\JsonApi\V1\CartItems;

use LaravelJsonApi\Laravel\Http\Requests\ResourceQuery;
use LaravelJsonApi\Validation\Rule as JsonApiRule;

final class CartItemCollectionQuery extends ResourceQuery
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
            'filter.id'                => 'array',
            'filter.id.*'              => 'integer',
            'filter.exclude'           => 'array',
            'filter.exclude.*'         => 'integer',
            'filter.cart'              => 'integer',
            'filter.sellable-type'     => 'string',
            'filter.sellable-id'       => 'integer',
            'filter.quantity'          => 'integer',
            'filter.gt-quantity'       => 'integer',
            'filter.gte-quantity'      => 'integer',
            'filter.lt-quantity'       => 'integer',
            'filter.lte-quantity'      => 'integer',
            'filter.with-cart'         => 'array',
            'filter.with-cart.*'       => 'string',
            'filter.without-cart'      => 'array',
            'filter.without-cart.*'    => 'string',
            'include'                  => [
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
