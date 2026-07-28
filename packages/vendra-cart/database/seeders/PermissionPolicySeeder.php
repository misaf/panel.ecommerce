<?php

declare(strict_types=1);

namespace Misaf\VendraCart\Database\Seeders;

use Misaf\VendraCart\CartPlugin;
use Misaf\VendraCart\Enums\CartItemPolicyEnum;
use Misaf\VendraCart\Enums\CartPolicyEnum;
use Misaf\VendraSupport\Tenancy\Database\Seeders\PermissionPolicySeeder as BasePermissionPolicySeeder;

final class PermissionPolicySeeder extends BasePermissionPolicySeeder
{
    protected const string MODULE_NAME = CartPlugin::ID;

    /**
     * @return list<string>
     */
    protected function policies(): array
    {
        return [
            ...array_column(CartPolicyEnum::cases(), 'value'),
            ...array_column(CartItemPolicyEnum::cases(), 'value'),
        ];
    }
}
