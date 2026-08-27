<?php

declare(strict_types=1);

namespace Misaf\VendraInquiry\Database\Seeders;

use Misaf\VendraInquiry\Enums\InquiryPolicyEnum;
use Misaf\VendraInquiry\InquiryPlugin;
use Misaf\VendraSupport\Tenancy\Database\Seeders\PermissionPolicySeeder as BasePermissionPolicySeeder;

final class PermissionPolicySeeder extends BasePermissionPolicySeeder
{
    protected const string MODULE_NAME = InquiryPlugin::ID;

    /**
     * @return list<string>
     */
    protected function policies(): array
    {
        return array_column(InquiryPolicyEnum::cases(), 'value');
    }
}
