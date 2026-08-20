<?php

declare(strict_types=1);

namespace App\Settings\SettingsRepositories;

use Misaf\VendraStore\Models\Store;
use Misaf\VendraSupport\Tenancy\TenantSchema;
use Spatie\LaravelSettings\SettingsRepositories\DatabaseSettingsRepository as SpatieDatabaseSettingsRepository;

final class DatabaseSettingsRepository extends SpatieDatabaseSettingsRepository
{
    /**
     * @param  array<string, mixed>  $properties
     */
    public function updatePropertiesPayload(string $group, array $properties): void
    {
        $tenantId = Store::current()?->id;
        $tenantColumn = TenantSchema::column();

        $propertiesInBatch = collect($properties)->map(function (mixed $payload, string $name) use ($group, $tenantId, $tenantColumn) {
            return [
                $tenantColumn => $tenantId,
                'group'       => $group,
                'name'        => $name,
                'payload'     => $this->encode($payload),
            ];
        })->values()->toArray();

        $this->getBuilder()
            ->where('group', $group)
            ->upsert($propertiesInBatch, [$tenantColumn, 'group', 'name'], ['payload']);
    }
}
