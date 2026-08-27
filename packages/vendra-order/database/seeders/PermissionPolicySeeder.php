<?php

declare(strict_types=1);

namespace Misaf\VendraOrder\Database\Seeders;

use Misaf\VendraOrder\Enums\OrderLinePolicyEnum;
use Misaf\VendraOrder\Enums\OrderPolicyEnum;
use Misaf\VendraOrder\OrderPlugin;
use Misaf\VendraSupport\Tenancy\Database\Seeders\PermissionPolicySeeder as BasePermissionPolicySeeder;

final class PermissionPolicySeeder extends BasePermissionPolicySeeder
{
    protected const string MODULE_NAME = OrderPlugin::ID;

    /**
     * @return list<string>
     */
    protected function policies(): array
    {
        return [
            ...array_column(OrderPolicyEnum::cases(), 'value'),
            ...array_column(OrderLinePolicyEnum::cases(), 'value'),
        ];
    }
}
