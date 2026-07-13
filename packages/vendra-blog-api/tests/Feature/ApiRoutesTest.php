<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

it('registers blog api read routes', function (): void {
    expect(Route::has('vendra-blog.blog-posts.index'))->toBeTrue()
        ->and(Route::has('vendra-blog.blog-post-categories.index'))->toBeTrue()
        ->and(route('vendra-blog.blog-posts.index', [], false))->toBe('/v1/blog-posts')
        ->and(route('vendra-blog.blog-post-categories.index', [], false))->toBe('/v1/blog-post-categories');
});
