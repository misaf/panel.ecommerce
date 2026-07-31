<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Enums\StorefrontDeploymentStatus;
use App\Jobs\ProvisionStorefrontJob;
use App\Models\StorefrontDeployment;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;

final class RetryFailedStorefrontDeploymentsCommand extends Command
{
    protected $signature = 'storefront:retry-failed
        {--sync : Retry each failed storefront in the current process}';

    protected $description = 'Retry storefront deployments currently marked as failed';

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
            ->where('status', StorefrontDeploymentStatus::Failed->value)
            ->orderBy('id')
            ->chunkById(100, function ($deployments) use (&$count): void {
                foreach ($deployments as $deployment) {
                    $job = new ProvisionStorefrontJob($deployment->id);

                    if ($this->option('sync')) {
                        dispatch_sync($job);
                    } else {
                        dispatch($job);
                    }

                    $count++;
                }
            });

        $mode = $this->option('sync') ? 'retried' : 'queued for retry';
        $this->info(sprintf('%d failed storefront deployment(s) %s.', $count, $mode));

        return self::SUCCESS;
    }
}
