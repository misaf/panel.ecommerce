<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum OrderStatusEnum: string implements HasLabel, HasColor, HasIcon
{
    case Cancelled = 'cancelled';
    case Delivered = 'delivered';
    case Pending = 'pending';
    case Processing = 'processing';
    case Refund = 'refund';
    case Shipped = 'shipped';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::Pending    => 'gray',
            self::Processing => 'warning',
            self::Shipped    => 'warning',
            self::Delivered  => 'warning',
            self::Cancelled  => 'warning',
            self::Refund     => 'warning',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::Pending    => 'heroicon-m-check',
            self::Processing => 'heroicon-m-check',
            self::Shipped    => 'heroicon-m-check',
            self::Delivered  => 'heroicon-m-check',
            self::Cancelled  => 'heroicon-m-check',
            self::Refund     => 'heroicon-m-check',
        };
    }

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Pending    => 'Pending',
            self::Processing => 'Processing',
            self::Shipped    => 'Shiped',
            self::Delivered  => 'Delivered',
            self::Cancelled  => 'Cancelled',
            self::Refund     => 'Refund',
        };
    }
}
