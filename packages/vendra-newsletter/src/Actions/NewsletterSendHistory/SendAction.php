<?php

declare(strict_types=1);

namespace Misaf\VendraNewsletter\Actions\NewsletterSendHistory;

use Illuminate\Database\Eloquent\Collection;
use Misaf\VendraNewsletter\Actions\AbstractSendAction;
use Misaf\VendraNewsletter\Exceptions\NewsletterSendException;
use Misaf\VendraNewsletter\Models\NewsletterSendHistory;
use Misaf\VendraNewsletter\ValueObjects\NewsletterSendContext;
use Misaf\VendraNewsletter\ValueObjects\NewsletterSendHistoryContext;

final class SendAction extends AbstractSendAction
{
    /**
     * @param  Collection<int, NewsletterSendHistory>  $entities
     */
    protected function buildContext(Collection $entities, bool $isDryRun): NewsletterSendContext
    {
        return NewsletterSendHistoryContext::fromSendHistories($entities, $isDryRun);
    }

    /**
     * @param  Collection<int, NewsletterSendHistory>  $entities
     */
    protected function dispatch(NewsletterSendContext $context, Collection $entities): void
    {
        $dispatchedCount = 0;

        /** @var NewsletterSendHistory $history */
        foreach ($entities as $history) {
            // Get posts associated with this history
            $historyPosts = $history->newsletterPosts->isEmpty()
                ? $history->newsletter->newsletterPosts->filter(fn($post) => $post->isReady())
                : $history->newsletterPosts;

            if ($historyPosts->isEmpty()) {
                continue; // Skip if no posts available
            }

            $this->dispatchJob(
                fn() => $this->sendService->createAndDispatchJob($history->newsletter, $historyPosts),
                [
                    'id'              => $history->id,
                    'send_history_id' => $history->id,
                    'newsletter_id'   => $history->newsletter_id,
                    'newsletter_slug' => $history->newsletter->slug,
                    'post_count'      => $historyPosts->count(),
                    'retry_attempt'   => true,
                ]
            );

            $dispatchedCount++;
        }

        if (0 === $dispatchedCount) {
            throw NewsletterSendException::noReadyPosts();
        }
    }

    /**
     * @param  Collection<int, NewsletterSendHistory>  $entities
     */
    protected function validateEntities(Collection $entities): void
    {
        // Validate send histories using the validation service
        $this->validationService->validateSendHistories($entities);
    }

    protected function performAdditionalValidation(NewsletterSendContext $context): void
    {
        // Additional context-based validation would go here if needed
    }

    protected function getEntityType(): string
    {
        return 'NewsletterSendHistory';
    }
}
