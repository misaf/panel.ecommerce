<?php

declare(strict_types=1);

namespace Misaf\Tag\Models;

use Misaf\Tenant\Traits\BelongsToTenant;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Tags\Tag as SpatieTag;

final class Tag extends SpatieTag
{
    use BelongsToTenant;
    use LogsActivity;

    /**
     * @return LogOptions
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->logExcept(['id']);
    }
}
