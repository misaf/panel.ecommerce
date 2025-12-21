<?php

declare(strict_types=1);

namespace Misaf\Newsletter\Listeners;

use Closure;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Misaf\EmailWebhooks\DataTransferObjects\EmailEventDto;
use Misaf\EmailWebhooks\Events\EmailBouncedEvent;
use Misaf\EmailWebhooks\Events\EmailComplainedEvent;
use Misaf\EmailWebhooks\Events\EmailSentEvent;
use Misaf\EmailWebhooks\Services\EmailWebhookService;
use Misaf\Newsletter\Models\NewsletterSubscriber;
use Misaf\Tenant\Scopes\TenantScope;
use Throwable;

final class EmailEventListener implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * @var string
     */
    private const TAG_HARD_BOUNCE = 'Hard Bounced';

    /**
     * @var string
     */
    private const TAG_SOFT_BOUNCE = 'Soft Bounced';

    /**
     * @var string
     */
    private const TAG_COMPLAINED = 'Complained';

    /**
     * @param EmailSentEvent $event
     * @return void
     */
    public function handleEmailSent(EmailSentEvent $event): void
    {
        $this->processEmails(
            emails: $event->eventData->to,
            callback: fn(string $email) => $this->sentEmail($email),
            eventName: 'EmailSent',
        );
    }

    /**
     * @param EmailBouncedEvent $event
     * @return void
     */
    public function handleEmailBounced(EmailBouncedEvent $event): void
    {
        $this->processEmails(
            emails: $event->eventData->to,
            callback: fn(string $email) => $this->bouncedEmail($email, $event->eventData),
            eventName: 'EmailBounced',
        );
    }

    /**
     * @param EmailComplainedEvent $event
     * @return void
     */
    public function handleEmailComplained(EmailComplainedEvent $event): void
    {
        $this->processEmails(
            emails: $event->eventData->to,
            callback: fn(string $email) => $this->complainedEmail($email, $event->eventData),
            eventName: 'EmailComplained',
        );
    }

    /**
     * @param list<string> $emails
     * @param Closure $callback
     * @param string $eventName
     * @return void
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

    /**
     * @param string $email
     * @return void
     */
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

    /**
     * @param string $email
     * @param EmailEventDto $eventData
     * @return void
     */
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

    /**
     * @param string $email
     * @param EmailEventDto $eventData
     * @return void
     */
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

    /**
     * @param string $email
     * @param string $tag
     * @return void
     */
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
