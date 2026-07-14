<?php

declare(strict_types=1);

namespace Misaf\VendraSupport\Contracts;

use Closure;
use Illuminate\Database\Eloquent\Model;

interface TenantResolver
{
    public function available(): bool;

    public function current(): ?Model;

    public function currentId(): ?int;

    /**
     * @return class-string<Model>
     */
    public function modelClass(): string;

    public function findByKeyOrSlug(int|string $tenant): ?Model;

    public function makeCurrent(Model|int|string $tenant): bool;

    /**
     * Run the callback within the given tenant's context, restoring the
     * previous context afterwards. Runs the callback as-is when tenancy
     * is disabled.
     */
    public function execute(Model|int|string $tenant, Closure $callback): mixed;

    /**
     * Run the callback once within each tenant's context, restoring the
     * previous context afterwards. Runs the callback once with no tenant
     * context when tenancy is disabled.
     */
    public function eachTenant(Closure $callback): void;

    /**
     * @return array<int, string>
     */
    public function searchOptions(string $value, int $limit = 10): array;
}
