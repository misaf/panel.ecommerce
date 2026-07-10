<?php

declare(strict_types=1);

namespace Misaf\VendraFaq\Filament\Clusters\Resources\Faqs;

use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use LaraZeus\SpatieTranslatable\Resources\Concerns\Translatable;
use Misaf\VendraFaq\Filament\Clusters\FaqsCluster;
use Misaf\VendraFaq\Filament\Clusters\Resources\Faqs\Pages\CreateFaq;
use Misaf\VendraFaq\Filament\Clusters\Resources\Faqs\Pages\EditFaq;
use Misaf\VendraFaq\Filament\Clusters\Resources\Faqs\Pages\ListFaqs;
use Misaf\VendraFaq\Filament\Clusters\Resources\Faqs\Pages\ViewFaq;
use Misaf\VendraFaq\Filament\Clusters\Resources\Faqs\Schemas\FaqForm;
use Misaf\VendraFaq\Filament\Clusters\Resources\Faqs\Tables\FaqTable;
use Misaf\VendraFaq\Models\Faq;

final class FaqResource extends Resource
{
    use Translatable;

    protected static ?string $model = Faq::class;

    protected static ?int $navigationSort = 1;

    protected static ?string $slug = 'faqs';

    protected static ?string $cluster = FaqsCluster::class;

    public static function getBreadcrumb(): string
    {
        return __('vendra-faq::navigation.faq');
    }

    public static function getModelLabel(): string
    {
        return __('vendra-faq::navigation.faq');
    }

    public static function getNavigationGroup(): string
    {
        return __('vendra-faq::navigation.faq_management');
    }

    public static function getNavigationLabel(): string
    {
        return __('vendra-faq::navigation.faq');
    }

    public static function getPluralModelLabel(): string
    {
        return __('vendra-faq::navigation.faq');
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListFaqs::route('/'),
            'create' => CreateFaq::route('/create'),
            'view'   => ViewFaq::route('/{record}'),
            'edit'   => EditFaq::route('/{record}/edit'),
        ];
    }

    public static function getDefaultTranslatableLocale(): string
    {
        return app()->getLocale();
    }

    public static function form(Schema $schema): Schema
    {
        return FaqForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FaqTable::configure($table);
    }
}
