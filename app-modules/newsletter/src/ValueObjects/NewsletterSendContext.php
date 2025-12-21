<?php

declare(strict_types=1);

namespace Misaf\Newsletter\ValueObjects;

use Illuminate\Database\Eloquent\Collection;
use Misaf\Newsletter\Models\Newsletter;
use Misaf\Newsletter\Models\NewsletterPost;
use Misaf\Newsletter\Models\NewsletterSubscriber;

class NewsletterSendContext
{
    /**
     * @param Collection<int, Newsletter> $newsletters
     * @param Collection<int, NewsletterSubscriber> $subscribers
     * @param Collection<int, NewsletterPost> $posts
     */
    public function __construct(
        public readonly Collection $newsletters,
        public readonly Collection $subscribers,
        public readonly Collection $posts,
        public readonly int $subscriberCount,
        public readonly bool $isDryRun = false
    ) {}

    public static function fromNewsletters(Collection $newsletters, bool $isDryRun = false): self
    {
        $subscribers = self::collectSubscribersFromNewsletters($newsletters);
        $posts = self::collectPostsFromNewsletters($newsletters);

        return new self(
            newsletters: $newsletters,
            subscribers: $subscribers,
            posts: $posts,
            subscriberCount: $subscribers->count(),
            isDryRun: $isDryRun
        );
    }

    public static function fromPosts(Collection $newsletterPosts, bool $isDryRun = false): self
    {
        $newsletters = $newsletterPosts
            ->map(fn($post) => $post->newsletter)
            ->unique('id');

        $subscribers = self::collectSubscribersFromNewsletters($newsletters);

        return new self(
            newsletters: $newsletters,
            subscribers: $subscribers,
            posts: $newsletterPosts,
            subscriberCount: $subscribers->count(),
            isDryRun: $isDryRun
        );
    }

    private static function collectSubscribersFromNewsletters(Collection $newsletters): Collection
    {
        // Use eager loading to avoid N+1 queries
        $newsletters->load('newsletterSubscribedUsers');

        return $newsletters
            ->flatMap(fn($newsletter) => $newsletter->newsletterSubscribedUsers)
            ->unique('id');
    }

    private static function collectPostsFromNewsletters(Collection $newsletters): Collection
    {
        // Use eager loading to avoid N+1 queries
        $newsletters->load(['newsletterPosts' => fn($query) => $query->ready()]);

        return $newsletters
            ->flatMap(fn($newsletter) => $newsletter->newsletterPosts)
            ->unique('id');
    }
}
