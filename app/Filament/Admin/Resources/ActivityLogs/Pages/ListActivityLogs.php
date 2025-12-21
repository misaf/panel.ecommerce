<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\ActivityLogs\Pages;

use App\Filament\Admin\Resources\ActivityLogs\ActivityLogs\ActivityLogResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Activitylog\Models\Activity;

final class ListActivityLogs extends ListRecords
{
    protected static string $resource = ActivityLogResource::class;

    /**
     * @param Builder<Activity> $query
     * @return Paginator<int, Activity>
     */

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
