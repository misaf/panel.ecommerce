<?php

declare(strict_types=1);

namespace App\JsonApi\V1\FaqCategories;

use LaravelJsonApi\Laravel\Http\Requests\ResourceQuery;
use LaravelJsonApi\Validation\Rule as JsonApiRule;

final class FaqCategoryQuery extends ResourceQuery
{
    /**
     * Get the validation rules that apply to the request query parameters.
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
                JsonApiRule::filter()->forget('id'),
            ],
            'include' => [
                'nullable',
                'string',
                JsonApiRule::includePaths(),
            ],
            'page'      => JsonApiRule::notSupported(),
            'sort'      => JsonApiRule::notSupported(),
            'withCount' => [
                'nullable',
                'string',
                JsonApiRule::countable(),
            ],
        ];
    }
}
