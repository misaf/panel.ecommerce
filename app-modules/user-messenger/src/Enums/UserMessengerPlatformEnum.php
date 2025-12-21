<?php

declare(strict_types=1);

namespace Misaf\UserMessenger\Enums;

use Filament\Support\Colors\Color;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum UserMessengerPlatformEnum: string implements HasColor, HasIcon, HasLabel
{
    case Telegram = 'telegram';

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
            self::Telegram => Color::Blue,
        };
    }

    /**
     * @return string
     */
    public function getIcon(): string
    {
        return match ($this) {
            self::Telegram => 'fab-telegram',
        };
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return match ($this) {
            self::Telegram => __('user-messenger::user_messenger_platform_enum.telegram'),
        };
    }
}
