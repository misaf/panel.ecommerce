<?php

declare(strict_types=1);

namespace Misaf\VendraBlogApi\JsonApi\V1\BlogPostCategories;

use LaravelJsonApi\Laravel\Http\Requests\ResourceQuery;
use LaravelJsonApi\Validation\Rule as JsonApiRule;

final class BlogPostCategoryCollectionQuery extends ResourceQuery
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
            'filter.has-blog-posts'       => 'boolean',
            'filter.with-blog-posts'      => 'array',
            'filter.with-blog-posts.*'    => 'string',
            'filter.without-blog-posts'   => 'array',
            'filter.without-blog-posts.*' => 'string',
            'include'                     => [
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
     * @return array<string, string>
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
            'filter.active'               => 'boolean',
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
