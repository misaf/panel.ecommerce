<?php

declare(strict_types=1);

namespace Misaf\VendraNewsletter\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Misaf\VendraNewsletter\Mail\NewsletterMail;
use Misaf\VendraNewsletter\Models\Newsletter;
use Misaf\VendraNewsletter\Models\NewsletterSubscriber;

final class SendNewsletterEmailJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public int $tries;

    public int $timeout;

    public function __construct(
        public readonly int $newsletterId,
        public readonly int $subscriberId,
    ) {
        $this->onConnection(Config::get('vendra-newsletter.queue.connection'));
        $this->onQueue(Config::string('vendra-newsletter.queue.name', 'default'));
        $this->tries = Config::integer('vendra-newsletter.queue.tries', 3);
        $this->timeout = Config::integer('vendra-newsletter.queue.email_timeout', 30);
    }

    public function handle(): void
    {
        $newsletter = Newsletter::query()->find($this->newsletterId);
        $subscriber = NewsletterSubscriber::query()->find($this->subscriberId);

        if ( ! $newsletter instanceof Newsletter || ! $subscriber instanceof NewsletterSubscriber) {
            return;
        }

        if ( ! $subscriber->isSubscribed()) {
            return;
        }

        Mail::to($subscriber->email)->send(new NewsletterMail($newsletter, $subscriber));
    }
}
