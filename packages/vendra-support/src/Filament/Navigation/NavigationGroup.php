<?php

declare(strict_types=1);

namespace Misaf\VendraSupport\Filament\Navigation;

use Filament\Support\Contracts\HasLabel;

enum NavigationGroup implements HasLabel
{
    case Catalog;
    case Sales;
    case Customers;
    case Content;
    case Marketing;
    case Localization;
    case System;

    public function getLabel(): string
    {
        return __("vendra-support::navigation.groups.{$this->name}");
    }
}
