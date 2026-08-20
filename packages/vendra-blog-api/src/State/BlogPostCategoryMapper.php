<?php

declare(strict_types=1);

namespace Misaf\VendraBlogApi\State;

use Illuminate\Database\Eloquent\Model;
use Misaf\VendraApi\State\Concerns\MapsResourceReferences;
use Misaf\VendraApi\State\Concerns\NormalizesResourceValues;
use Misaf\VendraApi\State\ResourceMapper;
use Misaf\VendraBlog\Models\BlogPostCategory;
use Misaf\VendraBlogApi\ApiResource\BlogPostCategoryResource;
use Misaf\VendraMultimediaApi\State\Concerns\MapsPublicMultimedia;

final class BlogPostCategoryMapper implements ResourceMapper
{
    use MapsPublicMultimedia;
    use MapsResourceReferences;
    use NormalizesResourceValues;

    public function map(Model $model): BlogPostCategoryResource
    {
        $this->expectModel($model, BlogPostCategory::class, 'Expected a blog-post category model.');

        return new BlogPostCategoryResource(
            id: $model->id,
            name: $this->normalizeTranslations($model->getTranslations('name')),
            slug: $this->normalizeTranslations($model->getTranslations('slug')),
            description: $this->normalizeTranslations($model->getTranslations('description')),
            position: $model->position,
            active: $model->active,
            blogPosts: $this->referencesTo($model->blogPosts, 'BlogPost'),
            multimedia: $this->publicMultimedia($model, onlyWhenLoaded: true),
            createdAt: $model->created_at->toAtomString(),
            updatedAt: $model->updated_at->toAtomString(),
        );
    }
}
