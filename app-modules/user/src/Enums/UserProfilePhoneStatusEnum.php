<?php

declare(strict_types=1);

namespace Misaf\User\Enums;

use Filament\Support\Colors\Color;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasDescription;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum UserProfilePhoneStatusEnum: string implements HasColor, HasDescription, HasIcon, HasLabel
{
    case Approved = 'approved';
    case Pending = 'pending';
    case Rejected = 'rejected';

    /**
     * @return array<string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return match ($this) {
            self::Approved => trans('This status indicates that the user has been successfully authenticated and can access their account.'),
            self::Rejected => trans("This status indicates that the user's authentication attempt was unsuccessful."),
            self::Pending  => trans("This status indicates that the user's authentication is still being processed."),
        };
    }

    /**
     * @return array<string>
     */
    public function getColor(): array
    {
        return match ($this) {
            self::Approved => Color::Blue,
            self::Rejected => Color::Red,
            self::Pending  => Color::Yellow,
        };
    }

    /**
     * @return string
     */
    public function getIcon(): string
    {
        return match ($this) {
            self::Approved => 'heroicon-m-eye',
            self::Rejected => 'heroicon-m-x-mark',
            self::Pending  => 'heroicon-m-check',
        };
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return match ($this) {
            self::Approved => __('form.approved'),
            self::Rejected => __('form.rejected'),
            self::Pending  => __('form.pending'),
        };
    }
}
