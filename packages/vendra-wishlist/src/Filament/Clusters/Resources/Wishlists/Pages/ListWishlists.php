<?php

declare(strict_types=1);

namespace Misaf\VendraWishlist\Filament\Clusters\Resources\Wishlists\Pages;

use Filament\Resources\Pages\ListRecords;
use Misaf\VendraWishlist\Filament\Clusters\Resources\Wishlists\WishlistResource;

final class ListWishlists extends ListRecords
{
    protected static string $resource = WishlistResource::class;
}
