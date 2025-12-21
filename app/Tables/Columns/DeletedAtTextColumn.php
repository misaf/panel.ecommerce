<?php

declare(strict_types=1);

namespace App\Tables\Columns;

use Filament\Tables\Columns\TextColumn;

final class DeletedAtTextColumn
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
            ->unless(app()->isLocale('fa'), fn(TextColumn $column) => $column->jalaliDate('Y-m-d', toLatin: true))
            ->label(__('form.deleted_at'))
            ->sinceTooltip()
            ->toggleable(isToggledHiddenByDefault: true);
    }
}
