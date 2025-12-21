<?php

declare(strict_types=1);

namespace App\Filament\Admin\Clusters\Newsletters\Resources\Newsletters\Pages;

use App\Filament\Admin\Clusters\Newsletters\Resources\Newsletters\NewsletterResource;
use App\Filament\Admin\Clusters\Newsletters\Resources\Newsletters\Widgets\NewsletterSendHistoryFailedCountOverview;
use App\Filament\Admin\Clusters\Newsletters\Resources\Newsletters\Widgets\NewsletterSendHistorySentCountOverview;
use App\Filament\Admin\Clusters\Newsletters\Resources\Newsletters\Widgets\NewsletterSubscribedUsersOverview;
use App\Filament\Admin\Clusters\Newsletters\Resources\Newsletters\Widgets\NewsletterUnsubscribedUsersOverview;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use LaraZeus\SpatieTranslatable\Actions\LocaleSwitcher;
use LaraZeus\SpatieTranslatable\Resources\Pages\ViewRecord\Concerns\Translatable;

final class ViewNewsletter extends ViewRecord
{
    use Translatable;

    protected static string $resource = NewsletterResource::class;

    public function getBreadcrumb(): string
    {
        return self::$breadcrumb ?? __('filament-panels::resources/pages/view-record.breadcrumb') . ' ' . __('newsletter/navigation.newsletter');
    }

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
            LocaleSwitcher::make(),
        ];
    }

    /**
     * @return array<string, int>
     */
    public function getHeaderWidgetsColumns(): array
    {
        return [
            'sm' => 1,
            'md' => 2,
            'lg' => 4,
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            NewsletterSubscribedUsersOverview::class,
            NewsletterUnsubscribedUsersOverview::class,
            NewsletterSendHistorySentCountOverview::class,
            NewsletterSendHistoryFailedCountOverview::class,
        ];
    }
}
