<?php

declare(strict_types=1);

namespace Misaf\VendraCustomPage\Observers;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Misaf\VendraCustomPage\Models\CustomPageCategory;

final class CustomPageCategoryObserver implements ShouldQueue
{
    use InteractsWithQueue;

    public bool $afterCommit = true;

    public function deleted(CustomPageCategory $customPageCategory): void
    {
        $customPageCategory->customPages()->delete();
    }
}
