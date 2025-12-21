<?php

declare(strict_types=1);

namespace App\Filament\Admin\Clusters\Newsletters\Resources\NewsletterSendHistories;

use App\Filament\Admin\Clusters\Newsletters\NewslettersCluster;
use App\Filament\Admin\Clusters\Newsletters\Resources\NewsletterSendHistories\Pages\ListNewsletterSendHistories;
use App\Filament\Admin\Clusters\Newsletters\Resources\NewsletterSendHistories\Pages\ViewNewsletterSendHistory;
use App\Filament\Admin\Clusters\Newsletters\Resources\NewsletterSendHistories\Schemas\NewsletterSendHistoryTable;
use App\Filament\Admin\Clusters\Newsletters\Resources\NewsletterSendHistoryPosts\RelationManagers\NewsletterSendHistoryPostRelationManager;
use App\Filament\Admin\Clusters\Newsletters\Resources\NewsletterSendHistorySubscribers\RelationManagers\NewsletterSendHistorySubscriberRelationManager;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Illuminate\Support\Number;
use Misaf\Newsletter\Models\NewsletterSendHistory;

final class NewsletterSendHistoryResource extends Resource
{
    protected static ?string $model = NewsletterSendHistory::class;

    protected static ?int $navigationSort = 2;

    protected static ?string $slug = 'send-histories';

    protected static ?string $cluster = NewslettersCluster::class;

    protected static bool $isDiscovered = true;

    protected static bool $shouldRegisterNavigation = false;

    public static function getBreadcrumb(): string
    {
        return __('newsletter/navigation.newsletter_send_history');
    }

    public static function getModelLabel(): string
    {
        return __('newsletter/navigation.newsletter_send_history');
    }

    public static function getNavigationGroup(): string
    {
        return __('newsletter/navigation.newsletter_management');
    }

    public static function getNavigationLabel(): string
    {
        return __('newsletter/navigation.newsletter_send_history');
    }

    public static function getPluralModelLabel(): string
    {
        return __('newsletter/navigation.newsletter_send_history');
    }

    public static function getNavigationBadge(): string
    {
        return (string) Number::format(app('newsletter-post-service')->getCount());
    }

    public static function getPages(): array
    {
        return [
            'index' => ListNewsletterSendHistories::route('/'),
            'view'  => ViewNewsletterSendHistory::route('/{record}'),
        ];
    }

    public static function getRelations(): array
    {
        return [
            NewsletterSendHistoryPostRelationManager::class,
            NewsletterSendHistorySubscriberRelationManager::class,
        ];
    }

    public static function table(Table $table): Table
    {
        return NewsletterSendHistoryTable::configure($table);
    }
}
