<?php

declare(strict_types=1);

namespace Misaf\Newsletter\Jobs;

use Illuminate\Bus\Batch;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Config;

use Misaf\Newsletter\Actions\NewsletterSendHistory\UpdateSendHistoryAction;
use Misaf\Newsletter\Models\NewsletterSendHistory;
use Throwable;

final class SendNewsletterBatchJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public int $timeout;
    public int $tries;
    public int $chunkSize;
    public string $queueName;

    public function __construct(
        public NewsletterSendHistory $newsletterSendHistory,
    ) {
        $this->timeout = Config::integer('newsletter.queue.timeout', 300);
        $this->tries = Config::integer('newsletter.queue.tries', 3);
        $this->chunkSize = Config::integer('newsletter.batch_chunk_size', 100);
        $this->queueName = Config::string('newsletter.queue.name', 'marketing-email');

        $this->onQueue($this->queueName);
    }

    public function handle(): void
    {
        $newsletter = $this->newsletterSendHistory->newsletter;
        $batch = null;
        $jobIndex = 0;

        $newsletter->newsletterSubscribedUsers()
            ->chunkById($this->chunkSize, function (Collection $subscribers) use (&$batch, &$jobIndex, $newsletter): void {
                $jobs = $this->createJobsForSubscribers($subscribers, $jobIndex);
                $jobIndex += $subscribers->count();

                if (null === $batch) {
                    $batch = $this->createBatch($jobs, $newsletter);
                } else {
                    $batch->add($jobs);
                }
            });
    }

    private function createJobsForSubscribers(Collection $subscribers, int $startIndex): array
    {
        $jobs = [];

        foreach ($subscribers as $index => $subscriber) {
            $jobs[] = (new SendNewsletterEmailJob($this->newsletterSendHistory, $subscriber))
                ->delay(Carbon::now()->addSeconds(($startIndex + $index) * 5))
                ->onQueue($this->queueName);
        }

        return $jobs;
    }

    private function createBatch(array $jobs, $newsletter): Batch
    {
        return Bus::batch($jobs)
            ->name("Newsletter {$newsletter->id} - {$newsletter->slug}")
            ->onQueue($this->queueName)
            ->finally(function (Batch $batch): void {
                app(UpdateSendHistoryAction::class)->execute($this->newsletterSendHistory, $batch->toArray());
            })
            ->allowFailures()
            ->dispatch();
    }

    public function failed(?Throwable $exception): void
    {
        logger()->error('Newsletter batch job failed', [
            'newsletter_id'   => $this->newsletterSendHistory->newsletter->id,
            'newsletter_name' => $this->newsletterSendHistory->newsletter->name,
            'error'           => $exception?->getMessage() ?? 'Unknown error',
        ]);
    }
}
