<?php

declare(strict_types=1);

namespace Misaf\VendraLanguage\Database\Seeders;

use Misaf\VendraLanguage\Enums\LanguageLinePolicyEnum;
use Misaf\VendraLanguage\Enums\LanguagePolicyEnum;
use Misaf\VendraLanguage\LanguagePlugin;
use Misaf\VendraSupport\Tenancy\Database\Seeders\PermissionPolicySeeder as BasePermissionPolicySeeder;

final class PermissionPolicySeeder extends BasePermissionPolicySeeder
{
    protected const string MODULE_NAME = LanguagePlugin::ID;

    /**
     * @return list<string>
     */
    protected function policies(): array
    {
        return [
            ...array_column(LanguagePolicyEnum::cases(), 'value'),
            ...array_column(LanguageLinePolicyEnum::cases(), 'value'),
        ];
    }
}
