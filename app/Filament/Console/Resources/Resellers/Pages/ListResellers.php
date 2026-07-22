<?php

declare(strict_types=1);

namespace App\Filament\Console\Resources\Resellers\Pages;

use App\Filament\Console\Resources\Resellers\ResellerResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

final class ListResellers extends ListRecords
{
    protected static string $resource = ResellerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
