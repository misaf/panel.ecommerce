<?php

declare(strict_types=1);

use Filament\Facades\Filament;
use Misaf\VendraOrder\Database\Factories\OrderFactory;
use Misaf\VendraOrder\Filament\Clusters\Resources\Orders\Pages\ListOrders;
use Misaf\VendraOrder\Filament\Clusters\Resources\Orders\Pages\ViewOrder;

use function Pest\Livewire\livewire;

beforeEach(function (): void {
    setUpFilamentAdminTestContext();
});

it('renders the orders table under strict authorization', function (): void {
    Filament::getPanel('admin')->strictAuthorization();

    $order = OrderFactory::new()->createOne();

    livewire(ListOrders::class)
        ->assertOk()
        ->call('loadTable')
        ->assertCanSeeTableRecords([$order]);
});

it('renders the view order page under strict authorization', function (): void {
    Filament::getPanel('admin')->strictAuthorization();

    $order = OrderFactory::new()->createOne();

    livewire(ViewOrder::class, ['record' => $order->getKey()])
        ->assertOk();
});
