<?php

declare(strict_types=1);

namespace App\Settings\SettingsRepositories;

use Misaf\VendraTenant\Models\Tenant;
use Spatie\LaravelSettings\SettingsRepositories\DatabaseSettingsRepository as SpatieDatabaseSettingsRepository;

final class DatabaseSettingsRepository extends SpatieDatabaseSettingsRepository
{
    /**
     * @param  array<string, mixed>  $properties
     */
    public function updatePropertiesPayload(string $group, array $properties): void
    {
        $tenantId = Tenant::current()?->id;

        $propertiesInBatch = collect($properties)->map(function (mixed $payload, string $name) use ($group, $tenantId) {
            return [
                'tenant_id' => $tenantId,
                'group'     => $group,
                'name'      => $name,
                'payload'   => $this->encode($payload),
            ];
        })->values()->toArray();

        $this->getBuilder()
            ->where('group', $group)
            ->upsert($propertiesInBatch, ['tenant_id', 'group', 'name'], ['payload']);
    }
}
