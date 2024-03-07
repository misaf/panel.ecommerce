<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Blog;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\Blog\BlogPostCategoryResource;
use App\Models\Blog\BlogPost;
use App\Models\Blog\BlogPostCategory;
use Spatie\QueryBuilder\AllowedInclude;
use Spatie\QueryBuilder\QueryBuilder;

final class BlogPostCategoryController extends Controller
{
    public function index()
    {
        $query = QueryBuilder::for(BlogPostCategory::class)
            ->allowedIncludes([
                AllowedInclude::relationship('blog_post_category', 'blogPostCategory'),
                'media'
            ])
            ->allowedFilters(['name', 'slug', 'status'])
            ->allowedSorts('position')
            ->defaultSort('-position');

        $paginatedPosts = $query->jsonPaginate()->appends(request()->all());

        return BlogPostCategoryResource::collection($paginatedPosts);
    }

    public function show(string $id): void
    {
        // return BlogPostCategoryResource::collection(BlogPost::find($id));
    }
}
