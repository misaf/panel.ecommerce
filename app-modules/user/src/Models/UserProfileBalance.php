<?php

declare(strict_types=1);

namespace Misaf\User\Models;

use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Misaf\Currency\Traits\BelongsToCurrency;
use Misaf\Currency\Traits\BelongsToCurrencyCategoryThroughCurrency;
use Misaf\Tenant\Traits\BelongsToTenant;
use Misaf\User\Database\Factories\UserProfileBalanceFactory;
use Misaf\User\Observers\UserProfileBalanceObserver;
use Misaf\User\Traits\BelongsToUserProfile;
use Misaf\User\Traits\BelongsToUserThroughUserProfile;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property int $id
 * @property int $tenant_id
 * @property int $user_profile_id
 * @property int $currency_id
 * @property Money $amount
 * @property bool $status
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon|null $deleted_at
 */
#[ObservedBy([UserProfileBalanceObserver::class])]
final class UserProfileBalance extends Model
{
    use BelongsToCurrency;
    use BelongsToCurrencyCategoryThroughCurrency;
    use BelongsToTenant;
    use BelongsToUserProfile;
    use BelongsToUserThroughUserProfile;
    /** @use HasFactory<UserProfileBalanceFactory> */
    use HasFactory;
    use LogsActivity;
    use SoftDeletes;

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'id'              => 'integer',
        'tenant_id'       => 'integer',
        'user_profile_id' => 'integer',
        'currency_id'     => 'integer',
        'amount'          => 'integer',
        'status'          => 'boolean',
    ];

    /**
     * @var list<string>
     */
    protected $fillable = [
        'user_profile_id',
        'currency_id',
        'amount',
        'status',
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
