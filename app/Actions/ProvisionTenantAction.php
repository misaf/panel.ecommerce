<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Reseller;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Misaf\VendraPermission\Actions\CreateRoleAction;
use Misaf\VendraSupport\Events\TenantProvisioned;
use Misaf\VendraTenant\Jobs\CacheTenantRoutesJob;
use Misaf\VendraTenant\Models\Tenant;
use Misaf\VendraUser\Models\User;
use Spatie\Permission\Guard;

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
    public function execute(array $data, bool $shouldSeed = false, ?string $password = null, ?Reseller $reseller = null): array
    {
        $password ??= Str::password(length: 8, letters: true, numbers: true, symbols: false);

        $result = DB::transaction(function () use ($data, $password, $reseller): array {
            $result = $this->createTenantAction->execute(
                name: $data['name'],
                domain: $data['domain'],
                username: $data['username'],
                email: $data['email'],
                password: $password,
                reseller: $reseller,
            );

            $role = $this->createRoleAction->execute(
                tenant: $result['tenant'],
                name: Config::string('vendra-permission.super_admin_role'),
                guardName: Guard::getDefaultName(User::class),
            );

            $result['tenant']->execute(fn() => $result['user']->assignRole($role));

            return [
                ...$result,
                'password' => $password,
            ];
        });

        event(new TenantProvisioned($result['tenant'], $shouldSeed));

        CacheTenantRoutesJob::dispatch($result['tenant']->id);

        return $result;
    }
}
