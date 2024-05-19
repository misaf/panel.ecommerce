<?php

declare(strict_types=1);

namespace App\JsonApi\V1\Faqs;

use LaravelJsonApi\Laravel\Http\Requests\ResourceQuery;
use LaravelJsonApi\Validation\Rule as JsonApiRule;

final class FaqCollectionQuery extends ResourceQuery
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
            'filter.id'                     => 'array',
            'filter.id.*'                   => 'integer',
            'filter.exclude'                => 'array',
            'filter.exclude.*'              => 'integer',
            'filter.faq-category'           => 'integer',
            'filter.slug'                   => 'string',
            'filter.status'                 => 'boolean',
            'filter.with-faq-category'      => 'array',
            'filter.with-faq-category.*'    => 'string',
            'filter.without-faq-category'   => 'array',
            'filter.without-faq-category.*' => 'string',
            'filter.in-faq-category.*'      => 'integer',
            'filter.not-in-faq-category.*'  => 'integer',
            'filter.has-multimedia'         => 'boolean',
            'filter.with-multimedia'        => 'array',
            'filter.with-multimedia.*'      => 'string',
            'filter.without-multimedia'     => 'array',
            'filter.without-multimedia.*'   => 'string',
            'filter.with-trashed'           => 'boolean',
            'filter.only-trashed'           => 'boolean',
            'include'                       => [
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
