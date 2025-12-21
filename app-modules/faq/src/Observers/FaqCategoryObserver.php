<?php

declare(strict_types=1);

namespace Misaf\Faq\Observers;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Misaf\Faq\Models\FaqCategory;

final class FaqCategoryObserver implements ShouldQueue
{
    use InteractsWithQueue;

    public bool $afterCommit = true;

    public function deleted(FaqCategory $faqCategory): void
    {
        $faqCategory->faqs()->delete();
    }
}
