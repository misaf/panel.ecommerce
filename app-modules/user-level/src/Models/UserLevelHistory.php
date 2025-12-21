<?php

declare(strict_types=1);

namespace Misaf\UserLevel\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Misaf\Tenant\Traits\BelongsToTenant;
use Misaf\User\Models\User;
use Misaf\UserLevel\Database\Factories\UserLevelHistoryFactory;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Tags\HasTags;

/**
 * @property int $id
 * @property int $tenant_id
 * @property int $user_id
 * @property int $user_level_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
final class UserLevelHistory extends Model
{
    use BelongsToTenant;
    /** @use HasFactory<UserLevelHistoryFactory> */
    use HasFactory;
    use HasTags;
    use LogsActivity;

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'id'            => 'integer',
        'tenant_id'     => 'integer',
        'user_id'       => 'integer',
        'user_level_id' => 'integer',
    ];

    /**
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'user_level_id',
    ];

    /**
     * @var list<string>
     */
    protected $hidden = [
        'tenant_id',
    ];

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo<UserLevel, $this>
     */
    public function userLevel(): BelongsTo
    {
        return $this->belongsTo(UserLevel::class);
    }

    /**
     * @return LogOptions
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->logExcept(['id']);
    }
}
