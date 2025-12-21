<?php

declare(strict_types=1);

namespace Misaf\User\Models;

use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Misaf\Tenant\Traits\BelongsToTenant;
use Misaf\User\Database\Factories\UserProfilePhoneFactory;
use Misaf\User\Enums\UserProfilePhoneStatusEnum;
use Misaf\User\Observers\UserProfilePhoneObserver;
use Misaf\User\Services\UserProfilePhoneService;
use Misaf\User\Traits\BelongsToUserProfile;
use Misaf\User\Traits\BelongsToUserThroughUserProfile;
use Propaganistas\LaravelPhone\Casts\RawPhoneNumberCast;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Znck\Eloquent\Traits\BelongsToThrough as TraitBelongsToThrough;

/**
 * @property int $id
 * @property int $tenant_id
 * @property int $user_profile_id
 * @property string $country
 * @property string $phone
 * @property string $phone_normalized
 * @property string $phone_national
 * @property string $phone_e164
 * @property UserProfilePhoneStatusEnum $status
 * @property Carbon|null $verified_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon|null $deleted_at
 */
#[ObservedBy([UserProfilePhoneObserver::class])]
final class UserProfilePhone extends Model
{
    use BelongsToTenant;
    use BelongsToUserProfile;
    use BelongsToUserThroughUserProfile;
    /** @use HasFactory<UserProfilePhoneFactory> */
    use HasFactory;
    use LogsActivity;
    use SoftDeletes;
    use TraitBelongsToThrough;

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'id'               => 'integer',
        'tenant_id'        => 'integer',
        'user_profile_id'  => 'integer',
        'country'          => 'string',
        'phone'            => RawPhoneNumberCast::class . ':country',
        'phone_normalized' => 'string',
        'phone_national'   => 'string',
        'phone_e164'       => 'string',
        'status'           => UserProfilePhoneStatusEnum::class,
        'verified_at'      => 'datetime',
    ];

    /**
     * @var list<string>
     */
    protected $fillable = [
        'user_profile_id',
        'country',
        'phone',
        'phone_normalized',
        'phone_national',
        'phone_e164',
        'status',
        'verified_at',
    ];

    /**
     * @var list<string>
     */
    protected $hidden = [
        'tenant_id',
    ];

    public static function boot(): void
    {
        parent::boot();

        self::saving(function (UserProfilePhone $userProfilePhone): void {
            if ($userProfilePhone->isDirty('phone') && $userProfilePhone->phone) {
                $userProfilePhone->phone_normalized = UserProfilePhoneService::phoneNormalized($userProfilePhone->phone);
                $userProfilePhone->phone_national = UserProfilePhoneService::phoneNational($userProfilePhone->country, $userProfilePhone->phone);
                $userProfilePhone->phone_e164 = UserProfilePhoneService::phoneE164($userProfilePhone->country, $userProfilePhone->phone);
            }

            if ($userProfilePhone->isDirty('status') && UserProfilePhoneStatusEnum::Approved === $userProfilePhone->status) {
                $userProfilePhone->verified_at = Carbon::now();
            }
        });
    }

    /**
     * @return LogOptions
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->logExcept(['id']);
    }
}
