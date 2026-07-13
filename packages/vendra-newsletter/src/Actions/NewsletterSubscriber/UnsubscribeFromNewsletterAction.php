<?php

declare(strict_types=1);

namespace Misaf\VendraNewsletter\Actions\NewsletterSubscriber;

use Misaf\VendraNewsletter\Models\Newsletter;
use Misaf\VendraNewsletter\Models\NewsletterSubscriber;
use Misaf\VendraNewsletter\Services\NewsletterSubscriberService;

final class UnsubscribeFromNewsletterAction
{
    public function __construct(
        private readonly NewsletterSubscriberService $subscriberService,
    ) {}

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
