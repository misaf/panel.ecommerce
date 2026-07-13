<?php

declare(strict_types=1);

namespace Misaf\VendraLanguage\Filament\Clusters\Resources\LanguageLines\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Number;
use LaraZeus\SpatieTranslatable\Resources\RelationManagers\Concerns\Translatable;
use Livewire\Attributes\Reactive;
use Misaf\VendraLanguage\Filament\Clusters\Resources\LanguageLines\Schemas\LanguageLineForm;
use Misaf\VendraLanguage\Filament\Clusters\Resources\LanguageLines\Tables\LanguageLineTable;
use Misaf\VendraLanguage\Models\LanguageLine;

final class LanguageLineRelationManager extends RelationManager
{
    use Translatable;

    #[Reactive]
    public ?string $activeLocale = null;

    protected static string $relationship = 'languageLines';

    protected static bool $isLazy = false;

    public static function getModelLabel(): string
    {
        return __('vendra-language::navigation.language');
    }

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('vendra-language::navigation.language');
    }

    public function isReadOnly(): bool
    {
        return false;
    }

    public static function getBadge(Model $ownerRecord, string $pageClass): string
    {
        /** @var Collection<int, LanguageLine> $languageLines */
        $languageLines = $ownerRecord->getRelation('languageLines') ?? collect();

        return (string) Number::format($languageLines->count());
    }

    public function form(Schema $schema): Schema
    {
        return LanguageLineForm::configure($schema);
    }

    public function table(Table $table): Table
    {
        return LanguageLineTable::configure($table)
            ->headerActions([
                CreateAction::make(),
            ]);
    }
}
