<?php

declare(strict_types=1);

namespace App\Filament\Admin\Clusters\Affiliates\Resources\AffiliateUsers\Pages;

use App\Filament\Admin\Clusters\Affiliates\Resources\AffiliateUsers\AffiliateUserResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateAffiliateUser extends CreateRecord
{
    protected static string $resource = AffiliateUserResource::class;

    public function getBreadcrumb(): string
    {
        return self::$breadcrumb ?? __('filament-panels::resources/pages/create-record.breadcrumb') . ' ' . __('navigation.affiliate_user');
    }
}
