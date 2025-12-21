<?php

declare(strict_types=1);

namespace Misaf\User\Models;

use App\Traits\ThumbnailTableRecord;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Misaf\Tenant\Traits\BelongsToTenant;
use Misaf\User\Database\Factories\UserProfileDocumentFactory;
use Misaf\User\Enums\UserProfileDocumentStatusEnum;
use Misaf\User\Observers\UserProfileDocumentObserver;
use Misaf\User\Traits\BelongsToUserProfile;
use Misaf\User\Traits\BelongsToUserThroughUserProfile;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Znck\Eloquent\Traits\BelongsToThrough as TraitBelongsToThrough;

/**
 * @property int $id
 * @property int $tenant_id
 * @property int $user_profile_id
 * @property UserProfileDocumentStatusEnum $status
 * @property Carbon|null $verified_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon|null $deleted_at
 */
#[ObservedBy([UserProfileDocumentObserver::class])]
final class UserProfileDocument extends Model implements HasMedia
{
    use BelongsToTenant;
    use BelongsToUserProfile;
    use BelongsToUserThroughUserProfile;
    /** @use HasFactory<UserProfileDocumentFactory> */
    use HasFactory;
    use InteractsWithMedia, ThumbnailTableRecord {
        ThumbnailTableRecord::registerMediaCollections insteadof InteractsWithMedia;
        ThumbnailTableRecord::registerMediaConversions insteadof InteractsWithMedia;
    }
    use LogsActivity;
    use SoftDeletes;
    use TraitBelongsToThrough;

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'id'              => 'integer',
        'tenant_id'       => 'integer',
        'user_profile_id' => 'integer',
        'status'          => UserProfileDocumentStatusEnum::class,
        'verified_at'     => 'datetime',
    ];

    /**
     * @var list<string>
     */
    protected $fillable = [
        'user_profile_id',
        'status',
        'verified_at',
    ];

    /**
     * @var list<string>
     */
    protected $hidden = [
        'tenant_id',
    ];

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
