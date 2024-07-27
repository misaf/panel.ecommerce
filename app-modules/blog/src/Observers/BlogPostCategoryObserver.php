<?php

declare(strict_types=1);

namespace Termehsoft\Blog\Observers;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Cache;
use Termehsoft\Blog\Models\BlogPostCategory;

final class BlogPostCategoryObserver implements ShouldQueue
{
    use InteractsWithQueue;

    public bool $afterCommit = true;

    /**
     * Handle the BlogPostCategory "deleted" event.
     *
     * @param BlogPostCategory $blogPostCategory
     * @return void
     */
    public function deleted(BlogPostCategory $blogPostCategory): void
    {
        $blogPostCategory->blogPosts()->delete();

        $this->clearCaches($blogPostCategory);
    }

    /**
     * Handle the BlogPostCategory "saved" event.
     *
     * @param BlogPost $blogPost
     * @return void
     */
    public function saved(BlogPostCategory $blogPostCategory): void
    {
        $this->clearCaches($blogPostCategory);
    }

    /**
     * Clear relevant caches.
     *
     * @param BlogPostCategory $blogPostCategory
     * @return void
     */
    private function clearCaches(BlogPostCategory $blogPostCategory): void
    {
        $this->forgetRowCountCache();
    }

    /**
     * Forget the blog post category row count cache.
     *
     * @return void
     */
    private function forgetRowCountCache(): void
    {
        Cache::forget('blog-post-category-row-count');
    }
}
