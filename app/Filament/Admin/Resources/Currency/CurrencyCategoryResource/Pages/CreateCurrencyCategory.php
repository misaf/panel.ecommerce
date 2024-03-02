<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Currency\CurrencyCategoryResource\Pages;

use App\Filament\Admin\Resources\Currency\CurrencyCategoryResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateCurrencyCategory extends CreateRecord
{
    protected static string $resource = CurrencyCategoryResource::class;
}
