<?php

declare(strict_types=1);

namespace Misaf\AuthifyLog\Traits;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Misaf\AuthifyLog\Models\AuthifyLog;

trait HasAuthifyLog
{
    /**
     * @return HasOne<AuthifyLog, $this>
     */
    public function latestAuthifyLog(): HasOne
    {
        return $this->hasOne(AuthifyLog::class)->latestOfMany();
    }

    /**
     * @return HasOne<AuthifyLog, $this>
     */
    public function oldestAuthifyLog(): HasOne
    {
        return $this->hasOne(AuthifyLog::class)->oldestOfMany();
    }

    /**
     * @return HasMany<AuthifyLog, $this>
     */
    public function authifyLogs(): HasMany
    {
        return $this->hasMany(AuthifyLog::class);
    }
}
