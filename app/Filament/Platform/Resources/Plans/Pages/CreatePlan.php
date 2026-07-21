<?php

declare(strict_types=1);

namespace App\Filament\Platform\Resources\Plans\Pages;

use App\Filament\Platform\Resources\Plans\PlanResource;
use Filament\Resources\Pages\CreateRecord;

final class CreatePlan extends CreateRecord
{
    protected static string $resource = PlanResource::class;
}
