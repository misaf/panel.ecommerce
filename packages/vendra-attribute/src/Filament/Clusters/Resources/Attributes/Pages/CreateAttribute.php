<?php

declare(strict_types=1);

namespace Misaf\VendraAttribute\Filament\Clusters\Resources\Attributes\Pages;

use Filament\Resources\Pages\CreateRecord;
use Misaf\VendraAttribute\Filament\Clusters\Resources\Attributes\AttributeResource;

final class CreateAttribute extends CreateRecord
{
    protected static string $resource = AttributeResource::class;
}
