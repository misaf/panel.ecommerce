<?php

declare(strict_types=1);

namespace App\Filament\Console\Resources\Properties\Pages;

use App\Filament\Console\Resources\Properties\PropertyResource;
use Filament\Resources\Pages\EditRecord;

final class EditProperty extends EditRecord
{
    protected static string $resource = PropertyResource::class;
}
