<?php

declare(strict_types=1);

namespace App\JsonApi\V1\BlogPosts;

use LaravelJsonApi\Laravel\Http\Requests\ResourceRequest;

final class BlogPostRequest extends ResourceRequest
{
    /**
     * Get the validation rules for the resource.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            // @TODO
        ];
    }
}
