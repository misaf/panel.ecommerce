<?php

declare(strict_types=1);

namespace Misaf\VendraNewsletter\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Config;

final class SendNewsletterBatchJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public int $tries;

    public int $timeout;

    /**
     * @param  list<int>  $subscriberIds
     */
    public function __construct(
        public readonly int $newsletterId,
        public readonly array $subscriberIds,
    ) {
        $this->onConnection(Config::get('vendra-newsletter.queue.connection'));
        $this->onQueue(Config::string('vendra-newsletter.queue.name', 'default'));
        $this->tries = Config::integer('vendra-newsletter.queue.tries', 3);
        $this->timeout = Config::integer('vendra-newsletter.queue.timeout', 300);
    }

    public function handle(): void
    {
        foreach ($this->subscriberIds as $subscriberId) {
            SendNewsletterEmailJob::dispatch($this->newsletterId, $subscriberId);
        }
    }
}
