<?php

declare(strict_types=1);

namespace Misaf\VendraWishlist\Enums;

enum WishlistPolicyEnum: string
{
    case Delete = 'delete-wishlist';
    case DeleteAny = 'delete-any-wishlist';
    case View = 'view-wishlist';
    case ViewAny = 'view-any-wishlist';
}
