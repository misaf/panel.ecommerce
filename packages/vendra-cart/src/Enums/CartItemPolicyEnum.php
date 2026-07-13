<?php

declare(strict_types=1);

namespace Misaf\VendraCart\Enums;

enum CartItemPolicyEnum: string
{
    case DELETE = 'delete-cart-item';
    case DELETE_ANY = 'delete-any-cart-item';
    case VIEW = 'view-cart-item';
    case VIEW_ANY = 'view-any-cart-item';
}
