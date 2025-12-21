<?php

declare(strict_types=1);

namespace Misaf\Newsletter\Models;

use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Misaf\Newsletter\Database\Factories\NewsletterSendHistoryFactory;
use Misaf\Newsletter\Enums\NewsletterSendHistoryStatusEnum;
use Misaf\Newsletter\Observers\NewsletterSendHistoryObserver;

/**
 * @property int $id
 * @property int $newsletter_id
 * @property string $token
 * @property NewsletterSendHistoryStatusEnum $status
 * @property int $total_subscribers
 * @property int $sent_count
 * @property int $failed_count
 * @property Carbon $started_at
 * @property Carbon|null $completed_at
 *
 * @method static NewsletterSendHistoryFactory newFactory()
 * @method static Builder<NewsletterSendHistorySubscriber> sending()
 * @method static Builder<NewsletterSendHistorySubscriber> sent()
 * @method static Builder<NewsletterSendHistorySubscriber> failed()
 * @method bool isSending()
 * @method bool isSent()
 * @method bool isFailed()
 * @method BelongsTo<Newsletter, $this> newsletter()
 * @method BelongsToMany<NewsletterPost, $this> newsletterPosts()
 * @method HasMany<NewsletterSendHistorySubscriber, $this> newsletterSendHistorySubscribers()
 */
#[ObservedBy([NewsletterSendHistoryObserver::class])]
final class NewsletterSendHistory extends Model
{
    /** @use HasFactory<NewsletterSendHistoryFactory> */
    use HasFactory;

    /** @var bool $timestamps */
    public $timestamps = false;

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'id'                  => 'integer',
        'newsletter_id'       => 'integer',
        'token'               => 'string',
        'status'              => NewsletterSendHistoryStatusEnum::class,
        'total_subscribers'   => 'integer',
        'sent_count'          => 'integer',
        'failed_count'        => 'integer',
        'started_at'          => 'datetime',
        'completed_at'        => 'datetime',
    ];

    /**
     * @var list<string>
     */
    protected $fillable = [
        'newsletter_id',
        'token',
        'status',
        'total_subscribers',
        'sent_count',
        'failed_count',
        'started_at',
        'completed_at',
    ];

    protected static function newFactory(): NewsletterSendHistoryFactory
    {
        return NewsletterSendHistoryFactory::new();
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
     * @return BelongsTo<Newsletter, $this>
     */
    public function newsletter(): BelongsTo
    {
        return $this->belongsTo(Newsletter::class);
    }

    /**
     * @return BelongsToMany<NewsletterPost, $this>
     */
    public function newsletterPosts(): BelongsToMany
    {
        return $this->belongsToMany(NewsletterPost::class, 'newsletter_send_history_post');
    }

    /**
     * @return HasMany<NewsletterSendHistorySubscriber, $this>
     */
    public function newsletterSendHistorySubscribers(): HasMany
    {
        return $this->hasMany(NewsletterSendHistorySubscriber::class);
    }
}
