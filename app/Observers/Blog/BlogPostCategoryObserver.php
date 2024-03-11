<?php

declare(strict_types=1);

namespace App\Observers\Blog;

use App\Models\Blog\BlogPostCategory;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Cache;

final class BlogPostCategoryObserver implements ShouldQueue
{
    use InteractsWithQueue;

    public bool $afterCommit = true;

    public function created(BlogPostCategory $blogPostCategory): void {}

    public function deleted(BlogPostCategory $blogPostCategory): void
    {
        $blogPostCategory->blogPosts()->delete();

        Cache::forget('blog_post_row_count');
    }

    public function forceDeleted(BlogPostCategory $blogPostCategory): void {}

    public function restored(BlogPostCategory $blogPostCategory): void {}

    public function updated(BlogPostCategory $blogPostCategory): void {}
}
