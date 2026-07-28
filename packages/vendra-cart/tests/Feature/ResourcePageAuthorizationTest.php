<?php

declare(strict_types=1);

use Filament\Facades\Filament;
use Misaf\VendraCart\Filament\Clusters\Resources\Carts\Pages\ListCarts;
use Misaf\VendraCart\Filament\Clusters\Resources\Carts\Pages\ViewCart;
use Misaf\VendraCart\Models\Cart;

use function Pest\Livewire\livewire;

beforeEach(function (): void {
    setUpFilamentSuperAdminTestContext();
});

it('renders the list carts page under strict authorization', function (): void {
    Filament::getPanel('admin')->strictAuthorization();

    livewire(ListCarts::class)
        ->assertOk();
});

it('renders the view cart page under strict authorization', function (): void {
    Filament::getPanel('admin')->strictAuthorization();

    $cart = Cart::factory()->create();

    livewire(ViewCart::class, ['record' => $cart->getKey()])
        ->assertOk();
});
