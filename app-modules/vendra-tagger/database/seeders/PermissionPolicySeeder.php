<?php

declare(strict_types=1);

namespace Misaf\VendraTagger\Database\Seeders;

use Misaf\VendraSupport\Database\Seeders\PermissionPolicySeeder as BasePermissionPolicySeeder;
use Misaf\VendraTagger\Enums\TaggerPolicyEnum;
use Misaf\VendraTagger\TaggerPlugin;

final class PermissionPolicySeeder extends BasePermissionPolicySeeder
{
    protected const string MODULE_NAME = TaggerPlugin::ID;

    /**
     * @return list<string>
     */
    protected function policies(): array
    {
        return array_column(TaggerPolicyEnum::cases(), 'value');
    }
}
