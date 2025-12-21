<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\ActivityLog;
use Misaf\Tenant\Traits\BelongsToTenant;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\LaravelSettings\Models\SettingsProperty as SpatieSettingsProperty;

final class SettingsProperty extends SpatieSettingsProperty
{
    use ActivityLog;
    use BelongsToTenant;
    use LogsActivity;

    /**
     * @var list<string>
     */
    protected $hidden = [
        'tenant_id',
    ];
}
