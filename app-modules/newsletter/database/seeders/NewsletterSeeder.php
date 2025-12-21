<?php

declare(strict_types=1);

namespace Misaf\Newsletter\Database\Seeders;

use Illuminate\Database\Seeder;
use Misaf\Newsletter\Enums\NewsletterSendHistoryStatusEnum;
use Misaf\Newsletter\Models\Newsletter;
use Misaf\Newsletter\Models\NewsletterPost;
use Misaf\Newsletter\Models\NewsletterSendHistory;
use Misaf\Newsletter\Models\NewsletterSendHistorySubscriber;
use Misaf\Newsletter\Models\NewsletterSubscriber;
use Misaf\Tenant\Models\Tenant;

final class NewsletterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        // Set the current tenant context to solve tenant_id issues
        Tenant::first()->makeCurrent();

        $newsletter = Newsletter::factory()
            ->enabled()
            ->create();

        $posts = NewsletterPost::factory()
            ->count(rand(3, 5))
            ->withNewsletter($newsletter)
            ->ready()
            ->create();

        $subscribers = NewsletterSubscriber::factory()
            ->count(rand(10, 20))
            ->create();

        // Attach subscribed users to the newsletter with subscription times after newsletter creation
        $subscriberIds = $subscribers->pluck('id')->toArray();
        $newsletter->newsletterSubscribers()->attach($subscriberIds, [
            'subscribed_at' => fake()->dateTimeBetween($newsletter->created_at, 'now'),
        ]);

        $sendHistory = NewsletterSendHistory::factory()
            ->withNewsletter($newsletter)
            ->sent()
            ->create([
                'total_subscribers' => $subscribers->count(),
            ]);

        // Attach posts to the send history
        $postIds = $posts->pluck('id')->toArray();
        $sendHistory->newsletterPosts()->attach($postIds);

        // Create send history subscribers (track individual subscriber send status)
        foreach ($subscribers as $subscriber) {
            NewsletterSendHistorySubscriber::factory()
                ->withNewsletterSendHistory($sendHistory)
                ->withNewsletterSubscriber($subscriber)
                ->sent()
                ->create();
        }

        // Create additional newsletters with different statuses for variety
        $this->createAdditionalNewsletters();
    }

    /**
     * Create additional newsletters with different configurations
     *
     * @return void
     */
    private function createAdditionalNewsletters(): void
    {
        // Newsletter with draft posts
        $draftNewsletter = Newsletter::factory()
            ->enabled()
            ->create();

        NewsletterPost::factory()
            ->count(rand(2, 4))
            ->withNewsletter($draftNewsletter)
            ->draft()
            ->create();

        // Newsletter with scheduled send
        $scheduledNewsletter = Newsletter::factory()
            ->scheduled()
            ->disabled()
            ->create();

        NewsletterPost::factory()
            ->count(rand(2, 3))
            ->withNewsletter($scheduledNewsletter)
            ->draft()
            ->create();

        // Newsletter with failed send history
        $failedNewsletter = Newsletter::factory()
            ->enabled()
            ->create();

        $failedPosts = NewsletterPost::factory()
            ->count(rand(2, 3))
            ->withNewsletter($failedNewsletter)
            ->ready()
            ->create();

        $failedSubscribers = NewsletterSubscriber::factory()
            ->count(rand(5, 10))
            ->create();

        foreach ($failedSubscribers as $subscriber) {
            $failedNewsletter->newsletterSubscribers()->attach($subscriber->id, [
                'subscribed_at' => fake()->dateTimeBetween($failedNewsletter->created_at, 'now'),
            ]);
        }

        $failedSendHistory = NewsletterSendHistory::factory()
            ->withNewsletter($failedNewsletter)
            ->failed()
            ->create([
                'total_subscribers' => $failedSubscribers->count(),
                'sent_count'        => rand(1, $failedSubscribers->count() - 1),
                'failed_count'      => $failedSubscribers->count() - rand(1, $failedSubscribers->count() - 1),
                'started_at'        => now()->subHours(3),
                'completed_at'      => now()->subHours(2),
            ]);

        // $failedSendHistory = NewsletterSendHistory::factory()
        //     ->withNewsletter($newsletter)
        //     ->failed()
        //     ->create([
        //         'total_subscribers' => $subscribers->count(),
        //     ]);

        // Attach failed posts to the failed send history
        $failedPostIds = $failedPosts->pluck('id')->toArray();
        $failedSendHistory->newsletterPosts()->attach($failedPostIds);

        foreach ($failedSubscribers as $subscriber) {
            $status = rand(0, 1) ? NewsletterSendHistoryStatusEnum::SENT : NewsletterSendHistoryStatusEnum::FAILED;

            NewsletterSendHistorySubscriber::factory(['status' => $status])
                ->withNewsletterSendHistory($failedSendHistory)
                ->withNewsletterSubscriber($subscriber)
                ->create();
            // NewsletterSendHistorySubscriber::create([
            //     'newsletter_send_history_id' => $failedSendHistory->id,
            //     'newsletter_subscriber_id'   => $subscriber->id,
            //     'status'                     => $status,
            //     'sent_at'                    => NewsletterSendHistoryStatusEnum::SENT === $status ? now()->subHours(2) : null,
            //     'failed_at'                  => NewsletterSendHistoryStatusEnum::FAILED === $status ? now()->subHours(2) : null,
            //     'failed_message'             => NewsletterSendHistoryStatusEnum::FAILED === $status ? 'Delivery failed' : null,
            // ]);
        }
    }
}
