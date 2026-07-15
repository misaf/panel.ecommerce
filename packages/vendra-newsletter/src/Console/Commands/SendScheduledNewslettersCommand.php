<?php

declare(strict_types=1);

namespace Misaf\VendraNewsletter\Console\Commands;

use Illuminate\Console\Command;
use Misaf\VendraNewsletter\Actions\SendNewsletter;
use Misaf\VendraNewsletter\Models\Newsletter;
use Misaf\VendraSupport\Contracts\TenantResolver;

final class SendScheduledNewslettersCommand extends Command
{
    protected $signature = 'vendra-newsletter:send-scheduled';

    protected $description = 'Dispatch newsletters whose scheduled send time has passed';

    /**
     * Dispatch due newsletters for every tenant. The support layer runs the
     * closure once per tenant (or once globally when no tenant provider is
     * installed), so subscriber scoping stays correct without this module
     * knowing anything about the concrete tenant.
     */
    public function handle(SendNewsletter $sendNewsletter, TenantResolver $tenants): int
    {
        $tenants->eachTenant(function () use ($sendNewsletter): void {
            Newsletter::query()
                ->due()
                ->orderBy('scheduled_at')
                ->get()
                ->each(function (Newsletter $newsletter) use ($sendNewsletter): void {
                    $recipients = $sendNewsletter->execute($newsletter);

                    $this->components->info(sprintf(
                        'Queued newsletter #%d for %d subscriber(s).',
                        $newsletter->id,
                        $recipients,
                    ));
                });
        });

        return self::SUCCESS;
    }
}
