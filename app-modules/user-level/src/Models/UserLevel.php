<?php

declare(strict_types=1);

namespace Misaf\UserLevel\Models;

use App\Traits\HasSlugOptionsTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Misaf\Tenant\Traits\BelongsToTenant;
use Misaf\UserLevel\Database\Factories\UserLevelFactory;
use Misaf\UserLevel\Traits\HasUserLevelHistory;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property int $id
 * @property int $tenant_id
 * @property string $name
 * @property string $description
 * @property string $slug
 * @property float $min_points
 * @property bool $status
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 */
final class UserLevel extends Model
{
    use BelongsToTenant;
    /** @use HasFactory<UserLevelFactory> */
    use HasFactory;
    use HasSlugOptionsTrait;
    use HasUserLevelHistory;
    use LogsActivity;
    use SoftDeletes;

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'id'          => 'integer',
        'tenant_id'   => 'integer',
        'name'        => 'string',
        'description' => 'string',
        'slug'        => 'string',
        'min_points'  => 'decimal:2',
        'status'      => 'boolean',
    ];

    /**
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'description',
        'slug',
        'min_points',
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
