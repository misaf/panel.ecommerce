<?php

declare(strict_types=1);

namespace Misaf\VendraCart\Filament\Resources\Carts\Pages;

use Filament\Resources\Pages\ListRecords;
use Misaf\VendraCart\Filament\Resources\Carts\CartResource;

final class ListCarts extends ListRecords
{
    protected static string $resource = CartResource::class;
}
