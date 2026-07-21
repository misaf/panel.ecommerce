<?php

declare(strict_types=1);

namespace Misaf\VendraCurrency\Enums;

use Filament\Support\Contracts\HasLabel;

enum CurrencyType: string implements HasLabel
{
    case Fiat = 'fiat';
    case Crypto = 'crypto';

    public function getLabel(): string
    {
        return __("vendra-currency::attributes.types.{$this->value}");
    }
}
