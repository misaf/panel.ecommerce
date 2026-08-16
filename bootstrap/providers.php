<?php

declare(strict_types=1);

return [
    App\Providers\AppServiceProvider::class,
    App\Providers\FilamentDefaultsServiceProvider::class,
    Misaf\VendraReseller\Providers\ResellerPanelServiceProvider::class,
    App\Providers\Filament\AdminPanelServiceProvider::class,
    Misaf\VendraConsole\Providers\ConsolePanelServiceProvider::class,
    App\Providers\HorizonServiceProvider::class,
    App\Providers\PulseServiceProvider::class,
];
