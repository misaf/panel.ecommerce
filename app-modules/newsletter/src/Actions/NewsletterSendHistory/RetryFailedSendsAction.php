<?php

declare(strict_types=1);

namespace Misaf\Newsletter\Actions\NewsletterSendHistory;

use InvalidArgumentException;
use Misaf\Newsletter\Models\NewsletterSendHistory;

final class RetryFailedSendsAction
{
    /**
     * Retry sending newsletter to subscribers who previously failed.
     *
     * @param NewsletterSendHistory $newsletterSendHistory
     * @return int
     * @throws InvalidArgumentException
     */
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
