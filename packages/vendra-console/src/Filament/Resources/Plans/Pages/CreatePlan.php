<?php

declare(strict_types=1);

namespace Misaf\VendraConsole\Filament\Resources\Plans\Pages;

use Misaf\VendraConsole\Filament\Resources\Plans\PlanResource;
use Filament\Resources\Pages\CreateRecord;

final class CreatePlan extends CreateRecord
{
    protected static string $resource = PlanResource::class;
}
