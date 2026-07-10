<?php

declare(strict_types=1);

namespace Misaf\VendraGeo\Database\Seeders;

use Misaf\VendraGeo\Enums\CityPolicyEnum;
use Misaf\VendraGeo\Enums\CountryPolicyEnum;
use Misaf\VendraGeo\Enums\StatePolicyEnum;
use Misaf\VendraGeo\GeoPlugin;
use Misaf\VendraSupport\Database\Seeders\PermissionPolicySeeder as BasePermissionPolicySeeder;

final class PermissionPolicySeeder extends BasePermissionPolicySeeder
{
    protected const string MODULE_NAME = GeoPlugin::ID;

    /**
     * @return list<string>
     */
    protected function policies(): array
    {
        return [
            ...array_column(CountryPolicyEnum::cases(), 'value'),
            ...array_column(StatePolicyEnum::cases(), 'value'),
            ...array_column(CityPolicyEnum::cases(), 'value'),
        ];
    }
}
