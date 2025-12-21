<?php

declare(strict_types=1);

namespace Misaf\Newsletter\Actions\NewsletterSubscriber;

use Misaf\Newsletter\Models\Newsletter;
use Misaf\Newsletter\Models\NewsletterSubscriber;
use Misaf\Newsletter\Services\NewsletterPostService;
use Misaf\Newsletter\Services\NewsletterService;
use Misaf\Newsletter\Services\NewsletterSubscriberService;
use Misaf\User\Models\User;

final class SyncWithUsersAction
{
    public function __construct(
        private readonly NewsletterPostService $postService,
        private readonly NewsletterService $newsletterService,
        private readonly NewsletterSubscriberService $subscriberService,
    ) {}

    /**
     * Sync newsletter subscribers with users
     *
     * @param Newsletter $newsletter
     * @return array<string, int>
     */
    public function execute(Newsletter $newsletter): array
    {
        $users = User::where('tenant_id', $newsletter->tenant_id)->get();
        $subscribers = NewsletterSubscriber::where('tenant_id', $newsletter->tenant_id)->get();

        $syncedCount = 0;
        $createdCount = 0;

        foreach ($users as $user) {
            $existingSubscriber = $subscribers->firstWhere('user_id', $user->id);

            if ($existingSubscriber) {
                // Update existing subscriber
                $existingSubscriber->update([
                    'email' => $user->email,
                    'name'  => $user->name,
                ]);
                $syncedCount++;
            } else {
                // Create new subscriber
                NewsletterSubscriber::create([
                    'tenant_id'     => $user->tenant_id,
                    'user_id'       => $user->id,
                    'email'         => $user->email,
                    'name'          => $user->name,
                    'subscribed_at' => now(),
                ]);
                $createdCount++;
            }
        }

        // Clear caches
        $this->postService->clearCache();
        $this->newsletterService->clearCache();
        $this->subscriberService->clearCache();

        return [
            'synced'  => $syncedCount,
            'created' => $createdCount,
        ];
    }
}
