<?php

declare(strict_types=1);

namespace App\Settings\SettingsRepositories;

/**
 * Platform-wide settings: one row per property, no tenant, readable and
 * writable from every panel including the ones that run outside tenancy.
 */
final class GlobalSettingsRepository extends ScopedSettingsRepository
{
    protected function tenantId(): ?int
    {
        return null;
    }
}
