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

    //public $queue = 'listeners';

    public bool $afterCommit = true;

    public $maxExceptions = 5;

    public $tries = 5;

    public function backoff(): array
    {
        return [1, 5, 10, 30];
    }

    public function created(BlogPostCategory $blogPostCategory): void {}

    public function deleted(BlogPostCategory $blogPostCategory): void
    {
        $blogPostCategory->blogPosts()->delete();

        Cache::forget('blog_post_row_count');
    }

    public function forceDeleted(BlogPostCategory $blogPostCategory): void {}

    public function restored(BlogPostCategory $blogPostCategory): void {}

    public function retryUntil()
    {
        return now()->addMinutes(5);
    }

    public function updated(BlogPostCategory $blogPostCategory): void {}
}
