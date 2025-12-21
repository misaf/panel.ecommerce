<?php

declare(strict_types=1);

namespace Misaf\Newsletter\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Misaf\Newsletter\Database\Factories\NewsletterSendHistorySubscriberFactory;
use Misaf\Newsletter\Enums\NewsletterSendHistoryStatusEnum;

/**
 * @property int $id
 * @property int $newsletter_send_history_id
 * @property int $newsletter_subscriber_id
 * @property NewsletterSendHistoryStatusEnum $status
 * @property Carbon|null $sent_at
 * @property Carbon|null $failed_at
 * @property string|null $failed_message
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @method static NewsletterSendHistorySubscriberFactory newFactory()
 * @method static Builder<NewsletterSendHistorySubscriber> sending()
 * @method static Builder<NewsletterSendHistorySubscriber> sent()
 * @method static Builder<NewsletterSendHistorySubscriber> failed()
 * @method bool isSending()
 * @method bool isSent()
 * @method bool isFailed()
 * @method BelongsTo<NewsletterSendHistory, $this> newsletterSendHistory()
 * @method BelongsTo<NewsletterSubscriber, $this> newsletterSubscriber()
 */
final class NewsletterSendHistorySubscriber extends Model
{
    /** @use HasFactory<NewsletterSendHistorySubscriberFactory> */
    use HasFactory;

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'id'                         => 'integer',
        'newsletter_send_history_id' => 'integer',
        'newsletter_subscriber_id'   => 'integer',
        'status'                     => NewsletterSendHistoryStatusEnum::class,
        'sent_at'                    => 'datetime',
        'failed_at'                  => 'datetime',
        'failed_message'             => 'string',
    ];

    /**
     * @var list<string>
     */
    protected $fillable = [
        'newsletter_send_history_id',
        'newsletter_subscriber_id',
        'status',
        'sent_at',
        'failed_at',
        'failed_message',
    ];

    protected static function newFactory(): NewsletterSendHistorySubscriberFactory
    {
        return NewsletterSendHistorySubscriberFactory::new();
    }

    /**
     * @param Builder<NewsletterSendHistorySubscriber> $query
     * @return Builder<NewsletterSendHistorySubscriber>
     */
    #[Scope]
    protected function sending(Builder $query): Builder
    {
        return $query->where('status', NewsletterSendHistoryStatusEnum::SENDING);
    }

    /**
     * @param Builder<NewsletterSendHistorySubscriber> $query
     * @return Builder<NewsletterSendHistorySubscriber>
     */
    #[Scope]
    protected function sent(Builder $query): Builder
    {
        return $query->where('status', NewsletterSendHistoryStatusEnum::SENT);
    }

    /**
     * @param Builder<NewsletterSendHistorySubscriber> $query
     * @return Builder<NewsletterSendHistorySubscriber>
     */
    #[Scope]
    protected function failed(Builder $query): Builder
    {
        return $query->where('status', NewsletterSendHistoryStatusEnum::FAILED);
    }

    public function isSending(): bool
    {
        return NewsletterSendHistoryStatusEnum::SENDING === $this->status;
    }

    public function isSent(): bool
    {
        return NewsletterSendHistoryStatusEnum::SENT === $this->status;
    }

    public function isFailed(): bool
    {
        return NewsletterSendHistoryStatusEnum::FAILED === $this->status;
    }

    /**
     * @return BelongsTo<NewsletterSendHistory, $this>
     */
    public function newsletterSendHistory(): BelongsTo
    {
        return $this->belongsTo(NewsletterSendHistory::class);
    }

    /**
     * @return BelongsTo<NewsletterSubscriber, $this>
     */
    public function newsletterSubscriber(): BelongsTo
    {
        return $this->belongsTo(NewsletterSubscriber::class);
    }
}
