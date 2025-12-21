<?php

declare(strict_types=1);

namespace Misaf\Newsletter\Actions\NewsletterSendHistorySubscriber;

use Illuminate\Database\Eloquent\Collection;
use Misaf\Newsletter\Actions\AbstractSendAction;
use Misaf\Newsletter\Exceptions\NewsletterSendException;
use Misaf\Newsletter\Models\NewsletterSendHistorySubscriber;
use Misaf\Newsletter\ValueObjects\NewsletterSendContext;
use Misaf\Newsletter\ValueObjects\NewsletterSendHistoryContext;

final class SendAction extends AbstractSendAction
{
    /**
     * @param Collection<int, NewsletterSendHistorySubscriber> $entities
     */
    protected function buildContext(Collection $entities, bool $isDryRun): NewsletterSendContext
    {
        return NewsletterSendHistoryContext::fromSendHistorySubscribers($entities, $isDryRun);
    }

    /**
     * @param Collection<int, NewsletterSendHistorySubscriber> $entities
     */
    protected function dispatch(NewsletterSendContext $context, Collection $entities): void
    {
        // Group subscribers by send history for batch processing
        $subscribersBySendHistory = $entities->groupBy('newsletter_send_history_id');
        $dispatchedCount = 0;

        foreach ($subscribersBySendHistory as $sendHistoryId => $subscribers) {
            /** @var NewsletterSendHistorySubscriber $firstSubscriber */
            $firstSubscriber = $subscribers->first();
            $sendHistory = $firstSubscriber->newsletterSendHistory;
            $newsletter = $sendHistory->newsletter;

            // Get posts for this send history
            $historyPosts = $sendHistory->newsletterPosts->isEmpty()
                ? $newsletter->newsletterPosts->filter(fn($post) => $post->isReady())
                : $sendHistory->newsletterPosts;

            if ($historyPosts->isEmpty()) {
                continue; // Skip if no posts available
            }

            // Create a custom job for specific subscribers
            $this->dispatchJob(
                fn() => $this->sendService->createAndDispatchJob($newsletter, $historyPosts),
                [
                    'id'                => $sendHistoryId,
                    'send_history_id'   => $sendHistoryId,
                    'newsletter_id'     => $newsletter->id,
                    'newsletter_slug'   => $newsletter->slug,
                    'subscriber_count'  => $subscribers->count(),
                    'subscriber_ids'    => $subscribers->pluck('id')->toArray(),
                    'retry_subscribers' => true,
                ]
            );

            $dispatchedCount++;
        }

        if (0 === $dispatchedCount) {
            throw NewsletterSendException::noReadyPosts();
        }
    }

    protected function performAdditionalValidation(NewsletterSendContext $context): void
    {
        // Additional validation would go here
        // For example: validate that subscribers are not already being sent to
    }

    protected function getEntityType(): string
    {
        return 'NewsletterSendHistorySubscriber';
    }
}
