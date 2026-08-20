<?php

declare(strict_types=1);

namespace Misaf\VendraBlogApi\State;

use Illuminate\Database\Eloquent\Model;
use Misaf\VendraApi\State\Concerns\MapsResourceReferences;
use Misaf\VendraApi\State\Concerns\NormalizesResourceValues;
use Misaf\VendraApi\State\ResourceMapper;
use Misaf\VendraBlog\Models\BlogPost;
use Misaf\VendraBlog\Models\BlogPostCategory;
use Misaf\VendraBlogApi\ApiResource\BlogPostResource;
use Misaf\VendraMultimediaApi\State\Concerns\MapsPublicMultimedia;

final class BlogPostMapper implements ResourceMapper
{
    use MapsPublicMultimedia;
    use MapsResourceReferences;
    use NormalizesResourceValues;

    public function map(Model $model): BlogPostResource
    {
        $this->expectModel($model, BlogPost::class, 'Expected a blog post model.');
        $this->expectModel($category = $model->blogPostCategory, BlogPostCategory::class, 'A blog post must belong to a category.');

        return new BlogPostResource(
            id: $model->id,
            name: $this->normalizeTranslations($model->getTranslations('name')),
            description: $this->normalizeTranslations($model->getTranslations('description')),
            slug: $this->normalizeTranslations($model->getTranslations('slug')),
            active: $model->active,
            position: $model->position,
            blogPostCategory: $this->referenceTo($category, 'BlogPostCategory'),
            multimedia: $this->publicMultimedia($model),
            createdAt: $model->created_at->toAtomString(),
            updatedAt: $model->updated_at->toAtomString(),
        );
    }
}
