<?php

declare(strict_types=1);

namespace App\Tables\Columns;

use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use Misaf\AuthifyLog\Models\AuthifyLog;

final class IpAddressTextColumn
{
    /**
     * @param string $name
     * @return TextColumn
     */
    public static function make(string $name): TextColumn
    {
        return TextColumn::make('ip_address')
            ->alignCenter()
            ->copyable()
            ->copyMessage(__('form.saved'))
            ->copyMessageDuration(1500)
            ->extraCellAttributes(['dir' => 'ltr'])
            ->label(__('form.ip_address'))
            ->formatStateUsing(fn(string $state, AuthifyLog $record): HtmlString => new HtmlString(
                '<span class="flex items-center space-x-2">'
                . '<img src="' . asset('vendor/blade-country-flags/4x3-' . Str::lower($record->ip_country) . '.svg') . '" alt="' . $record->ip_country . '" title="' . $record->ip_country . '" class="w-4 inline-block" />'
                . '<span>' . $state . '</span>'
                . '</span>',
            ));
    }
}
