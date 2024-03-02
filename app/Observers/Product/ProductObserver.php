<?php

declare(strict_types=1);

namespace App\Observers\Product;

use App\Models\Product\Product;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Cache;

final class ProductObserver implements ShouldQueue
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

    public function created(Product $product): void {}

    public function deleted(Product $product): void
    {
        $product->productPrices()->delete();

        $product->orderProducts()->delete();

        Cache::forget('product_row_count');
    }

    public function forceDeleted(Product $product): void {}

    public function restored(Product $product): void {}

    public function retryUntil()
    {
        return now()->addMinutes(5);
    }

    public function saved(Product $product): void
    {
        Cache::forget('product_row_count');
    }

    public function updated(Product $product): void {}
}
