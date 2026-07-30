<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Gate;
use Laravel\Horizon\Horizon;
use Laravel\Horizon\HorizonApplicationServiceProvider;

final class HorizonServiceProvider extends HorizonApplicationServiceProvider
{
    public function boot(): void
    {
        parent::boot();

        if ($notifyMail = Config::get('horizon.notify_mail')) {
            Horizon::routeMailNotificationsTo($notifyMail);
        }
    }

    protected function gate(): void
    {
        Gate::define('viewHorizon', function (?Authenticatable $user): bool {
            if ($this->app->isLocal()) {
                return true;
            }

            return Auth::guard('console')->check();
        });
    }
}
