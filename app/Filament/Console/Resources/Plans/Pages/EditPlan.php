<?php

declare(strict_types=1);

namespace App\Filament\Console\Resources\Plans\Pages;

use App\Filament\Console\Resources\Plans\PlanResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Misaf\VendraSubscription\Models\Plan;

final class EditPlan extends EditRecord
{
    protected static string $resource = PlanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->hidden(fn(Plan $record): bool => $record->isInUse()),
        ];
    }
}
