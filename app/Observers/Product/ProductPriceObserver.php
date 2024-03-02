<?php

declare(strict_types=1);

namespace App\Observers\Product;

use App\Models\Product\ProductPrice;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

final class ProductPriceObserver implements ShouldQueue
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

    public function created(ProductPrice $productPrice): void {}

    public function deleted(ProductPrice $productPrice): void {}

    public function forceDeleted(ProductPrice $productPrice): void {}

    public function restored(ProductPrice $productPrice): void {}

    public function retryUntil()
    {
        return now()->addMinutes(5);
    }

    public function updated(ProductPrice $productPrice): void {}
}
