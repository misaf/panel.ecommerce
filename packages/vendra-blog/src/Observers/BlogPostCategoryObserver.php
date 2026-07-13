<?php

declare(strict_types=1);

namespace Misaf\VendraBlog\Observers;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Misaf\VendraBlog\Models\BlogPostCategory;

final class BlogPostCategoryObserver implements ShouldQueue
{
    use InteractsWithQueue;

    public bool $afterCommit = true;

    public function deleted(BlogPostCategory $blogPostCategory): void
    {
        $blogPostCategory->blogPosts()->delete();
    }
}
