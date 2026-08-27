<?php

declare(strict_types=1);

namespace App\Models;

use Misaf\VendraSupport\Tenancy\BelongsToTenant;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;
use Spatie\LaravelSettings\Models\SettingsProperty as SpatieSettingsProperty;

final class SettingsProperty extends SpatieSettingsProperty
{
    use BelongsToTenant;
    use LogsActivity;

    /**
     * Both columns describe where a row lives rather than what it holds.
     *
     * @var list<string>
     */
    protected $hidden = [
        'scope',
        'tenant_id',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->logExcept(['id']);
    }
}
