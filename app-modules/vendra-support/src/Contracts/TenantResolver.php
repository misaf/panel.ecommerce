<?php

declare(strict_types=1);

namespace Misaf\VendraSupport\Contracts;

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
     * @return array<int, string>
     */
    public function searchOptions(string $value, int $limit = 10): array;
}
