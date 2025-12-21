<?php

declare(strict_types=1);

namespace App\Tables\Columns;

use Filament\Tables\Columns\TextColumn;

final class UpdatedAtTextColumn
{
    /**
     * @param string $name
     * @return TextColumn
     */
    public static function make(string $name): TextColumn
    {
        return TextColumn::make($name)
            ->alignCenter()
            ->badge()
            ->extraCellAttributes(['dir' => 'ltr'])
            ->unless(app()->isLocale('fa'), fn(TextColumn $column) => $column->jalaliDateTime('Y-m-d', toLatin: true))
            ->sinceTooltip()
            ->toggleable(isToggledHiddenByDefault: true);
    }
}
