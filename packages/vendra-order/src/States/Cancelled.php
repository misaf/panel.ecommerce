<?php

declare(strict_types=1);

namespace Misaf\VendraOrder\States;

use Filament\Support\Colors\Color;
use Filament\Support\Icons\Heroicon;

final class Cancelled extends OrderState
{
    public static string $name = 'cancelled';

    public function isFinal(): bool
    {
        return true;
    }

    /**
     * @return array<string>
     */
    public function getColor(): array
    {
        return Color::Red;
    }

    public function getIcon(): Heroicon
    {
        return Heroicon::OutlinedXCircle;
    }

    public function getLabel(): string
    {
        return __('vendra-order::enums.order_status_cancelled');
    }
}
