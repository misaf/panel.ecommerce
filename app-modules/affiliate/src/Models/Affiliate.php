<?php

declare(strict_types=1);

namespace Misaf\Affiliate\Models;

use App\Traits\HasSlugOptionsTrait;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Misaf\Affiliate\Database\Factories\AffiliateFactory;
use Misaf\Affiliate\Facades\AffiliateService;
use Misaf\Affiliate\Observers\AffiliateObserver;
use Misaf\Tenant\Traits\BelongsToTenant;
use Misaf\User\Traits\BelongsToUser;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property int $id
 * @property int $tenant_id
 * @property int $user_id
 * @property string $name
 * @property string $description
 * @property string $slug
 * @property int $commission_percent
 * @property bool $is_processing
 * @property bool $status
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 */
#[ObservedBy([AffiliateObserver::class])]
final class Affiliate extends Model
{
    use BelongsToTenant;
    use BelongsToUser;
    /** @use HasFactory<AffiliateFactory> */
    use HasFactory;
    use HasSlugOptionsTrait;
    use LogsActivity;
    use \Misaf\Affiliate\Traits\HasAffiliateUser;
    use SoftDeletes;

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'id'                 => 'integer',
        'tenant_id'          => 'integer',
        'user_id'            => 'integer',
        'name'               => 'string',
        'description'        => 'string',
        'slug'               => 'string',
        'commission_percent' => 'integer',
        'is_processing'      => 'boolean',
        'status'             => 'boolean',
    ];

    /**
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'name',
        'description',
        'slug',
        'commission_percent',
        'is_processing',
        'status',
    ];

    /**
     * @var list<string>
     */
    protected $hidden = [
        'tenant_id',
    ];

    /**
     * @return void
     */
    protected static function booted(): void
    {
        self::creating(function (self $affiliate): void {
            $name = AffiliateService::generateName();

            $affiliate->name = $name;
            $affiliate->slug = Str::slug($name);
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
