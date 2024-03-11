<?php

declare(strict_types=1);

namespace App\Observers\Blog;

use App\Models\Blog\BlogPost;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Cache;

final class BlogPostObserver implements ShouldQueue
{
    use InteractsWithQueue;

    public bool $afterCommit = true;

    public function created(BlogPost $blogPost): void
    {
        Cache::forget('blog_post_row_count');
    }

    public function deleted(BlogPost $blogPost): void
    {
        Cache::forget('blog_post_row_count');
    }

    public function forceDeleted(BlogPost $blogPost): void {}

    public function restored(BlogPost $blogPost): void {}

    public function updated(BlogPost $blogPost): void {}
}
