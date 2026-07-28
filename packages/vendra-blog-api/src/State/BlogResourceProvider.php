<?php

declare(strict_types=1);

namespace Misaf\VendraBlogApi\State;

use ApiPlatform\Metadata\Operation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Misaf\VendraApi\ApiResource\ResourceReference;
use Misaf\VendraApi\State\EloquentResourceProvider;
use Misaf\VendraBlog\Models\BlogPost;
use Misaf\VendraBlog\Models\BlogPostCategory;
use Misaf\VendraBlogApi\ApiResource\BlogPostCategoryResource;
use Misaf\VendraBlogApi\ApiResource\BlogPostResource;

/**
 * @extends EloquentResourceProvider<Model, BlogPostResource|BlogPostCategoryResource>
 */
final class BlogResourceProvider extends EloquentResourceProvider
{
    protected function query(Operation $operation): Builder
    {
        if (BlogPostCategoryResource::class === $operation->getClass()) {
            return BlogPostCategory::query()
                ->with('blogPosts:id,blog_post_category_id,name')
                ->where('active', true);
        }

        return BlogPost::query()
            ->with(['blogPostCategory:id,name', 'multimedia:id,model_id,name'])
            ->where('active', true);
    }

    protected function toResource(Model $model, Operation $operation): BlogPostResource|BlogPostCategoryResource
    {
        if ($model instanceof BlogPostCategory) {
            return new BlogPostCategoryResource(
                id: $model->id,
                title: $model->getTranslations('name'),
                slugs: $model->getTranslations('slug'),
                blogPosts: $model->blogPosts
                    ->map(fn(BlogPost $post): ResourceReference => new ResourceReference($post->id, 'BlogPost', $post->getTranslation('name', app()->getLocale())))
                    ->all(),
            );
        }

        /** @var BlogPost $model */
        return new BlogPostResource(
            id: $model->id,
            title: $model->getTranslations('name'),
            content: $model->getTranslations('description'),
            slugs: $model->getTranslations('slug'),
            active: $model->active,
            section: new ResourceReference($model->blogPostCategory->id, 'BlogPostCategory', $model->blogPostCategory->getTranslation('name', app()->getLocale())),
            multimedia: $model->multimedia
                ->map(fn(Model $media): ResourceReference => new ResourceReference($media->getKey(), 'Multimedia', $media->getAttribute('name')))
                ->all(),
            publishedAt: $model->created_at->toAtomString(),
        );
    }
}
