<?php

declare(strict_types=1);

namespace Misaf\VendraAttribute\Observers;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Misaf\VendraAttribute\Models\AttributeValue;

final class AttributeValueObserver implements ShouldQueue
{
    use InteractsWithQueue;

    public bool $afterCommit = true;

    public function deleted(AttributeValue $attributeValue): void
    {
        $attributeValue->selections()->delete();
    }
}
