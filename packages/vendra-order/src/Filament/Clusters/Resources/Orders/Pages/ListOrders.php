<?php

declare(strict_types=1);

namespace Misaf\VendraOrder\Filament\Clusters\Resources\Orders\Pages;

use Filament\Resources\Pages\ListRecords;
use Misaf\VendraOrder\Filament\Clusters\Resources\Orders\OrderResource;

final class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;
}
