<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum ProductStatusEnum: string implements HasLabel, HasColor, HasIcon
{
    case AvailableSoon = 'available_soon';
    case InStock = 'in_stock';
    case OutOfStock = 'out_of_stock';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::InStock       => 'warning',
            self::OutOfStock    => 'warning',
            self::AvailableSoon => 'warning',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::InStock       => 'heroicon-m-check',
            self::OutOfStock    => 'heroicon-m-check',
            self::AvailableSoon => 'heroicon-m-check',
        };
    }

    public function getLabel(): ?string
    {
        return match ($this) {
            self::InStock       => __('status.in_stock'),
            self::OutOfStock    => __('status.out_of_stock'),
            self::AvailableSoon => __('status.available_soon'),
        };
    }
}
