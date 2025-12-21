<?php

declare(strict_types=1);

namespace Misaf\Newsletter\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Contracts\Console\PromptsForMissingInput;
use Misaf\Newsletter\Actions\NewsletterSubscriber\SyncWithUsersAction;
use Spatie\Multitenancy\Commands\Concerns\TenantAware;

final class SyncSubscribersWithUsersCommand extends Command implements PromptsForMissingInput
{
    use TenantAware;

    protected $signature = 'newsletter:subscribers:sync-users
                            {--chunk=100 : Number of records to process per batch}
                            {--dry-run : Show what would be updated without making changes}
                            {--tenant=*}';

    protected $description = 'Sync newsletter subscribers with user accounts by matching email addresses';

    /**
     * Sync newsletter subscribers with user accounts by matching email addresses
     *
     * @param SyncWithUsersAction $action
     * @return int
     */
    public function handle(SyncWithUsersAction $action): int
    {
        if (app()->isDownForMaintenance()) {
            $this->warn('Application is in maintenance mode. Command aborted.');
            return self::FAILURE;
        }

        $chunk = (int) $this->option('chunk');
        $isDryRun = $this->option('dry-run');

        if ($chunk < 1 || $chunk > 1000) {
            $this->error('Chunk size must be between 1 and 1000.');
            return self::FAILURE;
        }

        $this->info('Starting newsletter subscriber sync...');

        if ($isDryRun) {
            $this->warn('DRY RUN MODE - No changes will be made');
        }

        try {
            $updatedCount = $action->execute($chunk, $isDryRun);

            if ($isDryRun) {
                $this->info("Would sync {$updatedCount} subscriber(s) to users.");
            } else {
                $this->info("Successfully synced {$updatedCount} subscriber(s) to users.");
            }

            return self::SUCCESS;
        } catch (Exception $e) {
            logger()->error('newsletter:sync-user-ids failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            $this->error("Sync failed: {$e->getMessage()}");
            return self::FAILURE;
        }
    }
}
