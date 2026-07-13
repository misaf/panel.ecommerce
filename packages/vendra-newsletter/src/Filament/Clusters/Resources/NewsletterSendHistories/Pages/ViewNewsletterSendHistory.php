<?php

declare(strict_types=1);

namespace Misaf\VendraNewsletter\Filament\Clusters\Resources\NewsletterSendHistories\Pages;

use Filament\Resources\Pages\ViewRecord;
use Misaf\VendraNewsletter\Filament\Clusters\Resources\NewsletterSendHistories\NewsletterSendHistoryResource;

final class ViewNewsletterSendHistory extends ViewRecord
{
    protected static string $resource = NewsletterSendHistoryResource::class;

    public function getBreadcrumb(): string
    {
        return self::$breadcrumb ?? __('filament-panels::resources/pages/view-record.breadcrumb') . ' ' . __('vendra-newsletter::navigation.newsletter_send_history');
    }
}
