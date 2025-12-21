<?php

declare(strict_types=1);

namespace Misaf\Newsletter\Observers;

use Illuminate\Contracts\Events\ShouldHandleEventsAfterCommit;
use Misaf\Newsletter\Models\NewsletterSendHistory;

/**
 * @method void saved(NewsletterSendHistory $sendHistory)
 */
final class NewsletterSendHistoryObserver implements ShouldHandleEventsAfterCommit
{
    /**
     * Handle the NewsletterSendHistory "saved" event.
     * No action needed since newsletter status is now boolean for enabled/disabled
     *
     * @param NewsletterSendHistory $sendHistory
     * @return void
     */
    public function saved(NewsletterSendHistory $sendHistory): void
    {
        // Newsletter status is now boolean (enabled/disabled)
        // Send status is tracked through send histories
        // No action needed here
    }
};
