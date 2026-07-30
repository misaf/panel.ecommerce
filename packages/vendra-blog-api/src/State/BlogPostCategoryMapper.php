<?php

declare(strict_types=1);

namespace Misaf\VendraBlogApi\State;

use Illuminate\Database\Eloquent\Model;
use Misaf\VendraApi\ApiResource\ResourceReference;
use Misaf\VendraApi\State\Concerns\NormalizesResourceValues;
use Misaf\VendraApi\State\ResourceMapper;
use Misaf\VendraBlog\Models\BlogPost;
use Misaf\VendraBlog\Models\BlogPostCategory;
use Misaf\VendraBlogApi\ApiResource\BlogPostCategoryResource;
use Misaf\VendraMultimediaApi\ApiResource\MultimediaResource;
use Misaf\VendraMultimediaApi\State\MultimediaResourceFactory;
use Misaf\VendraMultimediaApi\State\PublicMultimedia;
use UnexpectedValueException;

final class BlogPostCategoryMapper implements ResourceMapper
{
    use NormalizesResourceValues;

    public function map(Model $model): BlogPostCategoryResource
    {
        if ( ! $model instanceof BlogPostCategory) {
            throw new UnexpectedValueException('Expected a blog-post category model.');
        }

        return new BlogPostCategoryResource(
            id: $model->id,
            title: $this->normalizeTranslations($model->getTranslations('name')),
            slugs: $this->normalizeTranslations($model->getTranslations('slug')),
            description: $this->normalizeTranslations($model->getTranslations('description')),
            position: $model->position,
            active: $model->active,
            blogPosts: $model->blogPosts
                ->map(function (BlogPost $post): ResourceReference {
                    $name = $post->getTranslation('name', app()->getLocale());

                    return new ResourceReference(
                        $post->id,
                        'BlogPost',
                        is_string($name) ? $name : null,
                    );
                })
                ->all(),
            multimedia: $model->relationLoaded('multimedia')
                ? $model->multimedia
                    ->filter(fn(Model $media): bool => PublicMultimedia::isPublic($media))
                    ->map(fn(Model $media): MultimediaResource => MultimediaResourceFactory::make($media))
                    ->values()
                    ->all()
                : [],
            createdAt: $model->created_at->toAtomString(),
            updatedAt: $model->updated_at->toAtomString(),
        );
    }
}
