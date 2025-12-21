<?php

declare(strict_types=1);

namespace Misaf\Newsletter\Services;

use Exception;
use Illuminate\Support\Collection;
use Misaf\Newsletter\Actions\NewsletterSendHistory\CreateSendHistoryAction;
use Misaf\Newsletter\Jobs\SendNewsletterBatchJob;
use Misaf\Newsletter\Models\Newsletter;
use Misaf\Newsletter\Models\NewsletterPost;
use Misaf\Newsletter\Models\NewsletterSendHistory;
use Misaf\Newsletter\Models\NewsletterSendHistorySubscriber;

/**
 * Newsletter Send Service
 *
 * This service provides common functionality for newsletter sending operations.
 * Individual send operations are handled by specific actions:
 * - Newsletter\SendAction
 * - NewsletterPost\SendAction
 * - NewsletterSendHistory\SendAction
 * - NewsletterSendHistorySubscriber\SendAction
 */
final class NewsletterSendService
{
    public function __construct(
        private CreateSendHistoryAction $createSendHistoryAction
    ) {}

    public function validateNewsletter(Newsletter $newsletter): void
    {
        if ( ! $newsletter->isEnabled()) {
            throw new Exception('Newsletter is disabled. Newsletter Slug: ' . $newsletter->slug);
        }
    }

    public function validateNewsletterPosts(Collection $newsletterPosts): void
    {
        if ($newsletterPosts->isEmpty()) {
            throw new Exception('No ready newsletter posts found.');
        }
    }

    public function validateSubscribers(int $subscriberCount): void
    {
        if (0 === $subscriberCount) {
            throw new Exception('No subscribed users found.');
        }
    }

    public function validateNewsletterPost(NewsletterPost $newsletterPost): void
    {
        if ( ! $newsletterPost->isReady()) {
            throw new Exception('Newsletter post is not ready.');
        }
    }

    public function validateSendHistoryNotSending(NewsletterSendHistory $sendHistory): void
    {
        if ($sendHistory->isSending()) {
            throw new Exception('Newsletter send history is already sending.');
        }
    }

    public function validateSubscriberNotSending(NewsletterSendHistorySubscriber $subscriber): void
    {
        if ($subscriber->isSending()) {
            throw new Exception('Newsletter send history subscriber is sending.');
        }
    }

    public function createAndDispatchJob(Newsletter $newsletter, Collection $newsletterPosts): void
    {
        $sendHistory = $this->createSendHistoryAction->execute($newsletter, $newsletterPosts);
        SendNewsletterBatchJob::dispatch($sendHistory);
    }

    /**
     * @return array<string, int>
     */
    public function getDryRunResponse(int $subscriberCount): array
    {
        return ['queued' => $subscriberCount];
    }

    /**
     * @return array<string, int>
     */
    public function getSuccessResponse(int $subscriberCount): array
    {
        return ['queued' => $subscriberCount];
    }
}
