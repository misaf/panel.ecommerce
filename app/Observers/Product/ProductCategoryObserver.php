<?php

declare(strict_types=1);

namespace App\Observers\Product;

use App\Models\Product\ProductCategory;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

final class ProductCategoryObserver implements ShouldQueue
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

    public function created(ProductCategory $productCategory): void {}

    public function deleted(ProductCategory $productCategory): void
    {
        $productCategory->products()->each(function ($item): void {
            $item->productPrices()->delete();

            $item->orderProducts()->delete();

            $item->delete();
        });
    }

    public function forceDeleted(ProductCategory $productCategory): void {}

    public function restored(ProductCategory $productCategory): void {}

    public function retryUntil()
    {
        return now()->addMinutes(5);
    }

    public function updated(ProductCategory $productCategory): void {}
}
