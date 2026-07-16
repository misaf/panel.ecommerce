<?php

declare(strict_types=1);

namespace Misaf\VendraGeo\Filament\Clusters\Resources\Countries\Pages;

use Filament\Resources\Pages\CreateRecord;
use Misaf\VendraGeo\Filament\Clusters\Resources\Countries\CountryResource;

final class CreateCountry extends CreateRecord
{
    protected static string $resource = CountryResource::class;
}
