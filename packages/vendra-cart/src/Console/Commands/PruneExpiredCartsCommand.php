<?php

declare(strict_types=1);

namespace Misaf\VendraCart\Console\Commands;

use Illuminate\Console\Command;
use Misaf\VendraCart\Models\Cart;

final class PruneExpiredCartsCommand extends Command
{
    protected $signature = 'vendra-cart:prune-expired';

    protected $description = 'Delete carts whose expiry time has passed';

    public function handle(): int
    {
        $pruned = Cart::query()
            ->whereNotNull('expires_at')
            ->where('expires_at', '<=', now())
            ->delete();

        $this->info("Pruned {$pruned} expired cart(s).");

        return self::SUCCESS;
    }
}
