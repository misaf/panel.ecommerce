<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Reseller;
use App\Support\PropertyQuota;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Misaf\VendraTenant\Models\Tenant;
use Misaf\VendraTenant\Models\TenantDomain;
use Misaf\VendraUser\Actions\CreateUserAction;
use Misaf\VendraUser\Models\User;

final class CreateTenantAction
{
    public function __construct(
        private readonly CreateUserAction $createUserAction,
        private readonly PropertyQuota $propertyQuota,
    ) {}

    /**
     * @return array{tenant: Tenant, user: User}
     */
    public function execute(
        string $name,
        string $domain,
        string $username,
        string $email,
        string $password,
        ?Reseller $reseller = null,
    ): array {
        $domain = TenantDomain::normalizeDomain($domain);
        Validator::make(
            ['domain' => $domain],
            ['domain' => TenantDomain::activeDomainRules()],
        )->validate();

        return DB::transaction(function () use (
            $name,
            $domain,
            $username,
            $email,
            $password,
            $reseller,
        ): array {
            $isFirstProperty = false;
            $lockedReseller = null;

            if (null !== $reseller) {
                $lockedReseller = Reseller::query()
                    ->whereKey($reseller->getKey())
                    ->lockForUpdate()
                    ->firstOrFail();

                $this->propertyQuota->assertCanCreateProperty($lockedReseller);
                $isFirstProperty = 0 === $lockedReseller->tenants()->count()
                    && ! $lockedReseller->ownerUser()->exists();
            }

            $createdTenant = Tenant::query()->create([
                'reseller_id' => $lockedReseller?->getKey(),
                'name'        => $name,
                'slug'        => $name,
                'status'      => true,
            ]);

            $createdTenant->execute(fn() => $createdTenant->tenantDomains()->create([
                'name'   => $domain,
                'slug'   => $domain,
                'status' => true,
            ]));

            $createdUser = $this->createUserAction->execute(
                tenant: $createdTenant,
                username: $username,
                email: $email,
                password: $password,
                isVerified: true,
            );

            $createdUser->tenants()->syncWithoutDetaching([$createdTenant->getKey()]);

            // The owner of a reseller's first property operates the whole reseller.
            if ($isFirstProperty && null !== $lockedReseller) {
                $createdUser->forceFill(['reseller_id' => $lockedReseller->getKey()])->save();
            }

            return [
                'tenant' => $createdTenant,
                'user'   => $createdUser,
            ];
        }, attempts: 5);
    }
}
