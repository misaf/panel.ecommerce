<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Language\LanguageResource\Pages;

use App\Filament\Admin\Resources\Language\LanguageResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateLanguage extends CreateRecord
{
    protected static string $resource = LanguageResource::class;
}
