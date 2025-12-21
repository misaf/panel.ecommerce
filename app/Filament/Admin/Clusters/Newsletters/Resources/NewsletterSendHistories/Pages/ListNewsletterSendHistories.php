<?php

declare(strict_types=1);

namespace App\Filament\Admin\Clusters\Newsletters\Resources\NewsletterSendHistories\Pages;

use App\Filament\Admin\Clusters\Newsletters\Resources\NewsletterSendHistories\NewsletterSendHistoryResource;
use Filament\Resources\Pages\ListRecords;

final class ListNewsletterSendHistories extends ListRecords
{
    protected static string $resource = NewsletterSendHistoryResource::class;

    public function getBreadcrumb(): string
    {
        return self::$breadcrumb ?? __('filament-panels::resources/pages/list-records.breadcrumb') . ' ' . __('newsletter::navigation.newsletter_send_history');
    }
}
