<?php

declare(strict_types=1);

namespace App\Tables\Columns;

use Filament\Tables\Columns\ToggleColumn;

final class StatusToggleColumn
{
    /**
     * @param string $name
     * @return ToggleColumn
     */
    public static function make(string $name): ToggleColumn
    {
        return ToggleColumn::make($name)
            ->label(__('form.status'))
            ->onIcon('heroicon-m-bolt');
    }
}
