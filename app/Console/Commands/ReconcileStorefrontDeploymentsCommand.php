<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Jobs\ProvisionStorefrontJob;
use App\Models\StorefrontDeployment;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;

final class ReconcileStorefrontDeploymentsCommand extends Command
{
    protected $signature = 'storefront:reconcile
        {--sync : Provision each storefront in the current process}';

    protected $description = 'Reconcile every database-backed storefront deployment with the provisioner';

    public function handle(): int
    {
        if (
            '' === Config::string('services.storefront.provisioner_url')
            || '' === Config::string('services.storefront.provisioner_token')
        ) {
            $this->error('Configure STOREFRONT_PROVISIONER_URL and STOREFRONT_PROVISIONER_TOKEN first.');

            return self::FAILURE;
        }

        $count = 0;

        StorefrontDeployment::query()
            ->select('id')
            ->orderBy('id')
            ->chunkById(100, function ($deployments) use (&$count): void {
                foreach ($deployments as $deployment) {
                    $job = new ProvisionStorefrontJob($deployment->id, force: true);

                    if ($this->option('sync')) {
                        dispatch_sync($job);
                    } else {
                        dispatch($job);
                    }

                    $count++;
                }
            });

        $mode = $this->option('sync') ? 'reconciled' : 'queued for reconciliation';
        $this->info(sprintf('%d storefront deployment(s) %s.', $count, $mode));

        return self::SUCCESS;
    }
}
