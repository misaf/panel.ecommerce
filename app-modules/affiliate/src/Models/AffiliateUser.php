<?php

declare(strict_types=1);

namespace Misaf\Affiliate\Models;

use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Misaf\Affiliate\Database\Factories\AffiliateUserFactory;
use Misaf\Affiliate\Observers\AffiliateUserObserver;
use Misaf\Tenant\Traits\BelongsToTenant;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property int $id
 * @property int $tenant_id
 * @property int $affiliate_id
 * @property int $user_id
 * @property int $commission_earned
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 */
#[ObservedBy([AffiliateUserObserver::class])]
final class AffiliateUser extends Model
{
    use BelongsToTenant;
    /** @use HasFactory<AffiliateUserFactory> */
    use HasFactory;
    use LogsActivity;
    use \Misaf\Affiliate\Traits\BelongsToAffiliate;
    use \Misaf\User\Traits\BelongsToUser;
    use SoftDeletes;

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'id'                => 'integer',
        'tenant_id'         => 'integer',
        'affiliate_id'      => 'integer',
        'user_id'           => 'integer',
        'commission_earned' => 'integer',
    ];

    /**
     * @var list<string>
     */
    protected $fillable = [
        'affiliate_id',
        'user_id',
        'commission_earned',
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
