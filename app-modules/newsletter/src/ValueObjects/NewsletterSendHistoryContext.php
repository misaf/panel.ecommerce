<?php

declare(strict_types=1);

namespace Misaf\Newsletter\ValueObjects;

use Illuminate\Database\Eloquent\Collection;
use Misaf\Newsletter\Models\NewsletterSendHistory;
use Misaf\Newsletter\Models\NewsletterSendHistorySubscriber;

final class NewsletterSendHistoryContext extends NewsletterSendContext
{
    /**
     * @param Collection<int, NewsletterSendHistory> $sendHistories
     */
    public static function fromSendHistories(Collection $sendHistories, bool $isDryRun = false): self
    {
        // Use eager loading to avoid N+1 queries
        $sendHistories->load([
            'newsletter.newsletterSubscribedUsers',
            'newsletter.newsletterPosts' => fn($query) => $query->ready(),
            'newsletterPosts'
        ]);

        $newsletters = $sendHistories
            ->map(fn($history) => $history->newsletter)
            ->unique('id');

        $subscribers = $newsletters
            ->flatMap(fn($newsletter) => $newsletter->newsletterSubscribedUsers)
            ->unique('id');

        $posts = $sendHistories
            ->flatMap(fn($history) => $history->newsletterPosts)
            ->unique('id');

        return new self(
            newsletters: $newsletters,
            subscribers: $subscribers,
            posts: $posts,
            subscriberCount: $subscribers->count(),
            isDryRun: $isDryRun
        );
    }

    /**
     * @param Collection<int, NewsletterSendHistorySubscriber> $historySubscribers
     */
    public static function fromSendHistorySubscribers(Collection $historySubscribers, bool $isDryRun = false): self
    {
        // Use eager loading to avoid N+1 queries
        $historySubscribers->load([
            'newsletterSendHistory.newsletter.newsletterSubscribedUsers',
            'newsletterSendHistory.newsletter.newsletterPosts' => fn($query) => $query->ready(),
            'newsletterSendHistory.newsletterPosts',
            'newsletterSubscriber'
        ]);

        $newsletters = $historySubscribers
            ->map(fn($subscriber) => $subscriber->newsletterSendHistory->newsletter)
            ->unique('id');

        $subscribers = $historySubscribers
            ->map(fn($subscriber) => $subscriber->newsletterSubscriber)
            ->unique('id');

        $posts = $historySubscribers
            ->flatMap(fn($subscriber) => $subscriber->newsletterSendHistory->newsletterPosts)
            ->unique('id');

        return new self(
            newsletters: $newsletters,
            subscribers: $subscribers,
            posts: $posts,
            subscriberCount: $subscribers->count(),
            isDryRun: $isDryRun
        );
    }
}
