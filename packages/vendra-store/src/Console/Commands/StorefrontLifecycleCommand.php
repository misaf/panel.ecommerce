<?php

declare(strict_types=1);

namespace Misaf\VendraStore\Console\Commands;

use Illuminate\Console\Command;
use Misaf\VendraStore\Actions\ControlStoreStorefrontAction;
use Misaf\VendraStore\Models\StorefrontDeployment;

/**
 * Operator access to a single storefront's lifecycle.
 *
 * Deliberately separate from `storefront:reconcile`: these commands act on one
 * storefront, change no configuration, and record intent — stopping a storefront
 * here means it stays stopped through the next reconciliation pass.
 */
final class StorefrontLifecycleCommand extends Command
{
    protected $signature = 'storefront:lifecycle
        {action : start, stop, restart, status, or logs}
        {slug : The storefront slug}
        {--lines=200 : Log lines to show for the logs action}';

    protected $description = 'Start, stop, restart, or inspect one property storefront';

    public function handle(ControlStoreStorefrontAction $control): int
    {
        $slug = (string) $this->argument('slug');
        $deployment = StorefrontDeployment::query()->where('slug', $slug)->first();

        if ( ! $deployment instanceof StorefrontDeployment) {
            $this->error("No storefront deployment named [{$slug}] exists.");

            return self::FAILURE;
        }

        return match ((string) $this->argument('action')) {
            'start'   => $this->perform(fn() => $control->start($deployment), "Storefront [{$slug}] started."),
            'stop'    => $this->perform(fn() => $control->stop($deployment), "Storefront [{$slug}] stopped."),
            'restart' => $this->perform(fn() => $control->restart($deployment), "Storefront [{$slug}] restarted."),
            'status'  => $this->reportStatus($control, $deployment),
            'logs'    => $this->reportLogs($control, $deployment),
            default   => $this->unknownAction(),
        };
    }

    /**
     * @param callable(): void $operation
     */
    private function perform(callable $operation, string $message): int
    {
        $operation();

        $this->info($message);

        return self::SUCCESS;
    }

    private function reportStatus(ControlStoreStorefrontAction $control, StorefrontDeployment $deployment): int
    {
        $observed = $control->observe($deployment);

        $this->table(['Field', 'Value'], [
            ['Slug', $deployment->slug],
            ['Domain', $deployment->domain],
            ['Recorded status', $deployment->status->value],
            ['Desired state', $deployment->desired_state->value],
            ['Runtime state', $observed->state->value],
            ['Container', $observed->containerName ?? $deployment->container_name ?? '—'],
            ['Running image', $observed->image ?? '—'],
            ['Image digest', $deployment->image_digest ?? '—'],
        ]);

        return self::SUCCESS;
    }

    private function reportLogs(ControlStoreStorefrontAction $control, StorefrontDeployment $deployment): int
    {
        $lines = (int) $this->option('lines');

        $this->line($control->logs($deployment, $lines > 0 ? $lines : 200));

        return self::SUCCESS;
    }

    private function unknownAction(): int
    {
        $this->error('Unknown action. Use start, stop, restart, status, or logs.');

        return self::FAILURE;
    }
}
