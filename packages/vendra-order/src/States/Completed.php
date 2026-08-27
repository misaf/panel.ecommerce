<?php

declare(strict_types=1);

namespace Misaf\VendraOrder\States;

use Filament\Support\Colors\Color;
use Filament\Support\Icons\Heroicon;

final class Completed extends OrderState
{
    public static string $name = 'completed';

    public function isFinal(): bool
    {
        return true;
    }

    /**
     * @return array<string>
     */
    public function getColor(): array
    {
        return Color::Green;
    }

    public function getIcon(): Heroicon
    {
        return Heroicon::OutlinedTruck;
    }

    public function getLabel(): string
    {
        return __('vendra-order::enums.order_status_completed');
    }
}
