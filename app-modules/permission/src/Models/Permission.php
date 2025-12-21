<?php

declare(strict_types=1);

namespace Misaf\Permission\Models;

use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Misaf\Permission\Database\Factories\PermissionFactory;
use Misaf\Permission\Models\Permission as MisafPermission;
use Misaf\Permission\Observers\PermissionObserver;
use Misaf\Tenant\Traits\BelongsToTenant;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Models\Permission as SpatiePermission;

/**
 * @property int $id
 * @property int $tenant_id
 * @property string $name
 * @property string $guard_name
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 */
#[ObservedBy([PermissionObserver::class])]
final class Permission extends SpatiePermission
{
    use BelongsToTenant;
    /** @use HasFactory<PermissionFactory> */
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
     * @return PermissionFactory<MisafPermission>
     */
    protected static function newFactory()
    {
        return PermissionFactory::new();
    }

    /**
     * @return LogOptions
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->logExcept(['id']);
    }
}
