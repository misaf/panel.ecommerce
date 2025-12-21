<?php

declare(strict_types=1);

namespace App\Filament\Admin\Clusters\Affiliates\Resources\Affiliates\Pages;

use App\Filament\Admin\Clusters\Affiliates\Resources\Affiliates\AffiliateResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

final class EditAffiliate extends EditRecord
{
    protected static string $resource = AffiliateResource::class;

    public function getBreadcrumb(): string
    {
        return self::$breadcrumb ?? __('filament-panels::resources/pages/edit-record.breadcrumb') . ' ' . __('navigation.affiliate');
    }

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
