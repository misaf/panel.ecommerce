<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Faq\FaqResource\Pages;

use App\Filament\Admin\Resources\Faq\FaqResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ListRecords\Concerns\Translatable;

final class ListFaq extends ListRecords
{
    use Translatable;

    protected static string $resource = FaqResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\LocaleSwitcher::make(),
        ];
    }
}
