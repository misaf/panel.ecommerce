<?php

declare(strict_types=1);

namespace Misaf\VendraTenant\Support;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;
use Misaf\VendraSupport\Contracts\TenantResolver;
use Misaf\VendraTenant\Contracts\TenantContract;
use RuntimeException;

/**
 * The tenant resolver every tenant-agnostic package talks to.
 *
 * It knows no concrete model: both the class and the foreign key come from
 * `config/vendra-tenant.php`, so the same engine drives `Store`/`tenant_id`
 * here and `Company`/`company_id` in another application.
 */
final class ConfiguredTenantResolver implements TenantResolver
{
    public function available(): bool
    {
        return true;
    }

    public function current(): ?Model
    {
        $tenant = $this->tenantModelClass()::current();

        return $tenant instanceof Model ? $tenant : null;
    }

    public function currentId(): ?int
    {
        $tenant = $this->current();

        return $tenant instanceof TenantContract ? $tenant->getTenantKey() : null;
    }

    /**
     * @return class-string<Model>
     */
    public function modelClass(): string
    {
        return $this->tenantModelClass();
    }

    public function foreignKey(): string
    {
        return config()->string('vendra-tenant.foreign_key');
    }

    public function findByKeyOrSlug(int|string $tenant): ?Model
    {
        return $this->query()
            ->whereKey($tenant)
            ->orWhere('slug', $tenant)
            ->first();
    }

    public function makeCurrent(Model|int|string $tenant): bool
    {
        if (is_int($tenant) || is_string($tenant)) {
            $tenant = $this->findByKeyOrSlug($tenant);
        }

        if ( ! $tenant instanceof TenantContract) {
            return false;
        }

        $tenant->makeCurrent();

        return true;
    }

    public function execute(Model|int|string $tenant, Closure $callback): mixed
    {
        if (is_int($tenant) || is_string($tenant)) {
            $tenant = $this->findByKeyOrSlug($tenant);
        }

        if ( ! $tenant instanceof TenantContract) {
            throw new RuntimeException('The given tenant could not be resolved.');
        }

        return $tenant->execute($callback);
    }

    public function eachTenant(Closure $callback): void
    {
        $this->query()
            ->cursor()
            ->each(function (Model $tenant) use ($callback): void {
                if ($tenant instanceof TenantContract) {
                    $tenant->execute($callback);
                }
            });
    }

    /**
     * @return array<int, string>
     */
    public function searchOptions(string $value, int $limit = 10): array
    {
        $search = mb_trim($value);

        $query = $this->query()->select(['id', 'slug']);

        /*
         | "May this tenant serve requests?" is the concrete model's business, so
         | the engine applies its `accessible` scope when it defines one and
         | lists every tenant when it does not.
         */
        if ($this->hasScope('accessible')) {
            $query->scopes('accessible');
        }

        if ('' !== $search) {
            $query->where('slug', 'like', "%{$search}%");
        }

        $options = [];

        foreach ($query->limit($limit)->get() as $tenant) {
            if ($tenant instanceof TenantContract) {
                $options[$tenant->getTenantKey()] = $tenant->getTenantSlug();
            }
        }

        return $options;
    }

    /**
     * The configured tenant model, validated once at the point of use so a
     * misconfiguration reads as a configuration error rather than a fatal on
     * some unrelated static call.
     *
     * @return class-string<Model&TenantContract>
     */
    private function tenantModelClass(): string
    {
        $modelClass = config('vendra-tenant.model');

        if ( ! is_string($modelClass) || ! is_a($modelClass, Model::class, true) || ! is_a($modelClass, TenantContract::class, true)) {
            throw new InvalidArgumentException(sprintf(
                'Configure [vendra-tenant.model] with an Eloquent model implementing [%s]; [%s] given.',
                TenantContract::class,
                is_string($modelClass) ? $modelClass : get_debug_type($modelClass),
            ));
        }

        return $modelClass;
    }

    /**
     * @return Builder<Model>
     */
    private function query(): Builder
    {
        return $this->tenantModelClass()::query();
    }

    /**
     * Business availability ("may this tenant currently serve requests?") lives
     * on the concrete model, so the engine uses the scope when the application
     * defines one and lists every tenant when it does not.
     */
    private function hasScope(string $scope): bool
    {
        return method_exists($this->tenantModelClass(), 'scope' . ucfirst($scope));
    }
}
