<?php

declare(strict_types=1);

namespace Misaf\VendraAffiliate\Enums;

use Filament\Support\Colors\Color;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum AffiliateStatusEnum: string implements HasColor, HasIcon, HasLabel
{
    case Active = 'active';
    case Suspended = 'suspended';

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
            self::Active    => Color::Green,
            self::Suspended => Color::Red,
        };
    }

    public function getIcon(): string
    {
        return match ($this) {
            self::Active    => 'heroicon-o-check-circle',
            self::Suspended => 'heroicon-o-no-symbol',
        };
    }

    public function getLabel(): string
    {
        return match ($this) {
            self::Active    => __('vendra-affiliate::enums.affiliate_status_active'),
            self::Suspended => __('vendra-affiliate::enums.affiliate_status_suspended'),
        };
    }
}
