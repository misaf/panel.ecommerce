<?php

declare(strict_types=1);

namespace Misaf\VendraSubscription\Console\Commands;

use Illuminate\Console\Command;
use Misaf\VendraSubscription\Actions\EnforceSubscriptionsAction;
use Misaf\VendraSupport\Context\RequestJobContext;

final class EnforceSubscriptionsCommand extends Command
{
    protected $signature = 'vendra-subscription:enforce-subscriptions';

    protected $description = 'Expire lapsed subscriptions and suspend properties past their grace period';

    public function handle(EnforceSubscriptionsAction $enforceSubscriptionsAction): int
    {
        (new RequestJobContext(
            traceId: RequestJobContext::resolveTraceId(),
            operation: 'subscription_enforcement',
        ))->scope(function () use ($enforceSubscriptionsAction): void {
            $result = $enforceSubscriptionsAction->execute();

            $this->info('Subscriptions enforced.');
            $this->table(['Metric', 'Count'], [
                ['Expired subscriptions', $result['expired']],
                ['Expiry reminders sent', $result['reminded']],
                ['Subscribers past grace', $result['grace_expired']],
            ]);
        });

        return self::SUCCESS;
    }
}
