<?php

declare(strict_types=1);

namespace Misaf\VendraCurrency\Observers;

use Misaf\VendraCurrency\Models\Currency;

final class CurrencyObserver
{
    public function creating(Currency $currency): void
    {
        if ( ! $currency->status) {
            $currency->is_default = false;

            return;
        }

        if ( ! Currency::query()->enabled()->exists()) {
            $currency->is_default = true;
        }
    }

    public function saving(Currency $currency): void
    {
        if ( ! $currency->status) {
            $currency->is_default = false;

            return;
        }

        if ($currency->is_default) {
            Currency::query()
                ->where('is_default', true)
                ->whereKeyNot($currency->getKey())
                ->update(['is_default' => false]);

            return;
        }

        if ($currency->exists && true === $currency->getOriginal('is_default')) {
            $hasAnotherDefault = Currency::query()
                ->enabled()
                ->where('is_default', true)
                ->whereKeyNot($currency->getKey())
                ->exists();

            if ( ! $hasAnotherDefault) {
                $currency->is_default = true;
            }
        }
    }

    public function saved(Currency $currency): void
    {
        if ($currency->wasChanged(['status', 'is_default'])) {
            $this->ensureEnabledDefault();
        }
    }

    public function deleted(Currency $currency): void
    {
        if ( ! $currency->is_default) {
            return;
        }

        $this->ensureEnabledDefault();
    }

    private function ensureEnabledDefault(): void
    {
        if (Currency::query()->enabled()->where('is_default', true)->exists()) {
            return;
        }

        Currency::query()
            ->enabled()
            ->ordered()
            ->first()
            ?->update(['is_default' => true]);
    }
}
