<?php

declare(strict_types=1);

namespace Misaf\VendraSupport\Support;

use Closure;
use Illuminate\Database\Eloquent\Model;
use Misaf\VendraSupport\Contracts\TenantResolver;

final class NullTenantResolver implements TenantResolver
{
    public function available(): bool
    {
        return false;
    }

    public function current(): ?Model
    {
        return null;
    }

    public function currentId(): ?int
    {
        return null;
    }

    public function modelClass(): string
    {
        return Model::class;
    }

    public function findByKeyOrSlug(int|string $tenant): ?Model
    {
        return null;
    }

    public function makeCurrent(Model|int|string $tenant): bool
    {
        return false;
    }

    public function execute(Model|int|string $tenant, Closure $callback): mixed
    {
        return $callback();
    }

    /**
     * @return array<int, string>
     */
    public function searchOptions(string $value, int $limit = 10): array
    {
        return [];
    }
}
