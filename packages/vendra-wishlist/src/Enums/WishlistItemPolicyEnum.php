<?php

declare(strict_types=1);

namespace Misaf\VendraWishlist\Enums;

enum WishlistItemPolicyEnum: string
{
    case Delete = 'delete-wishlist-item';
    case DeleteAny = 'delete-any-wishlist-item';
    case View = 'view-wishlist-item';
    case ViewAny = 'view-any-wishlist-item';
}
