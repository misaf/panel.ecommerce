<?php

declare(strict_types=1);

namespace Misaf\Newsletter\Actions\Newsletter;

use Illuminate\Database\Eloquent\Collection;
use Misaf\Newsletter\Actions\AbstractSendAction;
use Misaf\Newsletter\Exceptions\NewsletterSendException;
use Misaf\Newsletter\Models\Newsletter;
use Misaf\Newsletter\ValueObjects\NewsletterSendContext;

final class SendAction extends AbstractSendAction
{
    /**
     * @param Collection<int, Newsletter> $entities
     */
    protected function buildContext(Collection $entities, bool $isDryRun): NewsletterSendContext
    {
        // Use optimized collection with eager loading
        $entities->load([
            'newsletterSubscribedUsers',
            'newsletterPosts' => fn($query) => $query->ready()
        ]);

        return NewsletterSendContext::fromNewsletters($entities, $isDryRun);
    }

    /**
     * @param Collection<int, Newsletter> $entities
     */
    protected function dispatch(NewsletterSendContext $context, Collection $entities): void
    {
        $dispatchedCount = 0;

        /** @var Newsletter $newsletter */
        foreach ($entities as $newsletter) {
            // Get posts for this specific newsletter
            $newsletterPosts = $context->posts->where('newsletter_id', $newsletter->id);

            if ($newsletterPosts->isEmpty()) {
                continue; // Skip newsletters without ready posts
            }

            $this->dispatchJob(
                fn() => $this->sendService->createAndDispatchJob($newsletter, $newsletterPosts),
                [
                    'id'              => $newsletter->id,
                    'newsletter_id'   => $newsletter->id,
                    'newsletter_slug' => $newsletter->slug,
                    'post_count'      => $newsletterPosts->count(),
                ]
            );

            $dispatchedCount++;
        }

        if (0 === $dispatchedCount) {
            throw NewsletterSendException::noReadyPosts();
        }
    }

    protected function getEntityType(): string
    {
        return 'Newsletter';
    }
}
