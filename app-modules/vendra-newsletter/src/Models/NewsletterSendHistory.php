<?php

declare(strict_types=1);

namespace Misaf\VendraNewsletter\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Misaf\VendraNewsletter\Database\Factories\NewsletterSendHistoryFactory;
use Misaf\VendraNewsletter\Enums\NewsletterSendHistoryStatusEnum;
use Misaf\VendraNewsletter\Observers\NewsletterSendHistoryObserver;

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
#[Fillable(['newsletter_id', 'token', 'status', 'total_subscribers', 'sent_count', 'failed_count', 'started_at', 'completed_at'])]
#[ObservedBy([NewsletterSendHistoryObserver::class])]
#[UseFactory(NewsletterSendHistoryFactory::class)]
final class NewsletterSendHistory extends Model
{
    /** @use HasFactory<NewsletterSendHistoryFactory> */
    use HasFactory;

    /** @var bool */
    public $timestamps = false;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'id'                => 'integer',
            'newsletter_id'     => 'integer',
            'token'             => 'string',
            'status'            => NewsletterSendHistoryStatusEnum::class,
            'total_subscribers' => 'integer',
            'sent_count'        => 'integer',
            'failed_count'      => 'integer',
            'started_at'        => 'datetime',
            'completed_at'      => 'datetime',
        ];
    }

    /**
     * @param  Builder<NewsletterSendHistorySubscriber>  $query
     * @return Builder<NewsletterSendHistorySubscriber>
     */
    #[Scope]
    protected function sending(Builder $query): Builder
    {
        return $query->where('status', NewsletterSendHistoryStatusEnum::SENDING);
    }

    /**
     * @param  Builder<NewsletterSendHistorySubscriber>  $query
     * @return Builder<NewsletterSendHistorySubscriber>
     */
    #[Scope]
    protected function sent(Builder $query): Builder
    {
        return $query->where('status', NewsletterSendHistoryStatusEnum::SENT);
    }

    /**
     * @param  Builder<NewsletterSendHistorySubscriber>  $query
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
