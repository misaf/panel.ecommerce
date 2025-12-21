<?php

declare(strict_types=1);

namespace Misaf\User\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasName;
use Filament\Panel;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Translation\HasLocalePreference;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use Misaf\Affiliate\Traits\HasAffiliate;
use Misaf\AuthifyLog\Traits\HasAuthifyLog;
use Misaf\Tenant\Models\Tenant;
use Misaf\Tenant\Traits\BelongsToTenant;
use Misaf\Transaction\Traits\HasTransaction;
use Misaf\User\Database\Factories\UserFactory;
use Misaf\User\Observers\UserObserver;
use Misaf\User\Traits\HasUserProfile;
use Misaf\UserLevel\Traits\HasUserLevelHistory;
use Misaf\UserMessenger\Traits\HasUserMessenger;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Tags\HasTags;

/**
 * @property int $id
 * @property int $tenant_id
 * @property string $username
 * @property string $email
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $password_fingerprint
 * @property string|null $remember_token
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon|null $deleted_at
 *
 * @method static UserFactory<User> newFactory()
 */
#[ObservedBy([UserObserver::class])]
final class User extends Authenticatable implements
    FilamentUser,
    HasLocalePreference,
    HasName,
    MustVerifyEmail
{
    use BelongsToTenant;
    use HasAffiliate;
    use HasApiTokens;
    use HasAuthifyLog;
    /** @use HasFactory<UserFactory> */
    use HasFactory;
    use HasRoles;
    use HasTags;
    use HasTransaction;
    use HasUserLevelHistory;
    use HasUserMessenger;
    use HasUserProfile;
    use LogsActivity;
    use Notifiable;
    use SoftDeletes;

    /**
     * Create a new factory instance for the model.
     *
     * @return UserFactory<User>
     */
    protected static function newFactory()
    {
        return UserFactory::new();
    }

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'id'                   => 'integer',
        'tenant_id'            => 'integer',
        'username'             => 'string',
        'email'                => 'string',
        'email_verified_at'    => 'datetime',
        'password'             => 'string',
        'password_fingerprint' => 'string',
        'remember_token'       => 'string',
    ];

    /**
     * @var list<string>
     */
    protected $fillable = [
        'tenant_id',
        'username',
        'email',
        'email_verified_at',
        'password',
        'password_fingerprint',
    ];

    /**
     * @var list<string>
     */
    protected $hidden = [
        'tenant_id',
        'password',
        'password_fingerprint',
        'remember_token',
    ];

    /**
     * The guard that should be used for authentication.
     *
     * @var string
     */
    protected $guard_name = 'web';

    /**
     * @param Panel $panel
     * @return bool
     */
    public function canAccessPanel(Panel $panel): bool
    {
        return match ($panel->getId()) {
            'panel-misaf-admin'      => $this->hasRole('super-admin') || $this->hasRole('admin'),
            'panel-misaf-reseller'   => $this->hasAnyRole(['super-admin', 'admin', 'reseller']),
            default                  => true,
        };
    }

    /**
     * @return string
     */
    public function getFilamentName(): string
    {
        return $this->username ?? $this->email;
    }

    /**
     * @return BelongsToMany<Tenant, $this>
     */
    public function tenants(): BelongsToMany
    {
        return $this->belongsToMany(Tenant::class);
    }

    /**
     * @return Attribute<string, string>
     */
    protected function email(): Attribute
    {
        return Attribute::make(
            set: fn(string $value) => Str::lower(mb_trim($value)),
        );
    }

    /**
     * @return string
     */
    public function preferredLocale(): string
    {
        return 'fa';
    }

    /**
     * @return LogOptions
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->logExcept(['id']);
    }
}
