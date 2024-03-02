<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Blog;

use App\Http\Controllers\Controller;
use App\Http\Resources\Blog\BlogPostResource;
use App\Models\Blog\BlogPost;
use Illuminate\Http\Request;

final class BlogPostController extends Controller
{
    public function destroy(string $id)
    {
        return BlogPostResource::collection(BlogPost::destroy($id));
    }

    public function index()
    {
        return BlogPostResource::collection(BlogPost::with('blogPostCategory')->get());
    }

    public function show(string $id)
    {
        return BlogPostResource::collection(BlogPost::find($id));
    }

    public function store(Request $request)
    {
        return new BlogPostResource(BlogPost::create($request));
    }

    public function update(Request $request, string $id)
    {
        return BlogPostResource::collection(BlogPost::find($id)->update($request));
    }
}
