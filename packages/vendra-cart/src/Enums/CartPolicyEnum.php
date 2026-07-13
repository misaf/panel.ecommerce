<?php

declare(strict_types=1);

namespace Misaf\VendraCart\Enums;

enum CartPolicyEnum: string
{
    case DELETE = 'delete-cart';
    case DELETE_ANY = 'delete-any-cart';
    case VIEW = 'view-cart';
    case VIEW_ANY = 'view-any-cart';
}
