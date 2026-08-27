<?php

declare(strict_types=1);

namespace Misaf\VendraWishlist\Database\Seeders;

use Misaf\VendraSupport\Tenancy\Database\Seeders\PermissionPolicySeeder as BasePermissionPolicySeeder;
use Misaf\VendraWishlist\Enums\WishlistItemPolicyEnum;
use Misaf\VendraWishlist\Enums\WishlistPolicyEnum;
use Misaf\VendraWishlist\WishlistPlugin;

final class PermissionPolicySeeder extends BasePermissionPolicySeeder
{
    protected const string MODULE_NAME = WishlistPlugin::ID;

    /**
     * @return list<string>
     */
    protected function policies(): array
    {
        return [
            ...array_column(WishlistPolicyEnum::cases(), 'value'),
            ...array_column(WishlistItemPolicyEnum::cases(), 'value'),
        ];
    }
}
