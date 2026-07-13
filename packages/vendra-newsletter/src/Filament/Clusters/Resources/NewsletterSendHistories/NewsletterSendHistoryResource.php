<?php

declare(strict_types=1);

namespace Misaf\VendraNewsletter\Filament\Clusters\Resources\NewsletterSendHistories;

use Filament\Resources\Resource;
use Filament\Tables\Table;
use Illuminate\Support\Number;
use Misaf\VendraNewsletter\Filament\Clusters\NewslettersCluster;
use Misaf\VendraNewsletter\Filament\Clusters\Resources\NewsletterSendHistories\Pages\ListNewsletterSendHistories;
use Misaf\VendraNewsletter\Filament\Clusters\Resources\NewsletterSendHistories\Pages\ViewNewsletterSendHistory;
use Misaf\VendraNewsletter\Filament\Clusters\Resources\NewsletterSendHistories\Tables\NewsletterSendHistoryTable;
use Misaf\VendraNewsletter\Filament\Clusters\Resources\NewsletterSendHistoryPosts\RelationManagers\NewsletterSendHistoryPostRelationManager;
use Misaf\VendraNewsletter\Filament\Clusters\Resources\NewsletterSendHistorySubscribers\RelationManagers\NewsletterSendHistorySubscriberRelationManager;
use Misaf\VendraNewsletter\Models\NewsletterSendHistory;

final class NewsletterSendHistoryResource extends Resource
{
    protected static ?string $model = NewsletterSendHistory::class;

    protected static ?int $navigationSort = 2;

    protected static ?string $slug = 'send-histories';

    protected static ?string $cluster = NewslettersCluster::class;

    protected static bool $isDiscovered = true;

    protected static bool $shouldRegisterNavigation = false;

    protected static bool $isScopedToTenant = false;

    public static function getBreadcrumb(): string
    {
        return __('vendra-newsletter::navigation.newsletter_send_history');
    }

    public static function getModelLabel(): string
    {
        return __('vendra-newsletter::navigation.newsletter_send_history');
    }

    public static function getNavigationGroup(): string
    {
        return __('vendra-newsletter::navigation.newsletter_management');
    }

    public static function getNavigationLabel(): string
    {
        return __('vendra-newsletter::navigation.newsletter_send_history');
    }

    public static function getPluralModelLabel(): string
    {
        return __('vendra-newsletter::navigation.newsletter_send_history');
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
