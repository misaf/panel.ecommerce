<?php

declare(strict_types=1);

namespace Misaf\Newsletter\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Contracts\Console\PromptsForMissingInput;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Misaf\Newsletter\Actions\Newsletter\SendAction;
use Misaf\Newsletter\Models\Newsletter;
use Misaf\Newsletter\Services\NewsletterService;
use Spatie\Multitenancy\Commands\Concerns\TenantAware;

final class SendScheduledNewslettersCommand extends Command implements PromptsForMissingInput
{
    use TenantAware;

    protected $signature = 'newsletter:send-scheduled
                            {--dry-run : Show what would be sent without actually sending}
                            {--tenant=*}';

    protected $description = 'Send scheduled newsletters when their scheduled time arrives';

    /**
     * Send scheduled newsletters when their scheduled time arrives
     *
     * @return int
     */
    public function handle(): int
    {
        $isDryRun = $this->option('dry-run');

        if (app()->isDownForMaintenance() && ! $isDryRun) {
            $this->warn('Application is in maintenance mode. Command aborted.');
            return self::FAILURE;
        }

        $this->info('Checking for scheduled newsletters...');

        if ($isDryRun) {
            $this->warn('DRY RUN MODE - No emails will be queued');
        }

        try {
            $scheduledNewsletters = $this->getScheduledNewsletters();

            if ($scheduledNewsletters->isEmpty()) {
                $this->info('No scheduled newsletters to send.');
                return self::SUCCESS;
            }

            $this->info("Found {$scheduledNewsletters->count()} newsletter(s) to send.");

            foreach ($scheduledNewsletters as $newsletter) {
                if ( ! $this->validateNewsletterStatus($newsletter)) {
                    $this->warn("Skipping newsletter {$newsletter->name} - not ready to be sent.");
                    continue;
                }

                $this->processScheduledNewsletter($newsletter, $isDryRun);
            }

            return self::SUCCESS;
        } catch (Exception $e) {
            $this->error('newsletter:send-scheduled failed', [
                'command' => 'newsletter:send-scheduled',
                'error'   => $e->getMessage(),
                'trace'   => $e->getTraceAsString()
            ]);

            return self::FAILURE;
        }
    }

    /**
     * Get newsletters that are scheduled and ready to be sent
     *
     * @return Collection<int, Newsletter>
     */
    private function getScheduledNewsletters(): Collection
    {
        $newsletterService = app(NewsletterService::class);

        return Newsletter::query()
            ->whereNotNull('scheduled_at')
            ->where('scheduled_at', '<=', Carbon::now())
            ->get()
            ->filter(fn(Newsletter $newsletter) => $newsletterService->isReady($newsletter));
    }

    /**
     * Process a single scheduled newsletter
     *
     * @param Newsletter $newsletter
     * @param bool $isDryRun
     * @return void
     */
    private function processScheduledNewsletter(Newsletter $newsletter, bool $isDryRun): void
    {
        $this->info("Processing scheduled newsletter: {$newsletter->name}");

        try {
            $sendAction = app(SendAction::class);
            $stats = $sendAction->execute($newsletter, $isDryRun);

            if ( ! $isDryRun) {
                $this->info("Newsletter batch job dispatched successfully! Queued: {$stats['queued']} emails");
            } else {
                $this->info("Would queue {$stats['queued']} emails");
            }
        } catch (Exception $e) {
            logger()->error('newsletter:send-scheduled failed', [
                'command'         => 'newsletter:send',
                'newsletter_id'   => $newsletter->id,
                'newsletter_name' => $newsletter->name,
                'error'           => $e->getMessage(),
                'trace'           => $e->getTraceAsString()
            ]);

            $this->error("Failed to queue newsletter {$newsletter->name}: {$e->getMessage()}");
        }
    }

    /**
     * Validate newsletter status before sending
     *
     * @param Newsletter $newsletter
     * @return bool
     */
    private function validateNewsletterStatus(Newsletter $newsletter): bool
    {
        $newsletterService = app(NewsletterService::class);

        if ( ! $newsletterService->isReady($newsletter)) {
            $this->error("Newsletter is not ready to be sent.");
            $this->info("Newsletter must have posts with READY status to be sent.");
            return false;
        }

        return true;
    }
}
