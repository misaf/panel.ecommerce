<?php

declare(strict_types=1);

namespace App\JsonApi\V1\Multimedia;

use LaravelJsonApi\Laravel\Http\Requests\ResourceQuery;
use LaravelJsonApi\Validation\Rule as JsonApiRule;

final class MultimediaCollectionQuery extends ResourceQuery
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
            'filter.id'               => 'array',
            'filter.id.*'             => 'integer',
            'filter.exclude'          => 'array',
            'filter.exclude.*'        => 'integer',
            'filter.uuid'             => 'string',
            'filter.collection-name'  => 'string',
            'filter.name'             => 'string',
            'filter.file-name'        => 'string',
            'filter.mime-type'        => 'string',
            'filter.disk'             => 'string',
            'filter.conversions-disk' => 'string',
            'filter.size'             => 'integer',
            'filter.gt-size'          => 'integer',
            'filter.gte-size'         => 'integer',
            'filter.lt-size'          => 'integer',
            'filter.lte-size'         => 'integer',
            'include'                 => [
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
