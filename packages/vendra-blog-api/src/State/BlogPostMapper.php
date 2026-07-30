<?php

declare(strict_types=1);

namespace Misaf\VendraBlogApi\State;

use Illuminate\Database\Eloquent\Model;
use Misaf\VendraApi\ApiResource\ResourceReference;
use Misaf\VendraApi\State\Concerns\NormalizesResourceValues;
use Misaf\VendraApi\State\ResourceMapper;
use Misaf\VendraBlog\Models\BlogPost;
use Misaf\VendraBlog\Models\BlogPostCategory;
use Misaf\VendraBlogApi\ApiResource\BlogPostResource;
use Misaf\VendraMultimediaApi\ApiResource\MultimediaResource;
use Misaf\VendraMultimediaApi\State\MultimediaResourceFactory;
use Misaf\VendraMultimediaApi\State\PublicMultimedia;
use UnexpectedValueException;

final class BlogPostMapper implements ResourceMapper
{
    use NormalizesResourceValues;

    public function map(Model $model): BlogPostResource
    {
        if ( ! $model instanceof BlogPost) {
            throw new UnexpectedValueException('Expected a blog post model.');
        }

        $category = $model->blogPostCategory;

        if ( ! $category instanceof BlogPostCategory) {
            throw new UnexpectedValueException('A blog post must belong to a category.');
        }

        $categoryName = $category->getTranslation('name', app()->getLocale());

        return new BlogPostResource(
            id: $model->id,
            title: $this->normalizeTranslations($model->getTranslations('name')),
            content: $this->normalizeTranslations($model->getTranslations('description')),
            slugs: $this->normalizeTranslations($model->getTranslations('slug')),
            active: $model->active,
            position: $model->position,
            blogPostCategory: new ResourceReference(
                $category->id,
                'BlogPostCategory',
                is_string($categoryName) ? $categoryName : null,
            ),
            multimedia: $model->multimedia
                ->filter(fn(Model $media): bool => PublicMultimedia::isPublic($media))
                ->map(fn(Model $media): MultimediaResource => MultimediaResourceFactory::make($media))
                ->values()
                ->all(),
            publishedAt: $model->created_at->toAtomString(),
            createdAt: $model->created_at->toAtomString(),
            updatedAt: $model->updated_at->toAtomString(),
        );
    }
}
