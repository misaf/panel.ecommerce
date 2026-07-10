<?php

declare(strict_types=1);

namespace Misaf\VendraNewsletter\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * @property int $id
 * @property int $newsletter_send_history_id
 * @property int $newsletter_post_id
 *
 * @method BelongsTo<NewsletterSendHistory, $this> newsletterSendHistory()
 * @method BelongsTo<NewsletterPost, $this> newsletterPost()
 */
#[Fillable(['newsletter_send_history_id', 'newsletter_post_id'])]
final class NewsletterSendHistoryPost extends Pivot
{
    /** @var bool */
    public $incrementing = true;

    /** @var bool */
    public $timestamps = false;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'id'                         => 'integer',
            'newsletter_send_history_id' => 'integer',
            'newsletter_post_id'         => 'integer',
        ];
    }

    /**
     * @return BelongsTo<NewsletterSendHistory, $this>
     */
    public function newsletterSendHistory(): BelongsTo
    {
        return $this->belongsTo(NewsletterSendHistory::class);
    }

    /**
     * @return BelongsTo<NewsletterPost, $this>
     */
    public function newsletterPost(): BelongsTo
    {
        return $this->belongsTo(NewsletterPost::class);
    }
}
