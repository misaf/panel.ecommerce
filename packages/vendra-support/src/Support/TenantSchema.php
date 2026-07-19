<?php

declare(strict_types=1);

namespace Misaf\VendraSupport\Support;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Misaf\VendraSupport\Contracts\TenantResolver;
use Throwable;

final class TenantSchema
{
    /**
     * @var array<string, bool>
     */
    private static array $tenantColumnCache = [];

    public static function enabled(): bool
    {
        return app(TenantResolver::class)->available();
    }

    public static function addTenantColumn(Blueprint $table): void
    {
        if ( ! self::enabled()) {
            return;
        }

        $table->unsignedBigInteger('tenant_id');
    }

    public static function addTenantIndex(Blueprint $table): void
    {
        if ( ! self::enabled()) {
            return;
        }

        $table->index('tenant_id');
    }

    /**
     * @param string|list<string> $columns
     *
     * @return list<string>
     */
    public static function tenantIndex(string|array $columns): array
    {
        $columns = is_array($columns) ? $columns : [$columns];

        if ( ! self::enabled()) {
            return $columns;
        }

        return array_values(array_unique(['tenant_id', ...$columns]));
    }

    public static function hasTenantColumn(string $table): bool
    {
        if (array_key_exists($table, self::$tenantColumnCache)) {
            return self::$tenantColumnCache[$table];
        }

        try {
            return self::$tenantColumnCache[$table] = Schema::hasColumn($table, 'tenant_id');
        } catch (Throwable) {
            return false;
        }
    }

    public static function forgetTenantColumn(string $table): void
    {
        unset(self::$tenantColumnCache[$table]);
    }
}
