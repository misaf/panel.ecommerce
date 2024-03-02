<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Faq\FaqResource\Pages;

use App\Filament\Admin\Resources\Faq\FaqResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\CreateRecord\Concerns\Translatable;

final class CreateFaq extends CreateRecord
{
    use Translatable;

    protected static string $resource = FaqResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
        ];
    }
}
