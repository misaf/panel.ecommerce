<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\StorefrontDeployment;
use Illuminate\Console\Command;

final class StorefrontDeploymentStatusCommand extends Command
{
    protected $signature = 'storefront:status';

    protected $description = 'List storefront deployments from the database';

    public function handle(): int
    {
        $deployments = StorefrontDeployment::query()
            ->select(['slug', 'domain', 'status', 'provider_reference', 'image_digest'])
            ->orderBy('slug')
            ->get();

        if ($deployments->isEmpty()) {
            $this->info('No storefront deployments found.');

            return self::SUCCESS;
        }

        $this->table(
            ['Slug', 'Domain', 'Status', 'Provider reference', 'Image digest'],
            $deployments->map(fn(StorefrontDeployment $deployment): array => [
                $deployment->slug,
                $deployment->domain,
                $deployment->status->value,
                $deployment->provider_reference ?? '—',
                $deployment->image_digest ?? '—',
            ])->all(),
        );

        return self::SUCCESS;
    }
}
