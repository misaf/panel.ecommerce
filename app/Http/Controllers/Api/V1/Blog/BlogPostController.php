<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Blog;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\Blog\BlogPostResource;
use App\Models\Blog\BlogPost;
use Spatie\QueryBuilder\AllowedInclude;
use Spatie\QueryBuilder\QueryBuilder;

final class BlogPostController extends Controller
{
    public function index()
    {
        $query = QueryBuilder::for(BlogPost::class)
            ->allowedIncludes([
                AllowedInclude::relationship('blog_post_category.media', 'blogPostCategory'),
                'media'
            ])
            ->with('blogPostCategory')
            ->allowedFilters(['name', 'slug', 'status'])
            ->allowedSorts('position')
            ->defaultSort('-position');

        $paginatedPosts = $query->jsonPaginate()
            ->appends(request()->all());

        return BlogPostResource::collection($paginatedPosts);
    }

    public function show(string $id)
    {
        return BlogPostResource::collection(BlogPost::find($id));
    }
}
