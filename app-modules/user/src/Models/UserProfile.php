<?php

declare(strict_types=1);

namespace Misaf\User\Models;

use App\Traits\ThumbnailTableRecord;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Misaf\Tenant\Traits\BelongsToTenant;
use Misaf\User\Database\Factories\UserProfileFactory;
use Misaf\User\Observers\UserProfileObserver;
use Misaf\User\Traits\BelongsToUser;
use Misaf\User\Traits\HasUserProfileBalance;
use Misaf\User\Traits\HasUserProfileDocument;
use Misaf\User\Traits\HasUserProfilePhone;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * @property int $id
 * @property int $tenant_id
 * @property int $user_id
 * @property string $first_name
 * @property string $last_name
 * @property string $description
 * @property Carbon|null $birthdate
 * @property bool $status
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon|null $deleted_at
 */
#[ObservedBy([UserProfileObserver::class])]
final class UserProfile extends Model implements HasMedia
{
    use BelongsToTenant;
    use BelongsToUser;
    /** @use HasFactory<UserProfileFactory> */
    use HasFactory;
    use HasUserProfileBalance;
    use HasUserProfileDocument;
    use HasUserProfilePhone;
    use InteractsWithMedia, ThumbnailTableRecord {
        ThumbnailTableRecord::registerMediaCollections insteadof InteractsWithMedia;
        ThumbnailTableRecord::registerMediaConversions insteadof InteractsWithMedia;
    }
    use LogsActivity;
    use SoftDeletes;

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'id'          => 'integer',
        'tenant_id'   => 'integer',
        'user_id'     => 'integer',
        'first_name'  => 'string',
        'last_name'   => 'string',
        'description' => 'string',
        'birthdate'   => 'datetime:Y-m-d',
        'status'      => 'boolean',
    ];

    /**
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'description',
        'birthdate',
        'status',
    ];

    /**
     * @var list<string>
     */
    protected $hidden = [
        'tenant_id',
    ];

    /**
     * Get the user's full name.
     *
     * @return Attribute<string, never>
     */
    protected function fullName(): Attribute
    {
        return Attribute::make(
            get: function (): string {
                return "{$this->first_name} {$this->last_name}";
            },
        );
    }

    /**
     * @return MorphMany<Media, $this>
     */
    public function multimedia(): MorphMany
    {
        return $this->media();
    }

    /**
     * @return LogOptions
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->logExcept(['id']);
    }
}
