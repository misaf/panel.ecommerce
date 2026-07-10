<?php

declare(strict_types=1);

namespace Misaf\VendraActivityLog\Database\Seeders;

use Misaf\VendraActivityLog\ActivityLogPlugin;
use Misaf\VendraActivityLog\Enums\ActivityLogPolicyEnum;
use Misaf\VendraSupport\Database\Seeders\PermissionPolicySeeder as BasePermissionPolicySeeder;

final class PermissionPolicySeeder extends BasePermissionPolicySeeder
{
    protected const string MODULE_NAME = ActivityLogPlugin::ID;

    /**
     * @return list<string>
     */
    protected function policies(): array
    {
        return array_column(ActivityLogPolicyEnum::cases(), 'value');
    }
}
