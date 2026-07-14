<?php

declare(strict_types=1);

namespace Misaf\VendraNewsletter\Filament\Clusters\Resources\Newsletters;

use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Misaf\VendraNewsletter\Filament\Clusters\NewslettersCluster;
use Misaf\VendraNewsletter\Filament\Clusters\Resources\Newsletters\Pages\CreateNewsletter;
use Misaf\VendraNewsletter\Filament\Clusters\Resources\Newsletters\Pages\EditNewsletter;
use Misaf\VendraNewsletter\Filament\Clusters\Resources\Newsletters\Pages\ListNewsletters;
use Misaf\VendraNewsletter\Filament\Clusters\Resources\Newsletters\Pages\ViewNewsletter;
use Misaf\VendraNewsletter\Filament\Clusters\Resources\Newsletters\Schemas\NewsletterForm;
use Misaf\VendraNewsletter\Filament\Clusters\Resources\Newsletters\Tables\NewsletterTable;
use Misaf\VendraNewsletter\Models\Newsletter;

final class NewsletterResource extends Resource
{
    protected static ?string $model = Newsletter::class;

    protected static ?int $navigationSort = 1;

    protected static ?string $slug = 'newsletters';

    protected static ?string $cluster = NewslettersCluster::class;

    public static function getBreadcrumb(): string
    {
        return __('vendra-newsletter::navigation.newsletter');
    }

    public static function getModelLabel(): string
    {
        return __('vendra-newsletter::navigation.newsletter');
    }

    public static function getNavigationGroup(): string
    {
        return __('vendra-newsletter::navigation.newsletter_management');
    }

    public static function getNavigationLabel(): string
    {
        return __('vendra-newsletter::navigation.newsletter');
    }

    public static function getPluralModelLabel(): string
    {
        return __('vendra-newsletter::navigation.newsletter');
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

    public static function form(Schema $schema): Schema
    {
        return NewsletterForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return NewsletterTable::configure($table);
    }
}
