<?php

declare(strict_types=1);

namespace Misaf\VendraNewsletter\Filament\Clusters\Resources\NewsletterSubscribers;

use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Misaf\VendraNewsletter\Filament\Clusters\Resources\NewsletterSubscribers\Pages\CreateNewsletterSubscriber;
use Misaf\VendraNewsletter\Filament\Clusters\Resources\NewsletterSubscribers\Pages\EditNewsletterSubscriber;
use Misaf\VendraNewsletter\Filament\Clusters\Resources\NewsletterSubscribers\Pages\ListNewsletterSubscribers;
use Misaf\VendraNewsletter\Filament\Clusters\Resources\NewsletterSubscribers\Pages\ViewNewsletterSubscriber;
use Misaf\VendraNewsletter\Filament\Clusters\Resources\NewsletterSubscribers\Schemas\NewsletterSubscriberForm;
use Misaf\VendraNewsletter\Filament\Clusters\Resources\NewsletterSubscribers\Tables\NewsletterSubscriberTable;
use Misaf\VendraNewsletter\Filament\Clusters\Resources\NewsletterSubscribers\Widgets\NewsletterSubscriberOverviewWidget;
use Misaf\VendraNewsletter\Models\NewsletterSubscriber;
use Misaf\VendraSupport\Filament\Clusters\MarketingCluster;

final class NewsletterSubscriberResource extends Resource
{
    protected static ?string $model = NewsletterSubscriber::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    protected static ?int $navigationSort = 2;

    protected static ?string $slug = 'newsletter-subscribers';

    protected static ?string $cluster = MarketingCluster::class;

    public static function getBreadcrumb(): string
    {
        return __('vendra-newsletter::navigation.newsletter_subscriber');
    }

    public static function getModelLabel(): string
    {
        return __('vendra-newsletter::navigation.newsletter_subscriber');
    }

    public static function getNavigationGroup(): string
    {
        return __('vendra-newsletter::navigation.newsletter_management');
    }

    public static function getNavigationLabel(): string
    {
        return __('vendra-newsletter::navigation.newsletter_subscriber');
    }

    public static function getPluralModelLabel(): string
    {
        return __('vendra-newsletter::navigation.newsletter_subscriber');
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListNewsletterSubscribers::route('/'),
            'create' => CreateNewsletterSubscriber::route('/create'),
            'view'   => ViewNewsletterSubscriber::route('/{record}'),
            'edit'   => EditNewsletterSubscriber::route('/{record}/edit'),
        ];
    }

    public static function getWidgets(): array
    {
        return [
            NewsletterSubscriberOverviewWidget::class,
        ];
    }

    public static function form(Schema $schema): Schema
    {
        return NewsletterSubscriberForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return NewsletterSubscriberTable::configure($table);
    }
}
