<?php

declare(strict_types=1);

namespace Misaf\VendraOrder\States;

use Filament\Support\Colors\Color;
use Filament\Support\Icons\Heroicon;

final class Pending extends OrderState
{
    public static string $name = 'pending';

    /**
     * @return array<string>
     */
    public function getColor(): array
    {
        return Color::Amber;
    }

    public function getIcon(): Heroicon
    {
        return Heroicon::OutlinedClock;
    }

    public function getLabel(): string
    {
        return __('vendra-order::enums.order_status_pending');
    }
}
