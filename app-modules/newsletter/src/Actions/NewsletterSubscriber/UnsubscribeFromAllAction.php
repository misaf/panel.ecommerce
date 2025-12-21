<?php

declare(strict_types=1);

namespace Misaf\Newsletter\Actions\NewsletterSubscriber;

use Misaf\Newsletter\Models\NewsletterSubscriber;
use Misaf\Newsletter\Services\NewsletterSubscriberService;

final class UnsubscribeFromAllAction
{
    public function __construct(
        private readonly NewsletterSubscriberService $subscriberService,
    ) {}

    /**
     * Unsubscribe a subscriber from all newsletters
     *
     * @param NewsletterSubscriber $subscriber
     * @return bool
     */
    public function execute(NewsletterSubscriber $subscriber): bool
    {
        $detached = $subscriber->newsletters()->detach();

        if ($detached > 0) {
            $this->subscriberService->clearCache();
            return true;
        }

        return false;
    }
}
