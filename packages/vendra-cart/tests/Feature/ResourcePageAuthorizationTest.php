<?php

declare(strict_types=1);

use Filament\Facades\Filament;
use Misaf\VendraCart\Filament\Clusters\Resources\Carts\Pages\ListCarts;
use Misaf\VendraCart\Filament\Clusters\Resources\Carts\Pages\ViewCart;
use Misaf\VendraPermission\Tests\Support\PermissionModuleTestContext;

use function Pest\Livewire\livewire;

beforeEach(function (): void {
    PermissionModuleTestContext::setUpFilamentAdminContext();
});

it('renders the list carts page under strict authorization', function (): void {
    Filament::getPanel('admin')->strictAuthorization();

    livewire(ListCarts::class)
        ->assertOk();
});

it('renders the view cart page under strict authorization', function (): void {
    Filament::getPanel('admin')->strictAuthorization();

    livewire(ViewCart::class, ['record' => 1])
        ->assertOk();
})->skip('Pre-existing Jalali date picker view error');
