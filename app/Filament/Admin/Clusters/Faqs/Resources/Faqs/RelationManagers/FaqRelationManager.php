<?php

declare(strict_types=1);

namespace App\Filament\Admin\Clusters\Faqs\Resources\Faqs\RelationManagers;

use App\Filament\Admin\Clusters\Faqs\Resources\Faqs\FaqResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Number;
use LaraZeus\SpatieTranslatable\Resources\RelationManagers\Concerns\Translatable;
use Livewire\Attributes\Reactive;

final class FaqRelationManager extends RelationManager
{
    use Translatable;

    #[Reactive]
    public ?string $activeLocale = null;

    protected static string $relationship = 'faqs';

    public static function getModelLabel(): string
    {
        return __('navigation.faq');
    }

    public static function getPluralModelLabel(): string
    {
        return __('navigation.faq');
    }

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('navigation.faq');
    }

    public function isReadOnly(): bool
    {
        return false;
    }

    public static function getBadge(Model $ownerRecord, string $pageClass): string
    {
        return (string) Number::format($ownerRecord->faqs()->count());
    }

    public function form(Schema $schema): Schema
    {
        return FaqResource::form($schema);
    }

    public function table(Table $table): Table
    {
        return FaqResource::table($table)
            ->headerActions([
                CreateAction::make(),
            ]);
    }
}
