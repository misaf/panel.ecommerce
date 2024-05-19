<?php

declare(strict_types=1);

namespace App\Support\SwitchTenantTasks;

use Spatie\Multitenancy\Models\Tenant;
use Spatie\Multitenancy\Tasks\SwitchTenantTask;

final class SwitchTenantConfigTask implements SwitchTenantTask
{
    public function forgetCurrent(): void
    {
        config()->set('app.name', 'a');
        config()->set('app.url', 'https://panel.houshang-flowers.test3');
        config()->set('app.asset_url', 'https://panel.houshang-flowers.test4');
    }

    public function makeCurrent(Tenant $tenant): void
    {
        config()->set('app.name', 'b');
        config()->set('app.url', 'https://panel.houshang-flowers.test1');
        config()->set('app.asset_url', 'https://panel.houshang-flowers.test2');
    }
}
