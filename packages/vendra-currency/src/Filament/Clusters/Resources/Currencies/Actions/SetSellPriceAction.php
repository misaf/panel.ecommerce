<?php

declare(strict_types=1);

namespace Misaf\VendraCurrency\Filament\Clusters\Resources\Currencies\Actions;

use Exception;
use Misaf\VendraCurrency\Models\Currency;

final class SetSellPriceAction
{
    public function execute(Currency $currency, int $price): bool
    {
        if ($price < $currency->buy_price) {
            throw new Exception('Sell price must be greater than buy price.');
        }

        $currency->update([
            'sell_price' => $price,
        ]);

        return $currency->wasChanged('sell_price');
    }
}
