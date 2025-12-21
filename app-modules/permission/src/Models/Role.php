<?php

declare(strict_types=1);

namespace Misaf\Permission\Models;

use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Misaf\Permission\Database\Factories\RoleFactory;
use Misaf\Permission\Models\Role as MisafRole;
use Misaf\Permission\Observers\RoleObserver;
use Misaf\Tenant\Traits\BelongsToTenant;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Models\Role as SpatieRole;

/**
 * @property int $id
 * @property int $tenant_id
 * @property string $name
 * @property string $guard_name
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 */
#[ObservedBy([RoleObserver::class])]
final class Role extends SpatieRole
{
    use BelongsToTenant;
    /** @use HasFactory<RoleFactory> */
    use HasFactory;
    use LogsActivity;
    use SoftDeletes;

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'id'         => 'integer',
        'tenant_id'  => 'integer',
        'name'       => 'string',
        'guard_name' => 'string',
    ];

    /**
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'guard_name',
    ];

    /**
     * @var list<string>
     */
    protected $hidden = [
        'tenant_id',
    ];

    /**
     * Create a new factory instance for the model.
     *
     * @return RoleFactory<MisafRole>
     */
    protected static function newFactory()
    {
        return RoleFactory::new();
    }

    /**
     * @return LogOptions
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->logExcept(['id']);
    }
}
