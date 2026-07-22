<?php

declare(strict_types=1);

namespace App\Filament\Console\Resources\Plans\Pages;

use App\Filament\Console\Resources\Plans\PlanResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

final class ListPlans extends ListRecords
{
    protected static string $resource = PlanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
