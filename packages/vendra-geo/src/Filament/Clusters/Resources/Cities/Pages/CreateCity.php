<?php

declare(strict_types=1);

namespace Misaf\VendraGeo\Filament\Clusters\Resources\Cities\Pages;

use Filament\Resources\Pages\CreateRecord;
use Misaf\VendraGeo\Filament\Clusters\Resources\Cities\CityResource;

final class CreateCity extends CreateRecord
{
    protected static string $resource = CityResource::class;
}
