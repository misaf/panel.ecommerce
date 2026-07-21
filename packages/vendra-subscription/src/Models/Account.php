<?php

declare(strict_types=1);

namespace Misaf\VendraSubscription\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Misaf\VendraSubscription\Database\Factories\AccountFactory;
use Misaf\VendraSubscription\Enums\SubscriptionStatus;
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
#[UseFactory(AccountFactory::class)]
final class Account extends Model implements ShouldLogActivity
{
    /** @use HasFactory<AccountFactory> */
    use HasFactory;

    use HasSlug;
    use Notifiable;
    use SoftDeletes;

    /**
     * Cascade offboarding: when an account is deleted its websites (and their
     * domains) are soft-deleted and any active subscription is cancelled, so no
     * orphaned websites keep resolving.
     */
    protected static function booted(): void
    {
        static::deleting(function (Account $account): void {
            $account->offboardWebsites();
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
     * Route mail notifications to the account owner's email.
     */
    public function routeNotificationForMail(): ?string
    {
        return $this->owner_email;
    }

    /**
     * Whether the account has an owner contact to notify.
     */
    public function hasOwnerContact(): bool
    {
        return null !== $this->owner_email;
    }

    /**
     * @param  Builder<Account>  $query
     * @return Builder<Account>
     */
    public function scopeEnabled(Builder $query): Builder
    {
        return $query->where('status', true);
    }

    /**
     * @param  Builder<Account>  $query
     * @return Builder<Account>
     */
    public function scopeDisabled(Builder $query): Builder
    {
        return $query->where('status', false);
    }

    /**
     * The websites (tenants) owned by this account.
     *
     * @return HasMany<Tenant, $this>
     */
    public function tenants(): HasMany
    {
        return $this->hasMany(Tenant::class);
    }

    /**
     * @return HasMany<Subscription, $this>
     */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * The user who operates this account (owner of its first website).
     *
     * @return HasOne<User, $this>
     */
    public function ownerUser(): HasOne
    {
        return $this->hasOne(User::class, 'account_id');
    }

    /**
     * The account's currently active subscription, if any.
     */
    public function activeSubscription(): ?Subscription
    {
        return $this->subscriptions()
            ->active()
            ->latest('starts_at')
            ->first();
    }

    /**
     * Whether the account's active plan grants the given feature entitlement.
     */
    public function allows(string $feature): bool
    {
        return (bool) $this->activeSubscription()?->plan->allows($feature);
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug')
            ->preventOverwrite();
    }

    /**
     * Soft-delete the account's websites and their domains, and cancel any
     * active subscription.
     */
    private function offboardWebsites(): void
    {
        $this->subscriptions()
            ->where('status', SubscriptionStatus::Active->value)
            ->update(['status' => SubscriptionStatus::Cancelled->value]);

        $this->tenants()->get()->each(function (Tenant $tenant): void {
            $tenant->delete();
        });
    }
}
