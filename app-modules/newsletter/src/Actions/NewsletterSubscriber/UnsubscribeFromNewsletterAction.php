<?php

declare(strict_types=1);

namespace Misaf\Newsletter\Actions\NewsletterSubscriber;

use Misaf\Newsletter\Models\Newsletter;
use Misaf\Newsletter\Models\NewsletterSubscriber;
use Misaf\Newsletter\Services\NewsletterSubscriberService;

final class UnsubscribeFromNewsletterAction
{
    public function __construct(
        private readonly NewsletterSubscriberService $subscriberService,
    ) {}

    /**
     * Unsubscribe a subscriber from a specific newsletter
     *
     * @param NewsletterSubscriber $subscriber
     * @param Newsletter $newsletter
     * @return bool
     */
    public function execute(NewsletterSubscriber $subscriber, Newsletter $newsletter): bool
    {
        $detached = $subscriber->newsletters()->detach($newsletter->id);

        if ($detached > 0) {
            $this->subscriberService->clearCache();
            return true;
        }

        return false;
    }
}
