<?php

declare(strict_types=1);

namespace Misaf\VendraNewsletter\Listeners;

use Closure;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Misaf\EmailWebhooks\DataTransferObjects\EmailEventDto;
use Misaf\EmailWebhooks\Events\EmailBouncedEvent;
use Misaf\EmailWebhooks\Events\EmailComplainedEvent;
use Misaf\EmailWebhooks\Events\EmailSentEvent;
use Misaf\EmailWebhooks\Services\EmailWebhookService;
use Misaf\VendraNewsletter\Models\NewsletterSubscriber;
use Misaf\VendraSupport\Scopes\TenantScope;
use Throwable;

final class EmailEventListener implements ShouldQueue
{
    use InteractsWithQueue;

    private const TAG_HARD_BOUNCE = 'Hard Bounced';

    private const TAG_SOFT_BOUNCE = 'Soft Bounced';

    private const TAG_COMPLAINED = 'Complained';

    public function handleEmailSent(EmailSentEvent $event): void
    {
        $this->processEmails(
            emails: $event->eventData->to,
            callback: fn(string $email) => $this->sentEmail($email),
            eventName: 'EmailSent',
        );
    }

    public function handleEmailBounced(EmailBouncedEvent $event): void
    {
        $this->processEmails(
            emails: $event->eventData->to,
            callback: fn(string $email) => $this->bouncedEmail($email, $event->eventData),
            eventName: 'EmailBounced',
        );
    }

    public function handleEmailComplained(EmailComplainedEvent $event): void
    {
        $this->processEmails(
            emails: $event->eventData->to,
            callback: fn(string $email) => $this->complainedEmail($email, $event->eventData),
            eventName: 'EmailComplained',
        );
    }

    /**
     * @param  list<string>  $emails
     */
    private function processEmails(array $emails, Closure $callback, string $eventName): void
    {
        if (empty($emails)) {
            logger()->warning("{$eventName} event received without valid email data.");

            return;
        }

        foreach ($emails as $email) {
            try {
                $callback($email);
            } catch (Throwable $e) {
                logger()->error("Failed to process {$eventName} for email", [
                    'email' => $email,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }

    private function sentEmail(string $email): void
    {
        $subscriberExists = NewsletterSubscriber::where('email', $email)->exists();
        if (true === $subscriberExists) {
            return;
        }

        NewsletterSubscriber::create([
            'email' => $email,
        ]);
    }

    private function bouncedEmail(string $email, EmailEventDto $eventData): void
    {
        $emailWebhookService = new EmailWebhookService($eventData);

        if ($emailWebhookService->isHardBounce()) {
            $this->markSubscriberWithTag($email, self::TAG_HARD_BOUNCE);

        } elseif ($emailWebhookService->isSoftBounce()) {
            $this->markSubscriberWithTag($email, self::TAG_SOFT_BOUNCE);

        } elseif ($emailWebhookService->isComplaint()) {
            $this->markSubscriberWithTag($email, self::TAG_COMPLAINED);

        } else {
            logger()->warning('Unknown bounce type, treating as hard bounce', [
                'email'       => $email,
                'bounce_type' => $eventData->bounce?->type,
            ]);
        }
    }

    private function complainedEmail(string $email, EmailEventDto $eventData): void
    {
        $emailWebhookService = new EmailWebhookService($eventData);

        if ($emailWebhookService->isComplaint()) {
            $this->markSubscriberWithTag($email, self::TAG_COMPLAINED);

        } else {
            logger()->warning('Unknown bounce type, treating as Complained', [
                'email'       => $email,
                'bounce_type' => $eventData->bounce?->type,
            ]);
        }
    }

    private function markSubscriberWithTag(string $email, string $tag): void
    {
        $subscriber = NewsletterSubscriber::withoutGlobalScope(TenantScope::class)->where('email', $email)->first();

        if (null === $subscriber) {
            logger()->warning("{$tag}: subscriber not found.", compact('email'));

            return;
        }

        $subscriber->attachTag($tag);
        logger()->info("Subscriber marked as {$tag}", compact('email'));
    }
}
