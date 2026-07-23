<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\ResellerFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;
use Misaf\VendraSubscription\Contracts\SubscriptionSubscriber;
use Misaf\VendraSubscription\Enums\SubscriptionPaymentStatus;
use Misaf\VendraSubscription\Enums\SubscriptionStatus;
use Misaf\VendraSubscription\Models\Subscription;
use Misaf\VendraSubscription\Models\SubscriptionPayment;
use Misaf\VendraSupport\Contracts\ShouldLogActivity;
use Misaf\VendraTenant\Models\Tenant;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property string $slug
 * @property bool $status
 * @property string|null $email
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon|null $deleted_at
 */
#[Fillable(['name', 'description', 'slug', 'status', 'email'])]
#[UseFactory(ResellerFactory::class)]
final class Reseller extends Model implements ShouldLogActivity, SubscriptionSubscriber
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
            'email'       => 'string',
        ];
    }

    /**
     * Route mail notifications to the reseller owner's email.
     */
    public function routeNotificationForMail(): ?string
    {
        return $this->email;
    }

    /**
     * Whether the reseller has an owner contact to notify.
     */
    public function hasOwnerContact(): bool
    {
        return null !== $this->email;
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

    public function latestSubscription(): ?Subscription
    {
        return $this->subscriptions()->latest('starts_at')->first();
    }

    public function hasSubscriptions(): bool
    {
        return $this->subscriptions()->exists();
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function createSubscription(array $attributes): Subscription
    {
        return $this->subscriptions()->create($attributes);
    }

    public function cancelActiveSubscriptions(?int $exceptKey = null): int
    {
        $query = $this->subscriptions()->where('status', SubscriptionStatus::Active->value);

        if (null !== $exceptKey) {
            $query->whereKeyNot($exceptKey);
        }

        return $query->update(['status' => SubscriptionStatus::Cancelled->value]);
    }

    /**
     * @param  array<int, int>  $keys
     */
    public function cancelPendingPaymentSubscriptions(array $keys): int
    {
        return $this->subscriptions()
            ->whereKey($keys)
            ->where('status', SubscriptionStatus::PendingPayment->value)
            ->update(['status' => SubscriptionStatus::Cancelled->value]);
    }

    /**
     * @return Collection<int, SubscriptionPayment>
     */
    public function lockOpenSubscriptionPayments(): Collection
    {
        return SubscriptionPayment::query()
            ->whereIn('subscription_id', $this->subscriptions()->select('id'))
            ->whereIn('status', [
                SubscriptionPaymentStatus::Pending,
                SubscriptionPaymentStatus::Processing,
                SubscriptionPaymentStatus::RequiresAction,
                SubscriptionPaymentStatus::NeedsReconciliation,
            ])
            ->lockForUpdate()
            ->get();
    }

    /**
     * @return HasOne<ResellerUser, $this>
     */
    public function ownerUser(): HasOne
    {
        return $this->hasOne(ResellerUser::class);
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

    public function isSubscriptionEnabled(): bool
    {
        return $this->status;
    }

    public function notifyOwner(Notification $notification): void
    {
        $this->notify($notification);
    }

    public function subscriptionPayer(): ?ResellerUser
    {
        return $this->ownerUser()->first();
    }

    public function subscribedPropertyCount(): int
    {
        return $this->tenants()->count();
    }

    public function activeSubscribedPropertyCount(): int
    {
        return $this->tenants()->where('status', true)->count();
    }

    public function suspendActiveProperties(): int
    {
        return $this->tenants()->where('status', true)->update(['status' => false]);
    }

    public function reactivateSuspendedProperties(): int
    {
        return $this->tenants()->where('status', false)->update(['status' => true]);
    }

    public function lockForSubscription(): static
    {
        return static::query()
            ->whereKey($this->getKey())
            ->lockForUpdate()
            ->firstOrFail();
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
