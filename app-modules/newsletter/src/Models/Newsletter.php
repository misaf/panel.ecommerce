<?php

declare(strict_types=1);

namespace Misaf\Newsletter\Models;

use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Misaf\Newsletter\Database\Factories\NewsletterFactory;
use Misaf\Newsletter\Observers\NewsletterObserver;
use Misaf\Tenant\Traits\BelongsToTenant;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Sluggable\HasTranslatableSlug;
use Spatie\Sluggable\SlugOptions;
use Spatie\Translatable\HasTranslations;

/**
 * @property int $id
 * @property int $tenant_id
 * @property array<string, string> $name
 * @property array<string, string> $description
 * @property array<string, string> $slug
 * @property Carbon|null $scheduled_at
 * @property bool $status
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon|null $deleted_at
 *
 * @method static NewsletterFactory newFactory()
 * @method static Builder<Newsletter> scheduled()
 * @method static Builder<Newsletter> enabled()
 * @method static Builder<Newsletter> disabled()
 * @method bool isEnabled()
 * @method HasMany<NewsletterPost, $this> newsletterPosts()
 * @method BelongsToMany<NewsletterSubscriber, $this, NewsletterSubscription> newsletterSubscribers()
 * @method BelongsToMany<NewsletterSubscriber, $this, NewsletterSubscription> newsletterSubscribedUsers()
 * @method BelongsToMany<NewsletterSubscriber, $this, NewsletterSubscription> newsletterUnsubscribedUsers()
 * @method HasMany<NewsletterSendHistory, $this> newsletterSendHistories()
 * @method HasManyThrough<NewsletterSendHistoryPost, NewsletterSendHistory, $this> newsletterSendHistoryPosts()
 * @method HasManyThrough<NewsletterSendHistorySubscriber, NewsletterSendHistory, $this> newsletterSendHistorySubscribers()
 * @method LogOptions getActivitylogOptions()
 * @method SlugOptions getSlugOptions()
 */
#[ObservedBy([NewsletterObserver::class])]
final class Newsletter extends Model
{
    use BelongsToTenant;
    /** @use HasFactory<NewsletterFactory> */
    use HasFactory;
    use HasTranslatableSlug;
    use HasTranslations;
    use LogsActivity;
    use SoftDeletes;

    /**
     * @var list<string>
     */
    public array $translatable = ['name', 'description', 'slug'];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'id'           => 'integer',
        'tenant_id'    => 'integer',
        'name'         => 'array',
        'description'  => 'array',
        'slug'         => 'array',
        'scheduled_at' => 'datetime',
        'status'       => 'boolean',
    ];

    /**
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'description',
        'slug',
        'scheduled_at',
        'status',
    ];

    /**
     * @var list<string>
     */
    protected $hidden = [
        'tenant_id',
    ];

    protected static function newFactory(): NewsletterFactory
    {
        return NewsletterFactory::new();
    }

    /**
     * @param Builder<Newsletter> $query
     * @return Builder<Newsletter>
     */
    #[Scope]
    protected function scheduled(Builder $query): Builder
    {
        return $query->whereNotNull('scheduled_at');
    }

    /**
     * @param Builder<Newsletter> $query
     * @return Builder<Newsletter>
     */
    #[Scope]
    protected function enabled(Builder $query): Builder
    {
        return $query->where('status', true);
    }

    /**
     * @param Builder<Newsletter> $query
     * @return Builder<Newsletter>
     */
    #[Scope]
    protected function disabled(Builder $query): Builder
    {
        return $query->where('status', false);
    }

    public function isEnabled(): bool
    {
        return $this->status;
    }

    /**
     * @return HasMany<NewsletterPost, $this>
     */
    public function newsletterPosts(): HasMany
    {
        return $this->hasMany(NewsletterPost::class);
    }

    /**
     * @return BelongsToMany<NewsletterSubscriber, $this, NewsletterSubscription>
     */
    public function newsletterSubscribers(): BelongsToMany
    {
        return $this->belongsToMany(NewsletterSubscriber::class, 'newsletter_subscription')
            ->using(NewsletterSubscription::class)
            ->withPivot(['subscribed_at', 'unsubscribed_at']);
    }

    /**
     * @return BelongsToMany<NewsletterSubscriber, $this, NewsletterSubscription>
     */
    public function newsletterSubscribedUsers(): BelongsToMany
    {
        return $this->newsletterSubscribers()->wherePivotNull('unsubscribed_at');
    }

    /**
     * @return BelongsToMany<NewsletterSubscriber, $this, NewsletterSubscription>
     */
    public function newsletterUnsubscribedUsers(): BelongsToMany
    {
        return $this->newsletterSubscribers()->wherePivotNotNull('unsubscribed_at');
    }

    /**
     * @return HasMany<NewsletterSendHistory, $this>
     */
    public function newsletterSendHistories(): HasMany
    {
        return $this->hasMany(NewsletterSendHistory::class);
    }

    /**
     * @return HasManyThrough<NewsletterSendHistoryPost, NewsletterSendHistory, $this>
     */
    public function newsletterSendHistoryPosts(): HasManyThrough
    {
        return $this->hasManyThrough(NewsletterSendHistoryPost::class, NewsletterSendHistory::class);
    }

    /**
     * @return HasManyThrough<NewsletterSendHistorySubscriber, NewsletterSendHistory, $this>
     */
    public function newsletterSendHistorySubscribers(): HasManyThrough
    {
        return $this->hasManyThrough(NewsletterSendHistorySubscriber::class, NewsletterSendHistory::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->logExcept(['id']);
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug')
            ->preventOverwrite();
    }
};
