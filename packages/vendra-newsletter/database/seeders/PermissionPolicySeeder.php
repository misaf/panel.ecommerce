<?php

declare(strict_types=1);

namespace Misaf\VendraNewsletter\Database\Seeders;

use Misaf\VendraNewsletter\Enums\NewsletterPolicyEnum;
use Misaf\VendraNewsletter\Enums\NewsletterSubscriberPolicyEnum;
use Misaf\VendraNewsletter\NewsletterPlugin;
use Misaf\VendraSupport\Database\Seeders\PermissionPolicySeeder as BasePermissionPolicySeeder;

final class PermissionPolicySeeder extends BasePermissionPolicySeeder
{
    protected const string MODULE_NAME = NewsletterPlugin::ID;

    /**
     * @return list<string>
     */
    protected function policies(): array
    {
        return [
            ...array_column(NewsletterPolicyEnum::cases(), 'value'),
            ...array_column(NewsletterSubscriberPolicyEnum::cases(), 'value'),
        ];
    }
}
