<?php

declare(strict_types=1);

namespace Misaf\VendraDelivery\Filament\Clusters\Resources\Deliveries\Pages;

use Filament\Resources\Pages\ListRecords;
use Misaf\VendraDelivery\Filament\Clusters\Resources\Deliveries\DeliveryResource;

final class ListDeliveries extends ListRecords
{
    protected static string $resource = DeliveryResource::class;
}
