<?php

declare(strict_types=1);

namespace App\Filament\Platform\Resources\Websites\Pages;

use App\Filament\Platform\Resources\Websites\WebsiteResource;
use Filament\Resources\Pages\EditRecord;

final class EditWebsite extends EditRecord
{
    protected static string $resource = WebsiteResource::class;
}
