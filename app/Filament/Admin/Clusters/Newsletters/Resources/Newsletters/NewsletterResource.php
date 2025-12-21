<?php

declare(strict_types=1);

namespace App\Filament\Admin\Clusters\Newsletters\Resources\Newsletters;

use App\Filament\Admin\Clusters\Newsletters\NewslettersCluster;
use App\Filament\Admin\Clusters\Newsletters\Resources\NewsletterPosts\RelationManagers\NewsletterPostRelationManager;
use App\Filament\Admin\Clusters\Newsletters\Resources\Newsletters\Pages\CreateNewsletter;
use App\Filament\Admin\Clusters\Newsletters\Resources\Newsletters\Pages\EditNewsletter;
use App\Filament\Admin\Clusters\Newsletters\Resources\Newsletters\Pages\ListNewsletters;
use App\Filament\Admin\Clusters\Newsletters\Resources\Newsletters\Pages\ViewNewsletter;
use App\Filament\Admin\Clusters\Newsletters\Resources\Newsletters\Schemas\NewsletterForm;
use App\Filament\Admin\Clusters\Newsletters\Resources\Newsletters\Schemas\NewsletterTable;
use App\Filament\Admin\Clusters\Newsletters\Resources\NewsletterSendHistories\RelationManagers\NewsletterSendHistoryRelationManager;
use App\Filament\Admin\Clusters\Newsletters\Resources\NewsletterSubscribers\RelationManagers\NewsletterSubscriberRelationManager;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Support\Number;
use LaraZeus\SpatieTranslatable\Resources\Concerns\Translatable;
use Misaf\Newsletter\Models\Newsletter;

final class NewsletterResource extends Resource
{
    use Translatable;

    protected static ?string $model = Newsletter::class;

    protected static ?int $navigationSort = 1;

    protected static ?string $slug = 'newsletters';

    protected static ?string $cluster = NewslettersCluster::class;

    public static function getBreadcrumb(): string
    {
        return __('newsletter/navigation.newsletter');
    }

    public static function getModelLabel(): string
    {
        return __('newsletter/navigation.newsletter');
    }

    public static function getNavigationGroup(): string
    {
        return __('newsletter/navigation.newsletter_management');
    }

    public static function getNavigationLabel(): string
    {
        return __('newsletter/navigation.newsletter');
    }

    public static function getPluralModelLabel(): string
    {
        return __('newsletter/navigation.newsletter');
    }

    public static function getNavigationBadge(): string
    {
        return (string) Number::format(app('newsletter-service')->getCount());
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListNewsletters::route('/'),
            'create' => CreateNewsletter::route('/create'),
            'view'   => ViewNewsletter::route('/{record}'),
            'edit'   => EditNewsletter::route('/{record}/edit'),
        ];
    }

    public static function getRelations(): array
    {
        return [
            NewsletterPostRelationManager::class,
            NewsletterSubscriberRelationManager::class,
            NewsletterSendHistoryRelationManager::class,
        ];
    }

    public static function form(Schema $schema): Schema
    {
        return NewsletterForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return NewsletterTable::configure($table);
    }
}
