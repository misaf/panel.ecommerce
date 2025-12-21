<?php

declare(strict_types=1);

namespace Misaf\Newsletter\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Carbon;

/**
 * @property int $newsletter_id
 * @property int $newsletter_subscriber_id
 * @property Carbon $subscribed_at
 * @property Carbon|null $unsubscribed_at
 *
 * @method static Builder<NewsletterSubscription> subscribed()
 * @method static Builder<NewsletterSubscription> unsubscribed()
 * @method BelongsTo<Newsletter, $this> newsletter()
 * @method BelongsTo<NewsletterSubscriber, $this> newsletterSubscriber()
 */
final class NewsletterSubscription extends Pivot
{
    /** @var bool $timestamps */
    public $timestamps = false;

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'newsletter_id'            => 'integer',
        'newsletter_subscriber_id' => 'integer',
        'subscribed_at'            => 'datetime',
        'unsubscribed_at'          => 'datetime',
    ];

    /**
     * @var list<string>
     */
    protected $fillable = [
        'newsletter_id',
        'newsletter_subscriber_id',
        'subscribed_at',
        'unsubscribed_at',
    ];

    /**
     * @param Builder<NewsletterSubscription> $query
     * @return Builder<NewsletterSubscription>
     */
    #[Scope]
    protected function subscribed(Builder $query): Builder
    {
        return $query->whereNull('unsubscribed_at');
    }

    /**
     * @param Builder<NewsletterSubscription> $query
     * @return Builder<NewsletterSubscription>
     */
    #[Scope]
    protected function unsubscribed(Builder $query): Builder
    {
        return $query->whereNotNull('unsubscribed_at');
    }

    /**
     * @return BelongsTo<Newsletter, $this>
     */
    public function newsletter(): BelongsTo
    {
        return $this->belongsTo(Newsletter::class);
    }

    /**
     * @return BelongsTo<NewsletterSubscriber, $this>
     */
    public function newsletterSubscriber(): BelongsTo
    {
        return $this->belongsTo(NewsletterSubscriber::class);
    }
}
