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
use Misaf\VendraMultimediaApi\ApiResource\MultimediaResource;
use Misaf\VendraMultimediaApi\State\MultimediaResourceFactory;

/**
 * @extends EloquentResourceProvider<Model, BlogPostResource|BlogPostCategoryResource>
 */
final class BlogResourceProvider extends EloquentResourceProvider
{
    protected function query(Operation $operation): Builder
    {
        if (BlogPostCategoryResource::class === $operation->getClass()) {
            return BlogPostCategory::query()
                ->with([
                    'blogPosts:id,blog_post_category_id,name',
                    'multimedia',
                ])
                ->where('active', true);
        }

        $query = BlogPost::query()
            ->with([
                'blogPostCategory:id,name,slug,description,position,active,created_at,updated_at',
                'multimedia',
            ])
            ->where('active', true);

        return $query;
    }

    protected function toResource(Model $model, Operation $operation): BlogPostResource|BlogPostCategoryResource
    {
        if ($model instanceof BlogPostCategory) {
            return $this->toCategoryResource($model);
        }

        /** @var BlogPost $model */
        return new BlogPostResource(
            id: $model->id,
            title: $model->getTranslations('name'),
            content: $model->getTranslations('description'),
            slugs: $model->getTranslations('slug'),
            active: $model->active,
            position: $model->position,
            section: $this->toCategoryResource($model->blogPostCategory, includePosts: false),
            multimedia: $model->multimedia
                ->map(fn(Model $media): MultimediaResource => MultimediaResourceFactory::make($media))
                ->all(),
            publishedAt: $model->created_at->toAtomString(),
            createdAt: $model->created_at->toAtomString(),
            updatedAt: $model->updated_at->toAtomString(),
        );
    }

    private function toCategoryResource(BlogPostCategory $category, bool $includePosts = true): BlogPostCategoryResource
    {
        return new BlogPostCategoryResource(
            id: $category->id,
            title: $category->getTranslations('name'),
            slugs: $category->getTranslations('slug'),
            description: $category->getTranslations('description'),
            position: $category->position,
            active: $category->active,
            blogPosts: $includePosts
                ? $category->blogPosts
                    ->map(fn(BlogPost $post): ResourceReference => new ResourceReference($post->id, 'BlogPost', $post->getTranslation('name', app()->getLocale())))
                    ->all()
                : [],
            multimedia: $category->relationLoaded('multimedia')
                ? $category->multimedia
                    ->map(fn(Model $media): MultimediaResource => MultimediaResourceFactory::make($media))
                    ->all()
                : [],
            createdAt: $category->created_at->toAtomString(),
            updatedAt: $category->updated_at->toAtomString(),
        );
    }
}
