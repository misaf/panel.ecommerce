<?php

declare(strict_types=1);

namespace Misaf\AuthifyLog\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Misaf\AuthifyLog\Database\Factories\AuthifyLogFactory;
use Misaf\AuthifyLog\Enums\AuthifyLogActionEnum;
use Misaf\Tenant\Traits\BelongsToTenant;
use Misaf\User\Traits\BelongsToUser;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property int $id
 * @property int $tenant_id
 * @property int $user_id
 * @property AuthifyLogActionEnum $action
 * @property string $ip_address
 * @property string $ip_country
 * @property string $user_agent
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
final class AuthifyLog extends Model
{
    use BelongsToTenant;
    use BelongsToUser;
    /** @use HasFactory<AuthifyLogFactory> */
    use HasFactory;
    use LogsActivity;

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'id'         => 'integer',
        'tenant_id'  => 'integer',
        'user_id'    => 'integer',
        'action'     => AuthifyLogActionEnum::class,
        'ip_address' => 'string',
        'ip_country' => 'string',
        'user_agent' => 'string',
    ];

    /**
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'action',
        'ip_address',
        'ip_country',
        'user_agent',
    ];

    /**
     * @var list<string>
     */
    protected $hidden = [
        'tenant_id',
    ];

    /**
     * @return LogOptions
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->logExcept(['id']);
    }
}
