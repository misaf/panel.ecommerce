<?php

declare(strict_types=1);

namespace App\Observers\Faq;

use App\Models\Faq\FaqCategory;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

final class FaqCategoryObserver implements ShouldQueue
{
    use InteractsWithQueue;

    public bool $afterCommit = true;

    public function created(FaqCategory $faqCategory): void {}

    public function deleted(FaqCategory $faqCategory): void
    {
        $faqCategory->faqs()->delete();
    }

    public function forceDeleted(FaqCategory $faqCategory): void {}

    public function restored(FaqCategory $faqCategory): void {}

    public function updated(FaqCategory $faqCategory): void {}
}
