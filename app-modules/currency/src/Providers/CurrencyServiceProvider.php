<?php

declare(strict_types=1);

namespace Misaf\Currency\Providers;

use Illuminate\Support\ServiceProvider;

final class CurrencyServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadTranslationsFrom(__DIR__ . '/../../lang', 'currency');
    }
}
