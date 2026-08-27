<?php

declare(strict_types=1);

namespace Misaf\VendraOrder\Filament\Clusters\Resources\Orders\Pages;

use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\ViewRecord;
use Misaf\VendraOrder\Filament\Clusters\Resources\Orders\Actions\CancelOrderAction;
use Misaf\VendraOrder\Filament\Clusters\Resources\Orders\Actions\CompleteOrderAction;
use Misaf\VendraOrder\Filament\Clusters\Resources\Orders\Actions\ConfirmOrderAction;
use Misaf\VendraOrder\Filament\Clusters\Resources\Orders\OrderResource;

final class ViewOrder extends ViewRecord
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ConfirmOrderAction::make(),
            CompleteOrderAction::make(),
            CancelOrderAction::make(),
            DeleteAction::make(),
        ];
    }
}
