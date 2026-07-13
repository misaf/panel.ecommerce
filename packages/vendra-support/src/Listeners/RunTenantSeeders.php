<?php

declare(strict_types=1);

namespace Misaf\VendraSupport\Listeners;

use Illuminate\Support\Facades\Artisan;
use Misaf\VendraSupport\Events\TenantProvisioned;
use Misaf\VendraSupport\Support\TenantSeeders;
use RuntimeException;

final class RunTenantSeeders
{
    public function __construct(private readonly TenantSeeders $seeders) {}

    public function handle(TenantProvisioned $event): void
    {
        if ( ! $event->shouldSeed) {
            return;
        }

        $tenant = $event->tenant->getKey();

        foreach ($this->seeders->ordered() as $command) {
            $exitCode = Artisan::call($command, [
                'tenant'  => $tenant,
                'seeders' => ['all'],
            ]);

            if (0 !== $exitCode) {
                throw new RuntimeException(sprintf(
                    'Seed command [%s] failed with exit code [%d].',
                    $command,
                    $exitCode,
                ));
            }
        }
    }
}
