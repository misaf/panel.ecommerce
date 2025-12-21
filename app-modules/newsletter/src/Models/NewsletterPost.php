<?php

declare(strict_types=1);

namespace Misaf\Newsletter\Models;

use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Misaf\Newsletter\Database\Factories\NewsletterPostFactory;
use Misaf\Newsletter\Enums\NewsletterPostStatusEnum;
use Misaf\Newsletter\Observers\NewsletterPostObserver;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Sluggable\HasTranslatableSlug;
use Spatie\Sluggable\SlugOptions;
use Spatie\Translatable\HasTranslations;

/**
 * @property int $id
 * @property int $newsletter_id
 * @property array<string, string> $name
 * @property array<string, string> $description
 * @property array<string, string> $slug
 * @property NewsletterPostStatusEnum $status
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon|null $deleted_at
 *
 * @method static NewsletterPostFactory newFactory()
 * @method static Builder<NewsletterPost> draft()
 * @method static Builder<NewsletterPost> ready()
 * @method bool isDraft()
 * @method bool isReady()
 * @method BelongsTo<Newsletter, $this> newsletter()
 * @method HasMany<NewsletterSendHistory, $this> newsletterSendHistories()
 * @method LogOptions getActivitylogOptions()
 * @method SlugOptions getSlugOptions()
 */
#[ObservedBy([NewsletterPostObserver::class])]
final class NewsletterPost extends Model
{
    /** @use HasFactory<NewsletterPostFactory> */
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
        'id'            => 'integer',
        'newsletter_id' => 'integer',
        'name'          => 'array',
        'description'   => 'array',
        'slug'          => 'array',
        'status'        => NewsletterPostStatusEnum::class,
    ];

    /**
     * @var list<string>
     */
    protected $fillable = [
        'newsletter_id',
        'name',
        'description',
        'slug',
        'status',
    ];

    protected static function newFactory(): NewsletterPostFactory
    {
        return NewsletterPostFactory::new();
    }

    /**
     * @param Builder<NewsletterPost> $query
     * @return Builder<NewsletterPost>
     */
    #[Scope]
    protected function scopeDraft(Builder $query): Builder
    {
        return $query->where('status', NewsletterPostStatusEnum::DRAFT);
    }

    /**
     * @param Builder<NewsletterPost> $query
     * @return Builder<NewsletterPost>
     */
    #[Scope]
    protected function scopeReady(Builder $query): Builder
    {
        return $query->where('status', NewsletterPostStatusEnum::READY);
    }

    public function isDraft(): bool
    {
        return NewsletterPostStatusEnum::DRAFT === $this->status;
    }

    public function isReady(): bool
    {
        return NewsletterPostStatusEnum::READY === $this->status;
    }

    /**
     * @return BelongsTo<Newsletter, $this>
     */
    public function newsletter(): BelongsTo
    {
        return $this->belongsTo(Newsletter::class);
    }

    /**
     * @return HasMany<NewsletterSendHistory, $this>
     */
    public function newsletterSendHistories(): HasMany
    {
        return $this->hasMany(NewsletterSendHistory::class);
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
}
