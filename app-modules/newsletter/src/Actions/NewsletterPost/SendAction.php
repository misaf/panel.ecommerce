<?php

declare(strict_types=1);

namespace Misaf\Newsletter\Actions\NewsletterPost;

use Illuminate\Database\Eloquent\Collection;
use Misaf\Newsletter\Actions\AbstractSendAction;
use Misaf\Newsletter\Models\NewsletterPost;
use Misaf\Newsletter\ValueObjects\NewsletterSendContext;

final class SendAction extends AbstractSendAction
{
    /**
     * @param Collection<int, NewsletterPost> $entities
     */
    protected function buildContext(Collection $entities, bool $isDryRun): NewsletterSendContext
    {
        // Use optimized collection with eager loading
        $entities->load([
            'newsletter.newsletterSubscribedUsers',
            'newsletter.newsletterPosts' => fn($query) => $query->ready()
        ]);

        return NewsletterSendContext::fromPosts($entities, $isDryRun);
    }

    /**
     * @param Collection<int, NewsletterPost> $entities
     */
    protected function dispatch(NewsletterSendContext $context, Collection $entities): void
    {
        // Group posts by newsletter for efficient dispatching
        $postsByNewsletter = $entities->groupBy('newsletter_id');
        $dispatchedCount = 0;

        foreach ($postsByNewsletter as $newsletterId => $posts) {
            $newsletter = $posts->first()->newsletter;

            $this->dispatchJob(
                fn() => $this->sendService->createAndDispatchJob($newsletter, $posts),
                [
                    'id'              => $newsletter->id,
                    'newsletter_id'   => $newsletter->id,
                    'newsletter_slug' => $newsletter->slug,
                    'post_count'      => $posts->count(),
                    'post_ids'        => $posts->pluck('id')->toArray(),
                ]
            );

            $dispatchedCount++;
        }
    }

    protected function getEntityType(): string
    {
        return 'NewsletterPost';
    }
}
