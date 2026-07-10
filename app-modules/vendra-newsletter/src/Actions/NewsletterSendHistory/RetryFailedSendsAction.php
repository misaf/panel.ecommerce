<?php

declare(strict_types=1);

namespace Misaf\VendraNewsletter\Actions\NewsletterSendHistory;

use InvalidArgumentException;
use Misaf\VendraNewsletter\Models\NewsletterSendHistory;

final class RetryFailedSendsAction
{
    public function execute(NewsletterSendHistory $newsletterSendHistory): int
    {
        $failedSubscriberIds = $newsletterSendHistory->newsletterSendHistorySubscribers()
            ->failed()
            ->pluck('newsletter_subscriber_id')
            ->all();

        if (empty($failedSubscriberIds)) {
            throw new InvalidArgumentException('No failed subscribers found.');
        }

        // (new SendAction)

        return count($failedSubscriberIds);
    }
}
