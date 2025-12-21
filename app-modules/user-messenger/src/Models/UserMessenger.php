<?php

declare(strict_types=1);

namespace Misaf\UserMessenger\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Misaf\Tenant\Traits\BelongsToTenant;
use Misaf\User\Traits\BelongsToUser;
use Misaf\UserMessenger\Database\Factories\UserMessengerFactory;
use Misaf\UserMessenger\Enums\UserMessengerPlatformEnum;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property int $id
 * @property int $tenant_id
 * @property int $user_id
 * @property UserMessengerPlatformEnum $platform
 * @property string $key_name
 * @property string $key_value
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon|null $deleted_at
 */
final class UserMessenger extends Model
{
    use BelongsToTenant;
    use BelongsToUser;
    /** @use HasFactory<UserMessengerFactory> */
    use HasFactory;
    use LogsActivity;
    use SoftDeletes;

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'id'         => 'integer',
        'tenant_id'  => 'integer',
        'user_id'    => 'integer',
        'platform'   => UserMessengerPlatformEnum::class,
        'key_name'   => 'string',
        'key_value'  => 'string',
    ];

    /**
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'platform',
        'key_name',
        'key_value',
    ];

    /**
     * @var list<string>
     */
    protected $hidden = [
        'tenant_id',
    ];

    /**
     * @param Builder<UserMessenger> $query
     * @return void
     */
    public function scopeTelegramChatId(Builder $query): void
    {
        $query->where('platform', UserMessengerPlatformEnum::Telegram)
            ->where('key_name', 'telegram_chat_id');
    }

    /**
     * @return LogOptions
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->logExcept(['id']);
    }
}
