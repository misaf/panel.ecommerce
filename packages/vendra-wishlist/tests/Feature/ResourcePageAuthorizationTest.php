<?php

declare(strict_types=1);

use Filament\Facades\Filament;
use Misaf\VendraWishlist\Database\Factories\WishlistFactory;
use Misaf\VendraWishlist\Filament\Clusters\Resources\Wishlists\Pages\ListWishlists;
use Misaf\VendraWishlist\Filament\Clusters\Resources\Wishlists\Pages\ViewWishlist;

use function Pest\Livewire\livewire;

beforeEach(function (): void {
    setUpFilamentAdminTestContext();

    Filament::getPanel('admin')->strictAuthorization();
});

it('renders the wishlists table under strict authorization', function (): void {
    $wishlist = WishlistFactory::new()->createOne();

    livewire(ListWishlists::class)
        ->assertOk()
        ->call('loadTable')
        ->assertCanSeeTableRecords([$wishlist]);
});

it('renders the view wishlist page under strict authorization', function (): void {
    $wishlist = WishlistFactory::new()->createOne();

    livewire(ViewWishlist::class, ['record' => $wishlist->getKey()])->assertOk();
});
