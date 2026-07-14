<?php

declare(strict_types=1);

namespace Misaf\VendraAffiliate\Enums;

use Filament\Support\Colors\Color;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum CommissionStatusEnum: string implements HasColor, HasIcon, HasLabel
{
    case Pending = 'pending';
    case Approved = 'approved';
    case Paid = 'paid';
    case Reversed = 'reversed';

    /**
     * @return array<string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * @return array<string>
     */
    public function getColor(): array
    {
        return match ($this) {
            self::Pending  => Color::Yellow,
            self::Approved => Color::Green,
            self::Paid     => Color::Blue,
            self::Reversed => Color::Red,
        };
    }

    public function getIcon(): string
    {
        return match ($this) {
            self::Pending  => 'heroicon-o-clock',
            self::Approved => 'heroicon-o-check-circle',
            self::Paid     => 'heroicon-o-banknotes',
            self::Reversed => 'heroicon-o-arrow-uturn-left',
        };
    }

    public function getLabel(): string
    {
        return match ($this) {
            self::Pending  => __('vendra-affiliate::enums.commission_status_pending'),
            self::Approved => __('vendra-affiliate::enums.commission_status_approved'),
            self::Paid     => __('vendra-affiliate::enums.commission_status_paid'),
            self::Reversed => __('vendra-affiliate::enums.commission_status_reversed'),
        };
    }
}
