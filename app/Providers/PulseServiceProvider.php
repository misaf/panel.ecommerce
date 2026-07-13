<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

final class PulseServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Gate::define('viewPulse', function (?Authenticatable $user): bool {
            if ($this->app->isLocal()) {
                return true;
            }

            return $user?->hasRole(Config::string('vendra-permission.super_admin_role')) ?? false;
        });
    }
}
