<?php

declare(strict_types=1);

namespace Misaf\Newsletter\Jobs;

use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Misaf\Newsletter\Mail\NewsletterMail;
use Misaf\Newsletter\Models\NewsletterSendHistory;
use Misaf\Newsletter\Models\NewsletterSubscriber;
use Throwable;

final class SendNewsletterEmailJob implements ShouldQueue
{
    use Batchable;
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public int $timeout;
    public int $tries;

    public function __construct(
        public NewsletterSendHistory $sendHistory,
        public NewsletterSubscriber $subscriber,
    ) {
        $this->timeout = Config::integer('newsletter.queue.email_timeout', 30);
        $this->tries = Config::integer('newsletter.queue.tries', 3);
        $this->onQueue(Config::string('newsletter.queue.name', 'marketing-email'));
    }

    public function handle(): void
    {
        if ($this->batch()?->cancelled()) {
            return;
        }

        Mail::to($this->subscriber->email)
            ->send(new NewsletterMail($this->sendHistory->newsletter, $this->subscriber, $this->sendHistory->newsletterPosts));
    }

    public function failed(?Throwable $exception): void
    {
        logger()->error('Newsletter email job permanently failed', [
            'newsletter_id' => $this->sendHistory->newsletter->id,
            'subscriber_id' => $this->subscriber->id,
            'email'         => $this->subscriber->email,
            'error'         => $exception?->getMessage(),
        ]);
    }
}
