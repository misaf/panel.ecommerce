<?php

declare(strict_types=1);

namespace App\Tables\Columns;

use Filament\Tables\Columns\TextColumn;

final class EmailTextColumn
{
    /**
     * @param string $name
     * @return TextColumn
     */
    public static function make(string $name): TextColumn
    {
        return TextColumn::make($name)
            ->alignLeft()
            ->copyable()
            ->copyMessage(__('form.saved'))
            ->copyMessageDuration(1500)
            ->extraCellAttributes(['dir' => 'ltr']);
    }
}
