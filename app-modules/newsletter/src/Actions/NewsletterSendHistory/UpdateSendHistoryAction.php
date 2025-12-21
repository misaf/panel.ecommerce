<?php

declare(strict_types=1);

namespace Misaf\Newsletter\Actions\NewsletterSendHistory;

use Illuminate\Support\Carbon;
use Misaf\Newsletter\Enums\NewsletterSendHistoryStatusEnum;
use Misaf\Newsletter\Models\NewsletterSendHistory;

final class UpdateSendHistoryAction
{
    /**
     * Update newsletter send history with batch completion results
     *
     * @param NewsletterSendHistory $sendHistory
     * @param array $batch
     * @return NewsletterSendHistory
     */
    public function execute(NewsletterSendHistory $sendHistory, array $batch): NewsletterSendHistory
    {
        $sendHistory->update([
            'status'       => $batch['failedJobs'] > 0 ? NewsletterSendHistoryStatusEnum::FAILED : NewsletterSendHistoryStatusEnum::SENT,
            'sent_count'   => $batch['totalJobs'] - $batch['failedJobs'],
            'failed_count' => $batch['failedJobs'],
            'completed_at' => Carbon::now(),
        ]);

        return $sendHistory->fresh();
    }
}
