<?php

declare(strict_types=1);

namespace Misaf\Newsletter\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * @property int $newsletter_send_history_id
 * @property int $newsletter_post_id
 *
 * @method BelongsTo<NewsletterSendHistory, $this> newsletterSendHistory()
 * @method BelongsTo<NewsletterPost, $this> newsletterPost()
 */
final class NewsletterSendHistoryPost extends Pivot
{
    /** @var bool $incrementing */
    public $incrementing = true;

    /** @var bool $timestamps */
    public $timestamps = false;

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'id'                         => 'integer',
        'newsletter_send_history_id' => 'integer',
        'newsletter_post_id'         => 'integer',
    ];

    /**
     * @var list<string>
     */
    protected $fillable = [
        'newsletter_send_history_id',
        'newsletter_post_id',
    ];

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
