<?php

declare(strict_types=1);

namespace Misaf\VendraGeo\Filament\Clusters\Resources\States\Pages;

use Filament\Resources\Pages\CreateRecord;
use Misaf\VendraGeo\Filament\Clusters\Resources\States\StateResource;

final class CreateState extends CreateRecord
{
    protected static string $resource = StateResource::class;
}
