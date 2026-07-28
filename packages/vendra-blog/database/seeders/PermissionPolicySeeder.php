<?php

declare(strict_types=1);

namespace Misaf\VendraBlog\Database\Seeders;

use Misaf\VendraBlog\BlogPlugin;
use Misaf\VendraBlog\Enums\BlogPostCategoryPolicyEnum;
use Misaf\VendraBlog\Enums\BlogPostPolicyEnum;
use Misaf\VendraSupport\Tenancy\Database\Seeders\PermissionPolicySeeder as BasePermissionPolicySeeder;

final class PermissionPolicySeeder extends BasePermissionPolicySeeder
{
    protected const string MODULE_NAME = BlogPlugin::ID;

    /**
     * @return list<string>
     */
    protected function policies(): array
    {
        return [
            ...array_column(BlogPostCategoryPolicyEnum::cases(), 'value'),
            ...array_column(BlogPostPolicyEnum::cases(), 'value'),
        ];
    }
}
