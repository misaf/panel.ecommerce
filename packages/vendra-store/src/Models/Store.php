<?php

declare(strict_types=1);

namespace Misaf\VendraStore\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Laravel\Pennant\Concerns\HasFeatures;
use Misaf\VendraStore\Database\Factories\StoreFactory;
use Misaf\VendraStore\Observers\StoreObserver;
use Misaf\VendraStore\Scopes\StoreScope;
use Misaf\VendraSupport\Contracts\ShouldLogActivity;
use Misaf\VendraTenant\Concerns\IsTenantModel;
use Misaf\VendraTenant\Contracts\TenantContract;
use Misaf\VendraTenant\Enums\TenantProvisioningStatus;
use Spatie\Multitenancy\Models\Tenant as SpatieTenant;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * A store: the ecommerce business entity, and the tenancy boundary.
 *
 * The Store *is* the tenant — it implements {@see TenantContract} rather than
 * pointing at a separate tenant row — so `stores` is the only table describing
 * it. Products, orders, customers and vendors all live inside one store; a
 * reseller may own several, and the platform console may own one directly
 * (`reseller_id` is nullable).
 *
 * Reusable domain packages stay tenant-agnostic and are owned through the
 * neutral `tenant_id` column, which resolves to a store because a store is what
 * plays the tenant role here. Only records describing the store itself — its
 * domains, its storefront deployment — name it outright with `store_id`.
 *
 * The reseller link is deliberately a bare key here: the reseller domain is a
 * layer above the store, and `misaf/vendra-reseller` supplies both sides of the
 * relationship so the store package stays installable without it.
 *
 * @property int $id
 * @property int|null $reseller_id
 * @property string $name
 * @property string $description
 * @property string $slug
 * @property bool $active
 * @property Carbon|null $billing_suspended_at
 * @property TenantProvisioningStatus $provisioning_status
 * @property bool $provisioning_should_seed
 * @property Carbon|null $provisioning_seeded_at
 * @property Carbon|null $routes_cached_at
 * @property Carbon|null $provisioned_at
 * @property Carbon|null $provisioning_failed_at
 * @property string|null $provisioning_error
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon|null $deleted_at
 */
#[Fillable(['reseller_id', 'name', 'description', 'slug', 'active', 'provisioning_status', 'provisioning_should_seed'])]
#[ObservedBy([StoreObserver::class])]
#[UseFactory(StoreFactory::class)]
final class Store extends SpatieTenant implements ShouldLogActivity, TenantContract
{
    /** @use HasFactory<StoreFactory> */
    use HasFactory;

    use HasFeatures;

    use HasSlug;
    use IsTenantModel;
    use SoftDeletes;

    /**
     * Cascade a store's domains through its own lifecycle so no orphaned domain
     * keeps resolving. The active domain (active = true) follows the store;
     * replaced history domains (active = false, already trashed) are left
     * untouched on soft delete and only purged on force delete. Each callback
     * runs in the store's own tenant context so {@see StoreScope} on the
     * domains resolves to this store, not whatever store is currently active in
     * the request.
     */

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'id'                       => 'integer',
            'reseller_id'              => 'integer',
            'name'                     => 'string',
            'description'              => 'string',
            'slug'                     => 'string',
            'active'                   => 'boolean',
            'billing_suspended_at'     => 'datetime',
            'provisioning_status'      => TenantProvisioningStatus::class,
            'provisioning_should_seed' => 'boolean',
            'provisioning_seeded_at'   => 'datetime',
            'routes_cached_at'         => 'datetime',
            'provisioned_at'           => 'datetime',
            'provisioning_failed_at'   => 'datetime',
            'provisioning_error'       => 'string',
        ];
    }

    /**
     * @param  Builder<Store>  $query
     * @return Builder<Store>
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('active', true);
    }

    /**
     * @param  Builder<Store>  $query
     * @return Builder<Store>
     */
    public function scopeInactive(Builder $query): Builder
    {
        return $query->where('active', false);
    }

    /**
     * Limit the query to stores that may currently serve requests.
     *
     * @param  Builder<Store>  $query
     * @return Builder<Store>
     */
    public function scopeAccessible(Builder $query): Builder
    {
        return $query
            ->active()
            ->whereNull('billing_suspended_at')
            ->where('provisioning_status', TenantProvisioningStatus::Ready);
    }

    /**
     * @return HasMany<StoreDomain, $this>
     */
    public function storeDomains(): HasMany
    {
        return $this->hasMany(StoreDomain::class);
    }

    /**
     * A store's domains are always scoped to itself by the relationship's
     * foreign key, so {@see StoreScope} (which targets the currently active
     * store) must be dropped to read them from another store's context such as
     * the console or reseller panels.
     *
     * @return HasMany<StoreDomain, $this>
     */
    public function domains(): HasMany
    {
        return $this->storeDomains()->withoutGlobalScope(StoreScope::class);
    }

    /**
     * The storefront the platform runs for this store, when one was requested.
     *
     * @return HasMany<StorefrontDeployment, $this>
     */
    public function storefrontDeployments(): HasMany
    {
        return $this->hasMany(StorefrontDeployment::class)
            ->withoutGlobalScope(StoreScope::class);
    }

    /**
     * The name of the store's active (resolving) domain, if any.
     */
    public function activeDomainName(): ?string
    {
        $name = $this->domains()->where('active', true)->value('name');

        return is_string($name) ? $name : null;
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug')
            ->preventOverwrite();
    }
}
