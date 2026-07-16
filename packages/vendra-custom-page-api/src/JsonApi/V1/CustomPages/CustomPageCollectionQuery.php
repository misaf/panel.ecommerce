<?php

declare(strict_types=1);

namespace Misaf\VendraCustomPageApi\JsonApi\V1\CustomPages;

use LaravelJsonApi\Laravel\Http\Requests\ResourceQuery;
use LaravelJsonApi\Validation\Rule as JsonApiRule;

final class CustomPageCollectionQuery extends ResourceQuery
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
            ...$this->sharedFilterRules(),
            'filter.with-custom-page-category'      => 'array',
            'filter.with-custom-page-category.*'    => 'string',
            'filter.without-custom-page-category'   => 'array',
            'filter.without-custom-page-category.*' => 'string',
            'include'                               => [
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
            'filter.status'               => JsonApiRule::boolean()->asString(),
            'filter.has-multimedia'       => JsonApiRule::boolean()->asString(),
            'filter.with-multimedia'      => 'array',
            'filter.with-multimedia.*'    => 'string',
            'filter.without-multimedia'   => 'array',
            'filter.without-multimedia.*' => 'string',
            'filter.with-trashed'         => JsonApiRule::boolean()->asString(),
            'filter.only-trashed'         => JsonApiRule::boolean()->asString(),
        ];
    }
}
