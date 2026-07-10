<?php

declare(strict_types=1);

namespace Misaf\VendraCurrency\Filament\Clusters\Resources\Currencies\Actions;

use Misaf\VendraCurrency\Models\Currency;

final class SetDefaultCurrencyAction
{
    public function execute(Currency $currency): void
    {
        Currency::where('is_default', true)
            ->whereKeyNot($currency->id)
            ->update(['is_default' => false]);

        $currency->update([
            'is_default' => true,
        ]);
    }
}
