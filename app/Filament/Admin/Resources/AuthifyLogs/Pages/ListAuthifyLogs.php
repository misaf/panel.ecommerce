<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\AuthifyLogs\Pages;

use App\Filament\Admin\Resources\AuthifyLogs\AuthifyLogs\AuthifyLogResource;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use Misaf\AuthifyLog\Models\AuthifyLog;

final class ListAuthifyLogs extends ListRecords
{
    protected static string $resource = AuthifyLogResource::class;

    /**
     * @param Builder<AuthifyLog> $query
     * @return Paginator<int, AuthifyLog>
     */
}
