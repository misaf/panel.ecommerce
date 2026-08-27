<?php

declare(strict_types=1);

namespace Misaf\VendraDelivery\Database\Seeders;

use Misaf\VendraDelivery\DeliveryPlugin;
use Misaf\VendraDelivery\Enums\DeliveryPolicyEnum;
use Misaf\VendraDelivery\Enums\DeliverySlotPolicyEnum;
use Misaf\VendraDelivery\Enums\DeliveryZonePolicyEnum;
use Misaf\VendraSupport\Tenancy\Database\Seeders\PermissionPolicySeeder as BasePermissionPolicySeeder;

final class PermissionPolicySeeder extends BasePermissionPolicySeeder
{
    protected const string MODULE_NAME = DeliveryPlugin::ID;

    /**
     * @return list<string>
     */
    protected function policies(): array
    {
        return [
            ...array_column(DeliveryZonePolicyEnum::cases(), 'value'),
            ...array_column(DeliverySlotPolicyEnum::cases(), 'value'),
            ...array_column(DeliveryPolicyEnum::cases(), 'value'),
        ];
    }
}
