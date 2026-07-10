<?php

declare(strict_types=1);

namespace Misaf\VendraCustomPage\Database\Seeders;

use Misaf\VendraCustomPage\CustomPagePlugin;
use Misaf\VendraCustomPage\Enums\CustomPageCategoryPolicyEnum;
use Misaf\VendraCustomPage\Enums\CustomPagePolicyEnum;
use Misaf\VendraSupport\Database\Seeders\PermissionPolicySeeder as BasePermissionPolicySeeder;

final class PermissionPolicySeeder extends BasePermissionPolicySeeder
{
    protected const string MODULE_NAME = CustomPagePlugin::ID;

    /**
     * @return list<string>
     */
    protected function policies(): array
    {
        return [
            ...array_column(CustomPageCategoryPolicyEnum::cases(), 'value'),
            ...array_column(CustomPagePolicyEnum::cases(), 'value'),
        ];
    }
}
