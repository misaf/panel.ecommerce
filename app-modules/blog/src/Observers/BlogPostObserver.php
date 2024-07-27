<?php

declare(strict_types=1);

namespace Termehsoft\Blog\Observers;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Cache;
use Termehsoft\Blog\Models\BlogPost;

final class BlogPostObserver implements ShouldQueue
{
    use InteractsWithQueue;

    public bool $afterCommit = true;

    /**
     * Handle the BlogPost "deleted" event.
     *
     * @param BlogPost $blogPost
     * @return void
     */
    public function deleted(BlogPost $blogPost): void
    {
        $this->clearCaches($blogPost);
    }

    /**
     * Handle the BlogPost "saved" event.
     *
     * @param BlogPost $blogPost
     * @return void
     */
    public function saved(BlogPost $blogPost): void
    {
        $this->clearCaches($blogPost);
    }

    /**
     * Clear relevant caches.
     *
     * @param BlogPost $blogPost
     * @return void
     */
    private function clearCaches(BlogPost $blogPost): void
    {
        $this->forgetRowCountCache();
        $this->forgetShowCache($blogPost);
    }

    /**
     * Forget the blog post row count cache.
     *
     * @return void
     */
    private function forgetRowCountCache(): void
    {
        Cache::forget('blog-post-row-count');
    }

    /**
     * Forget the blog post show cache.
     *
     * @param BlogPost $blogPost
     * @return void
     */
    private function forgetShowCache(BlogPost $blogPost): void
    {
        $slugs = $blogPost->getTranslations('slug');

        foreach ($slugs as $slug) {
            Cache::forget('show-blog-post-' . $slug);
        }
    }
}
