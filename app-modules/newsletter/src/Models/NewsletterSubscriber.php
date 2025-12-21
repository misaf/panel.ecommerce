<?php

declare(strict_types=1);

namespace Misaf\Newsletter\Models;

use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Misaf\Newsletter\Database\Factories\NewsletterSubscriberFactory;
use Misaf\Newsletter\Observers\NewsletterSubscriberObserver;
use Misaf\User\Models\User;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Tags\HasTags;

/**
 * @property int $id
 * @property int|null $user_id
 * @property string $email
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon|null $deleted_at
 *
 * @method static NewsletterSubscriberFactory newFactory()
 * @method Attribute<string, string> email()
 * @method BelongsToMany<Newsletter, $this, NewsletterSubscription> newsletters()
 * @method BelongsTo<User, $this> user()
 * @method HasMany<NewsletterSendHistorySubscriber, $this> newsletterSendHistorySubscribers()
 */
#[ObservedBy([NewsletterSubscriberObserver::class])]
final class NewsletterSubscriber extends Model
{
    /** @use HasFactory<NewsletterSubscriberFactory> */
    use HasFactory;
    use HasTags;
    use LogsActivity;
    use SoftDeletes;

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'id'      => 'integer',
        'user_id' => 'integer',
        'email'   => 'string',
    ];

    /**
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'email',
    ];

    protected static function newFactory(): NewsletterSubscriberFactory
    {
        return NewsletterSubscriberFactory::new();
    }

    /**
     * @return Attribute<string, string>
     */
    protected function email(): Attribute
    {
        return Attribute::make(
            set: fn(string $value): string => Str::lower($value),
        );
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsToMany<Newsletter, $this, NewsletterSubscription>
     */
    public function newsletters(): BelongsToMany
    {
        return $this->belongsToMany(Newsletter::class, 'newsletter_subscription')
            ->using(NewsletterSubscription::class)
            ->withPivot(['subscribed_at', 'unsubscribed_at']);
    }

    /**
     * @return BelongsToMany<Newsletter, $this, NewsletterSubscription>
     */
    public function subscribedNewsletters(): BelongsToMany
    {
        return $this->newsletters()->wherePivotNull('unsubscribed_at');
    }

    /**
     * @return BelongsToMany<Newsletter, $this, NewsletterSubscription>
     */
    public function unsubscribedNewsletters(): BelongsToMany
    {
        return $this->newsletters()->wherePivotNotNull('unsubscribed_at');
    }

    /**
     * @return HasMany<NewsletterSendHistorySubscriber, $this>
     */
    public function newsletterSendHistorySubscribers(): HasMany
    {
        return $this->hasMany(NewsletterSendHistorySubscriber::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->logExcept(['id']);
    }
}
