<?php

declare(strict_types=1);

namespace App\Tables\Columns;

use Filament\Tables\Columns\TextColumn;

final class NameTextColumn
{
    /**
     * @param string $name
     * @return TextColumn
     */
    public static function make(string $name): TextColumn
    {
        return TextColumn::make($name)
            ->label(__('form.name'))
            ->searchable();
    }
}
