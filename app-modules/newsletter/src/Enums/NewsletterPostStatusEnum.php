<?php

declare(strict_types=1);

namespace Misaf\Newsletter\Enums;

use Filament\Support\Colors\Color;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum NewsletterPostStatusEnum: string implements HasColor, HasIcon, HasLabel
{
    case DRAFT = 'draft';
    case READY = 'ready';

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
            self::DRAFT => Color::Gray,
            self::READY => Color::Yellow,
        };
    }

    public function getIcon(): string
    {
        return match ($this) {
            self::DRAFT => 'heroicon-o-pencil-square',
            self::READY => 'heroicon-o-paper-airplane',
        };
    }

    public function getLabel(): string
    {
        return match ($this) {
            self::DRAFT => __('newsletter::newsletter_post_status_enum.draft'),
            self::READY => __('newsletter::newsletter_post_status_enum.ready'),
        };
    }
}
