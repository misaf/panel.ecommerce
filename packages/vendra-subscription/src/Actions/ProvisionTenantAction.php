<?php

declare(strict_types=1);

namespace Misaf\VendraSubscription\Actions;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Misaf\VendraPermission\Actions\CreateRoleAction;
use Misaf\VendraSubscription\Models\Account;
use Misaf\VendraSupport\Events\TenantProvisioned;
use Misaf\VendraTenant\Jobs\CacheTenantRoutesJob;
use Misaf\VendraTenant\Models\Tenant;
use Misaf\VendraUser\Models\User;

final class ProvisionTenantAction
{
    public function __construct(
        private readonly CreateTenantAction $createTenantAction,
        private readonly CreateRoleAction $createRoleAction,
    ) {}

    /**
     * @param array{
     *     name: string,
     *     domain: string,
     *     username: string,
     *     email: string
     * } $data
     * @return array{tenant: Tenant, user: User, password: string}
     */
    public function execute(array $data, bool $shouldSeed = false, ?string $password = null, ?Account $account = null): array
    {
        $password ??= Str::password(length: 8, letters: true, numbers: true, symbols: false);

        $result = DB::transaction(function () use ($data, $password, $account): array {
            $result = $this->createTenantAction->execute(
                name: $data['name'],
                domain: $data['domain'],
                username: $data['username'],
                email: $data['email'],
                password: $password,
                account: $account,
            );

            $role = $this->createRoleAction->execute(
                tenant: $result['tenant'],
                name: Config::string('vendra-permission.super_admin_role'),
            );

            $result['tenant']->execute(fn() => $result['user']->assignRole($role));

            return [
                ...$result,
                'password' => $password,
            ];
        });

        event(new TenantProvisioned($result['tenant'], $shouldSeed));

        CacheTenantRoutesJob::dispatch($result['tenant']->getKey());

        return $result;
    }
}
