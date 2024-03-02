<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Currency\CurrencyResource\Pages;

use App\Filament\Admin\Resources\Currency\CurrencyResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

final class CreateCurrency extends CreateRecord
{
    protected static string $resource = CurrencyResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        return static::getModel()::create($data);
    }
}
