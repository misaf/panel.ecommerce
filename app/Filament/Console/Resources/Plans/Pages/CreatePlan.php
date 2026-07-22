<?php

declare(strict_types=1);

namespace App\Filament\Console\Resources\Plans\Pages;

use App\Filament\Console\Resources\Plans\PlanResource;
use Filament\Resources\Pages\CreateRecord;

final class CreatePlan extends CreateRecord
{
    protected static string $resource = PlanResource::class;
}
