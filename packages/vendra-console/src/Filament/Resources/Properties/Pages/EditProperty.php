<?php

declare(strict_types=1);

namespace Misaf\VendraConsole\Filament\Resources\Properties\Pages;

use Misaf\VendraConsole\Filament\Resources\Properties\PropertyResource;
use Filament\Resources\Pages\EditRecord;

final class EditProperty extends EditRecord
{
    protected static string $resource = PropertyResource::class;
}
