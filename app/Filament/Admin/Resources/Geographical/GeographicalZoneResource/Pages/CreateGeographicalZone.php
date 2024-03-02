<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Geographical\GeographicalZoneResource\Pages;

use App\Filament\Admin\Resources\Geographical\GeographicalZoneResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateGeographicalZone extends CreateRecord
{
    protected static string $resource = GeographicalZoneResource::class;
}
