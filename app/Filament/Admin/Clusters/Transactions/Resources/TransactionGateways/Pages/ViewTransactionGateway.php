<?php

declare(strict_types=1);

namespace App\Filament\Admin\Clusters\Transactions\Resources\TransactionGateways\Pages;

use App\Filament\Admin\Clusters\Transactions\Resources\TransactionGateways\TransactionGatewayResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use LaraZeus\SpatieTranslatable\Resources\Pages\ViewRecord\Concerns\Translatable;

final class ViewTransactionGateway extends ViewRecord
{
    use Translatable;

    protected static string $resource = TransactionGatewayResource::class;

    public function getBreadcrumb(): string
    {
        return self::$breadcrumb ?? __('filament-panels::resources/pages/view-record.breadcrumb') . ' ' . __('navigation.transaction_gateway');
    }

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
