<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum TransactionStatusEnum: string implements HasLabel, HasColor, HasIcon
{
    case Approved = 'approved';
    case Declined = 'declined';
    case Failed = 'failed';
    case Pending = 'pending';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::Pending  => 'gray',
            self::Approved => 'warning',
            self::Failed   => 'warning',
            self::Declined => 'warning',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::Pending  => 'heroicon-m-check',
            self::Approved => 'heroicon-m-check',
            self::Failed   => 'heroicon-m-check',
            self::Declined => 'heroicon-m-check',
        };
    }

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Pending  => 'Pending',
            self::Approved => 'Approved',
            self::Failed   => 'Failed',
            self::Declined => 'Declined',
        };
    }
}
