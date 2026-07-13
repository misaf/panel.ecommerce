<?php

declare(strict_types=1);

namespace Misaf\VendraCart\Filament\Resources\Carts\Pages;

use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\ViewRecord;
use Misaf\VendraCart\Filament\Resources\Carts\CartResource;

final class ViewCart extends ViewRecord
{
    protected static string $resource = CartResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
