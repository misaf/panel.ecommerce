<?php

declare(strict_types=1);

namespace Misaf\Newsletter\Enums;

use Filament\Support\Colors\Color;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum NewsletterSendHistoryStatusEnum: string implements HasColor, HasIcon, HasLabel
{
    case SENDING = 'sending';
    case SENT = 'sent';
    case FAILED = 'failed';

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
            self::SENDING => Color::Blue,
            self::SENT    => Color::Green,
            self::FAILED  => Color::Red,
        };
    }

    public function getIcon(): string
    {
        return match ($this) {
            self::SENDING => 'heroicon-o-arrow-path',
            self::SENT    => 'heroicon-o-check-circle',
            self::FAILED  => 'heroicon-o-x-circle',
        };
    }

    public function getLabel(): string
    {
        return match ($this) {
            self::SENDING => __('newsletter::newsletter_send_history_status.labels.sending'),
            self::SENT    => __('newsletter::newsletter_send_history_status.labels.sent'),
            self::FAILED  => __('newsletter::newsletter_send_history_status.labels.failed'),
        };
    }
}
