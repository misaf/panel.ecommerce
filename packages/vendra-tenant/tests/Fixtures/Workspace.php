<?php

declare(strict_types=1);

namespace Misaf\VendraTenant\Tests\Fixtures;

use Illuminate\Database\Eloquent\Builder;
use Misaf\VendraTenant\Concerns\IsTenantModel;
use Misaf\VendraTenant\Contracts\TenantContract;
use Spatie\Multitenancy\Models\Tenant as SpatieTenant;

/**
 * A tenant model this engine has never heard of.
 *
 * The suite drives the engine through a `Workspace` owning `workspace_id`
 * columns rather than through Vendra's own Store, which is the point: if the
 * tests pass against a model with a different name, a different table and a
 * different foreign key, nothing ecommerce-specific has leaked back into
 * `misaf/vendra-tenant`.
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property bool $active
 */
final class Workspace extends SpatieTenant implements TenantContract
{
    use IsTenantModel;

    protected $table = 'workspaces';

    protected $guarded = [];

    public $timestamps = false;

    /**
     * @param  Builder<Workspace>  $query
     * @return Builder<Workspace>
     */
    public function scopeAccessible(Builder $query): Builder
    {
        return $query->where('active', true);
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'id'     => 'integer',
            'active' => 'boolean',
        ];
    }
}
