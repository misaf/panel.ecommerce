<?php

declare(strict_types=1);

namespace Misaf\VendraNewsletter\Actions;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Config;
use Misaf\VendraNewsletter\Enums\NewsletterStatusEnum;
use Misaf\VendraNewsletter\Jobs\SendNewsletterBatchJob;
use Misaf\VendraNewsletter\Models\Newsletter;
use Misaf\VendraNewsletter\Models\NewsletterSubscriber;

final class SendNewsletter
{
    /**
     * Fan the newsletter out to every subscribed recipient in tenant-scoped
     * chunks, then mark it as sent. Returns the number of recipients queued.
     */
    public function execute(Newsletter $newsletter): int
    {
        if (NewsletterStatusEnum::Sent === $newsletter->status) {
            return 0;
        }

        $chunkSize = Config::integer('vendra-newsletter.batch_chunk_size', 100);
        $queued = 0;

        NewsletterSubscriber::query()
            ->subscribed()
            ->select('id')
            ->chunkById($chunkSize, function (Collection $subscribers) use ($newsletter, &$queued): void {
                /** @var list<int> $ids */
                $ids = $subscribers->modelKeys();

                SendNewsletterBatchJob::dispatch($newsletter->getKey(), $ids);

                $queued += count($ids);
            });

        $newsletter->forceFill([
            'status'  => NewsletterStatusEnum::Sent,
            'sent_at' => now(),
        ])->save();

        return $queued;
    }
}
