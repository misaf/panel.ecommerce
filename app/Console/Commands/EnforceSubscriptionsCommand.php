<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Misaf\VendraSubscription\Actions\EnforceSubscriptionsAction;

final class EnforceSubscriptionsCommand extends Command
{
    protected $signature = 'vendra-subscription:enforce-subscriptions';

    protected $description = 'Expire lapsed subscriptions and suspend properties past their grace period';

    public function __construct(private readonly EnforceSubscriptionsAction $enforceSubscriptionsAction)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $result = $this->enforceSubscriptionsAction->execute();

        $this->info('Subscriptions enforced.');
        $this->table(['Metric', 'Count'], [
            ['Expired subscriptions', $result['expired']],
            ['Expiry reminders sent', $result['reminded']],
            ['Subscribers past grace', $result['grace_expired']],
        ]);

        return self::SUCCESS;
    }
}
