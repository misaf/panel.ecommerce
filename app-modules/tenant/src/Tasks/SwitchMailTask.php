<?php

declare(strict_types=1);

namespace Misaf\Tenant\Tasks;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Spatie\Multitenancy\Contracts\IsTenant;
use Spatie\Multitenancy\Tasks\SwitchTenantTask;

/**
 * Task to switch mail configuration based on the current tenant.
 *
 * This task handles switching mail settings including SMTP configuration
 * and from address/name based on the tenant slug.
 */
final class SwitchMailTask implements SwitchTenantTask
{
    private string $originalFromAddress;

    private string $originalFromName;

    public function __construct()
    {
        $this->originalFromAddress = Config::string('mail.from.address');
        $this->originalFromName = Config::string('mail.from.name');
    }

    /**
     * Reset mail configuration to original settings.
     */
    public function forgetCurrent(): void
    {
        Mail::alwaysFrom($this->originalFromAddress, $this->originalFromName);
    }

    /**
     * Configure mail settings for the given tenant.
     *
     * @param IsTenant $tenant
     */
    public function makeCurrent(IsTenant $tenant): void
    {
        $tenantSlug = $tenant->slug;

        // Configure SMTP settings for the tenant using shared configuration
        $this->configureSmtpForTenant($tenantSlug);

        // Set the default mail driver
        Mail::setDefaultDriver($tenantSlug);

        // Set from address and name for the tenant
        $mailSettings = $this->getMailSettingsForTenant($tenantSlug);
        if ($mailSettings) {
            Mail::alwaysFrom($mailSettings['address'], $mailSettings['name']);
        }
    }

    /**
     * Configure SMTP settings for a specific tenant using shared configuration.
     *
     * @param string $tenantSlug
     */
    private function configureSmtpForTenant(string $tenantSlug): void
    {
        // Use the default SMTP configuration for all tenants
        $smtpConfig = Config::array('mail.mailers.smtp');

        Config::set("mail.mailers.{$tenantSlug}", $smtpConfig);
    }

    /**
     * Get mail settings (from address and name) for a specific tenant.
     *
     * @param string $slug
     * @return array|null
     */
    private function getMailSettingsForTenant(string $slug): ?array
    {
        return [
            'address' => 'support@example.com',
            'name'    => 'Example [Support]',
        ];
    }
}
