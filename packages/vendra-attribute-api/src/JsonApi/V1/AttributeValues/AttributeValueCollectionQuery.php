<?php

declare(strict_types=1);

namespace Misaf\VendraAttributeApi\JsonApi\V1\AttributeValues;

use LaravelJsonApi\Laravel\Http\Requests\ResourceQuery;
use LaravelJsonApi\Validation\Rule as JsonApiRule;

final class AttributeValueCollectionQuery extends ResourceQuery
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
            'filter.id'        => 'array',
            'filter.id.*'      => 'integer',
            'filter.exclude'   => 'array',
            'filter.exclude.*' => 'integer',
            'include'          => [
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
