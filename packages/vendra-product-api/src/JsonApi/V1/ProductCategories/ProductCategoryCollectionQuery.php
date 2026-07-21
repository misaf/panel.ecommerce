<?php

declare(strict_types=1);

namespace Misaf\VendraProductApi\JsonApi\V1\ProductCategories;

use LaravelJsonApi\Laravel\Http\Requests\ResourceQuery;
use LaravelJsonApi\Validation\Rule as JsonApiRule;
use Misaf\VendraSupport\Support\AttributeApiIntegration;

final class ProductCategoryCollectionQuery extends ResourceQuery
{
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $rules = [
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
            ...$this->sharedFilterRules(),
            'filter.has-product-prices'       => 'boolean',
            'filter.with-product-prices'      => 'array',
            'filter.with-product-prices.*'    => 'string',
            'filter.without-product-prices'   => 'array',
            'filter.without-product-prices.*' => 'string',
            'filter.has-products'             => 'boolean',
            'filter.with-products'            => 'array',
            'filter.with-products.*'          => 'string',
            'filter.without-products'         => 'array',
            'filter.without-products.*'       => 'string',
            'include'                         => [
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

        if (AttributeApiIntegration::isAvailable()) {
            $rules['filter.has-attribute-values'] = 'boolean';
            $rules['filter.with-attribute-values'] = 'array';
            $rules['filter.with-attribute-values.*'] = 'string';
            $rules['filter.without-attribute-values'] = 'array';
            $rules['filter.without-attribute-values.*'] = 'string';
        }

        return $rules;
    }

    /**
     * @return array<string, mixed>
     */
    private function sharedFilterRules(): array
    {
        return [
            'filter.id'                   => 'array',
            'filter.id.*'                 => 'integer',
            'filter.exclude'              => 'array',
            'filter.exclude.*'            => 'integer',
            'filter.name'                 => 'string',
            'filter.slug'                 => 'string',
            'filter.status'               => 'boolean',
            'filter.has-multimedia'       => 'boolean',
            'filter.with-multimedia'      => 'array',
            'filter.with-multimedia.*'    => 'string',
            'filter.without-multimedia'   => 'array',
            'filter.without-multimedia.*' => 'string',
            'filter.with-trashed'         => 'boolean',
            'filter.only-trashed'         => 'boolean',
        ];
    }
}
