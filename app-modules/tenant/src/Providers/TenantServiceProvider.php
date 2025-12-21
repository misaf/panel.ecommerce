<?php

declare(strict_types=1);

namespace Misaf\Tenant\Providers;

use Illuminate\Support\ServiceProvider;

final class TenantServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/multitenancy.php', 'multitenancy');
    }
}
