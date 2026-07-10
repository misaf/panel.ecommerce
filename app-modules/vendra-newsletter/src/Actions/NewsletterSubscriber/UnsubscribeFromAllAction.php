<?php

declare(strict_types=1);

namespace Misaf\VendraNewsletter\Actions\NewsletterSubscriber;

use Misaf\VendraNewsletter\Models\NewsletterSubscriber;
use Misaf\VendraNewsletter\Services\NewsletterSubscriberService;

final class UnsubscribeFromAllAction
{
    public function __construct(
        private readonly NewsletterSubscriberService $subscriberService,
    ) {}

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
