<?php

declare(strict_types=1);

namespace Misaf\VendraCurrency\Database\Seeders;

use Misaf\VendraCurrency\CurrencyPlugin;
use Misaf\VendraCurrency\Enums\CurrencyCategoryPolicyEnum;
use Misaf\VendraCurrency\Enums\CurrencyPolicyEnum;
use Misaf\VendraSupport\Database\Seeders\PermissionPolicySeeder as BasePermissionPolicySeeder;

final class PermissionPolicySeeder extends BasePermissionPolicySeeder
{
    protected const string MODULE_NAME = CurrencyPlugin::ID;

    /**
     * @return list<string>
     */
    protected function policies(): array
    {
        return [
            ...array_column(CurrencyCategoryPolicyEnum::cases(), 'value'),
            ...array_column(CurrencyPolicyEnum::cases(), 'value'),
        ];
    }
}
