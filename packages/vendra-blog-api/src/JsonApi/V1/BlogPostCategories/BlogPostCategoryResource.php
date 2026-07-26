<?php

declare(strict_types=1);

namespace Misaf\VendraBlogApi\JsonApi\V1\BlogPostCategories;

use LaravelJsonApi\Core\Resources\JsonApiResource;
use Misaf\VendraBlog\Models\BlogPostCategory;

/**
 * @mixin BlogPostCategory
 */
final class BlogPostCategoryResource extends JsonApiResource
{
    /**
     * @return iterable<string, mixed>
     */
    public function attributes($request): iterable
    {
        return [
            'name'        => $this->name,
            'description' => $this->description,
            'slug'        => $this->slug,
            'position'    => $this->position,
            'active'      => $this->active,
            'created_at'  => $this->created_at,
            'updated_at'  => $this->updated_at,
        ];
    }

    /**
     * @return iterable<int, mixed>
     */
    public function relationships($request): iterable
    {
        return [
            $this->relation('blogPosts'),
            $this->relation('multimedia'),
        ];
    }
}
