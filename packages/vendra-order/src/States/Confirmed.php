<?php

declare(strict_types=1);

namespace Misaf\VendraOrder\States;

use Filament\Support\Colors\Color;
use Filament\Support\Icons\Heroicon;

final class Confirmed extends OrderState
{
    public static string $name = 'confirmed';

    /**
     * @return array<string>
     */
    public function getColor(): array
    {
        return Color::Blue;
    }

    public function getIcon(): Heroicon
    {
        return Heroicon::OutlinedCheckCircle;
    }

    public function getLabel(): string
    {
        return __('vendra-order::enums.order_status_confirmed');
    }
}
