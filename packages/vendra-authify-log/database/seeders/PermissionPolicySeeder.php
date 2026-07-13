<?php

declare(strict_types=1);

namespace Misaf\VendraAuthifyLog\Database\Seeders;

use Misaf\VendraAuthifyLog\AuthifyLogPlugin;
use Misaf\VendraAuthifyLog\Enums\AuthifyLogPolicyEnum;
use Misaf\VendraSupport\Database\Seeders\PermissionPolicySeeder as BasePermissionPolicySeeder;

final class PermissionPolicySeeder extends BasePermissionPolicySeeder
{
    protected const string MODULE_NAME = AuthifyLogPlugin::ID;

    /**
     * @return list<string>
     */
    protected function policies(): array
    {
        return array_column(AuthifyLogPolicyEnum::cases(), 'value');
    }
}
