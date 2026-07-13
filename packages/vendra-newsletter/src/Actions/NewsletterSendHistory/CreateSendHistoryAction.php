<?php

declare(strict_types=1);

namespace Misaf\VendraNewsletter\Actions\NewsletterSendHistory;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Misaf\VendraNewsletter\Enums\NewsletterSendHistoryStatusEnum;
use Misaf\VendraNewsletter\Models\Newsletter;
use Misaf\VendraNewsletter\Models\NewsletterPost;
use Misaf\VendraNewsletter\Models\NewsletterSendHistory;

final class CreateSendHistoryAction
{
    /**
     * Create a new newsletter send history record
     *
     * @param  Collection<int, NewsletterPost>  $newsletterPosts
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
