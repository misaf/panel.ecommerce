<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\ResellerFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Misaf\VendraSubscription\Enums\SubscriptionStatus;
use Misaf\VendraSubscription\Models\Subscription;
use Misaf\VendraSupport\Contracts\ShouldLogActivity;
use Misaf\VendraTenant\Models\Tenant;
use Misaf\VendraUser\Models\User;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property string $slug
 * @property bool $status
 * @property string|null $owner_name
 * @property string|null $owner_email
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon|null $deleted_at
 */
#[Fillable(['name', 'description', 'slug', 'status', 'owner_name', 'owner_email'])]
#[UseFactory(ResellerFactory::class)]
final class Reseller extends Model implements ShouldLogActivity
{
    /** @use HasFactory<ResellerFactory> */
    use HasFactory;

    use HasSlug;
    use Notifiable;
    use SoftDeletes;

    /**
     * Cascade offboarding: when a reseller is deleted its properties (and their
     * domains) are soft-deleted and any active subscription is cancelled, so no
     * orphaned properties keep resolving.
     */
    protected static function booted(): void
    {
        static::deleting(function (Reseller $reseller): void {
            $reseller->offboardProperties();
        });
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'id'          => 'integer',
            'name'        => 'string',
            'description' => 'string',
            'slug'        => 'string',
            'status'      => 'boolean',
            'owner_name'  => 'string',
            'owner_email' => 'string',
        ];
    }

    /**
     * Route mail notifications to the reseller owner's email.
     */
    public function routeNotificationForMail(): ?string
    {
        return $this->owner_email;
    }

    /**
     * Whether the reseller has an owner contact to notify.
     */
    public function hasOwnerContact(): bool
    {
        return null !== $this->owner_email;
    }

    /**
     * @param  Builder<Reseller>  $query
     * @return Builder<Reseller>
     */
    public function scopeEnabled(Builder $query): Builder
    {
        return $query->where('status', true);
    }

    /**
     * @param  Builder<Reseller>  $query
     * @return Builder<Reseller>
     */
    public function scopeDisabled(Builder $query): Builder
    {
        return $query->where('status', false);
    }

    /**
     * The properties (tenants) owned by this reseller.
     *
     * @return HasMany<Tenant, $this>
     */
    public function tenants(): HasMany
    {
        return $this->hasMany(Tenant::class);
    }

    /**
     * @return MorphMany<Subscription, $this>
     */
    public function subscriptions(): MorphMany
    {
        return $this->morphMany(Subscription::class, 'subscriber');
    }

    /**
     * The user who operates this reseller (owner of its first property).
     *
     * @return HasOne<User, $this>
     */
    public function ownerUser(): HasOne
    {
        return $this->hasOne(User::class, 'reseller_id');
    }

    /**
     * The reseller's currently active subscription, if any.
     */
    public function activeSubscription(): ?Subscription
    {
        return $this->subscriptions()
            ->active()
            ->latest('starts_at')
            ->first();
    }

    /**
     * Whether the reseller's active plan grants the given feature entitlement.
     */
    public function allows(string $feature): bool
    {
        return $this->activeSubscription()?->plan?->allows($feature) ?? false;
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug')
            ->preventOverwrite();
    }

    /**
     * Soft-delete the reseller's properties and their domains, and cancel any
     * active subscription.
     */
    private function offboardProperties(): void
    {
        $this->subscriptions()
            ->where('status', SubscriptionStatus::Active->value)
            ->update(['status' => SubscriptionStatus::Cancelled->value]);

        $this->tenants()->get()->each(function (Tenant $tenant): void {
            $tenant->delete();
        });
    }
}
