<?php

declare(strict_types=1);

namespace Misaf\Language\Models;

use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Misaf\Language\Database\Factories\LanguageTranslateFactory;
use Misaf\Language\Observers\LanguageTranslateObserver;
use Misaf\Tenant\Traits\BelongsToTenant;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\TranslationLoader\LanguageLine as SpatieLanguageLine;

/**
 * @property int $id
 * @property int $tenant_id
 * @property string $group
 * @property string $key
 * @property array<string, string> $text
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 */
#[ObservedBy([LanguageTranslateObserver::class])]
final class LanguageTranslate extends SpatieLanguageLine
{
    use BelongsToTenant;
    /** @use HasFactory<LanguageTranslateFactory> */
    use HasFactory;
    use LogsActivity;
    use SoftDeletes;

    /** @var array<string, string> $casts */
    // protected $casts = [
    //     'id'         => 'integer',
    //     'group'      => 'string',
    //     'key'        => 'string',
    //     // 'text'       => 'array',
    //     'created_at' => 'datetime',
    //     'updated_at' => 'datetime',
    //     'deleted_at' => 'datetime',
    // ];

    /** @var array<int, string> $fillable */
    // protected $fillable = [
    //     'group',
    //     'key',
    //     'text',
    // ];

    /** @var array<int, string> $hidden */
    // protected $hidden = [
    //     'tenant_id',
    // ];

    /**
     * @return LogOptions
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->logExcept(['id']);
    }
}
