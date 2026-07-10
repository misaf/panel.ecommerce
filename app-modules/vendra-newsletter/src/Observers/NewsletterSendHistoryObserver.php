<?php

declare(strict_types=1);

namespace Misaf\VendraNewsletter\Observers;

use Illuminate\Contracts\Events\ShouldHandleEventsAfterCommit;
use Misaf\VendraNewsletter\Models\NewsletterSendHistory;

/**
 * @method void saved(NewsletterSendHistory $sendHistory)
 */
final class NewsletterSendHistoryObserver implements ShouldHandleEventsAfterCommit
{
    public function saved(NewsletterSendHistory $sendHistory): void
    {
        // Newsletter status is now boolean (enabled/disabled)
        // Send status is tracked through send histories
        // No action needed here
    }
}
