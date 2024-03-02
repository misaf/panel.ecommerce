<?php

declare(strict_types=1);

namespace App\Models\Permission;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Models\Role as ModelsRole;

final class Role extends ModelsRole
{
    use HasFactory;

    use LogsActivity;

    use SoftDeletes;

    protected $casts = [
        'id'         => 'integer',
        'name'       => 'string',
        'guard_name' => 'string',
    ];

    protected $fillable = [
        'name',
        'guard_name',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logExcept(['id']);
    }
}
