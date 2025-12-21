<?php

declare(strict_types=1);

namespace App\Filament\Admin\Clusters\Affiliates\Resources\Affiliates\Pages;

use App\Filament\Admin\Clusters\Affiliates\Resources\Affiliates\AffiliateResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateAffiliate extends CreateRecord
{
    protected static string $resource = AffiliateResource::class;

    public function getBreadcrumb(): string
    {
        return self::$breadcrumb ?? __('filament-panels::resources/pages/create-record.breadcrumb') . ' ' . __('navigation.affiliate');
    }
}
