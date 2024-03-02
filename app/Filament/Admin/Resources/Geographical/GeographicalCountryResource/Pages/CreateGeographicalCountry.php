<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Geographical\GeographicalCountryResource\Pages;

use App\Filament\Admin\Resources\Geographical\GeographicalCountryResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateGeographicalCountry extends CreateRecord
{
    protected static string $resource = GeographicalCountryResource::class;
}
