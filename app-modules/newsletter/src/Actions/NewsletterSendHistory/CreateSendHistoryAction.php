<?php

declare(strict_types=1);

namespace Misaf\Newsletter\Actions\NewsletterSendHistory;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Misaf\Newsletter\Enums\NewsletterSendHistoryStatusEnum;
use Misaf\Newsletter\Models\Newsletter;
use Misaf\Newsletter\Models\NewsletterPost;
use Misaf\Newsletter\Models\NewsletterSendHistory;

final class CreateSendHistoryAction
{
    /**
     * Create a new newsletter send history record
     *
     * @param Newsletter $newsletter
     * @param Collection<int, NewsletterPost> $newsletterPosts
     * @return NewsletterSendHistory
     */
    public function execute(Newsletter $newsletter, Collection $newsletterPosts): NewsletterSendHistory
    {
        $newsletterSendHistory = NewsletterSendHistory::create([
            'newsletter_id'     => $newsletter->id,
            'status'            => NewsletterSendHistoryStatusEnum::SENDING,
            'total_subscribers' => $newsletter->newsletterSubscribedUsers()->count(),
            'started_at'        => Carbon::now(),
        ]);

        $newsletterSendHistory->newsletterPosts()->attach($newsletterPosts->pluck('id'));

        return $newsletterSendHistory;
    }
}
