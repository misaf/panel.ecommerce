<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum StatusEnum: string implements HasLabel, HasColor, HasIcon
{
    case Disable = 'disabled';
    case Enable = 'enabled';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::Enable  => 'gray',
            self::Disable => 'warning',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::Enable  => 'heroicon-m-check',
            self::Disable => 'heroicon-m-x-mark',
        };
    }

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Enable  => 'Enable',
            self::Disable => 'Disable',
        };
    }
}
