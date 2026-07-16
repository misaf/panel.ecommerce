<?php

declare(strict_types=1);

namespace Misaf\VendraCart\Filament\Clusters\Resources\Carts\Pages;

use Filament\Resources\Pages\ListRecords;
use Misaf\VendraCart\Filament\Clusters\Resources\Carts\CartResource;
use Misaf\VendraCart\Filament\Clusters\Resources\Carts\Widgets\CartOverviewWidget;

final class ListCarts extends ListRecords
{
    protected static string $resource = CartResource::class;

    /**
     * @return array<string, int>
     */
    public function getHeaderWidgetsColumns(): array
    {
        return [
            'sm' => 1,
            'md' => 2,
            'lg' => 3,
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            CartOverviewWidget::class,
        ];
    }
}
