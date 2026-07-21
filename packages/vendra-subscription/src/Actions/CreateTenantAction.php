<?php

declare(strict_types=1);

namespace Misaf\VendraSubscription\Actions;

use Illuminate\Support\Facades\DB;
use Misaf\VendraSubscription\Models\Account;
use Misaf\VendraSubscription\Support\WebsiteQuota;
use Misaf\VendraTenant\Models\Tenant;
use Misaf\VendraUser\Actions\CreateUserAction;
use Misaf\VendraUser\Models\User;

final class CreateTenantAction
{
    public function __construct(
        private readonly CreateUserAction $createUserAction,
        private readonly WebsiteQuota $websiteQuota,
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
        ?Account $account = null,
    ): array {
        return DB::transaction(function () use (
            $name,
            $domain,
            $username,
            $email,
            $password,
            $account,
        ): array {
            $isFirstWebsite = false;

            if (null !== $account) {
                $this->websiteQuota->assertCanCreateWebsite($account);
                $isFirstWebsite = 0 === $account->tenants()->count();
            }

            $createdTenant = Tenant::query()->create([
                'account_id' => $account?->getKey(),
                'name'       => $name,
                'slug'       => $name,
                'status'     => true,
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

            // The owner of an account's first website operates the whole account.
            if ($isFirstWebsite && null !== $account) {
                $createdUser->forceFill(['account_id' => $account->getKey()])->save();
            }

            return [
                'tenant' => $createdTenant,
                'user'   => $createdUser,
            ];
        });
    }
}
