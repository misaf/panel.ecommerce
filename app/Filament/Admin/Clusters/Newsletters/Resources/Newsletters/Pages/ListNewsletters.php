<?php

declare(strict_types=1);

namespace App\Filament\Admin\Clusters\Newsletters\Resources\Newsletters\Pages;

use App\Filament\Admin\Clusters\Newsletters\Resources\Newsletters\NewsletterResource;
use App\Filament\Admin\Clusters\Newsletters\Resources\Newsletters\Widgets\NewsletterSendHistoryFailedCountOverview;
use App\Filament\Admin\Clusters\Newsletters\Resources\Newsletters\Widgets\NewsletterSendHistorySentCountOverview;
use App\Filament\Admin\Clusters\Newsletters\Resources\Newsletters\Widgets\NewsletterSubscribersOverview;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use LaraZeus\SpatieTranslatable\Actions\LocaleSwitcher;
use LaraZeus\SpatieTranslatable\Resources\Pages\ListRecords\Concerns\Translatable;

final class ListNewsletters extends ListRecords
{
    use Translatable;

    protected static string $resource = NewsletterResource::class;

    public function getBreadcrumb(): string
    {
        return self::$breadcrumb ?? __('filament-panels::resources/pages/list-records.breadcrumb') . ' ' . __('newsletter/navigation.newsletter');
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),

            LocaleSwitcher::make(),
        ];
    }

    /**
     * @return array<string, int>
     */
    public function getHeaderWidgetsColumns(): array
    {
        return [
            'sm'  => 1,
            'xl'  => 2,
            '2xl' => 3,
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            NewsletterSubscribersOverview::class,
            NewsletterSendHistorySentCountOverview::class,
            NewsletterSendHistoryFailedCountOverview::class,
        ];
    }
}
