<?php

declare(strict_types=1);

namespace App\Filament\Admin\Clusters\Transactions\Resources\Transactions\Pages;

use App\Filament\Admin\Clusters\Transactions\Resources\Transactions\TransactionResource;
use Filament\Resources\Pages\ViewRecord;

final class ViewTransaction extends ViewRecord
{
    protected static string $resource = TransactionResource::class;

    public function getBreadcrumb(): string
    {
        return self::$breadcrumb ?? __('filament-panels::resources/pages/view-record.breadcrumb') . ' ' . __('navigation.transaction');
    }
}
