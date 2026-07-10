<?php

declare(strict_types=1);

namespace Misaf\VendraCustomPage\Filament\Clusters\Resources\CustomPages\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Number;
use LaraZeus\SpatieTranslatable\Resources\RelationManagers\Concerns\Translatable;
use Livewire\Attributes\Reactive;
use Misaf\VendraCustomPage\Filament\Clusters\Resources\CustomPages\CustomPageResource;

final class CustomPageRelationManager extends RelationManager
{
    use Translatable;

    #[Reactive]
    public ?string $activeLocale = null;

    protected static string $relationship = 'customPages';

    protected static bool $isLazy = false;

    public static function getModelLabel(): string
    {
        return __('vendra-custom-page::navigation.custom_page');
    }

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('vendra-custom-page::navigation.custom_page');
    }

    public function isReadOnly(): bool
    {
        return false;
    }

    public static function getBadge(Model $ownerRecord, string $pageClass): string
    {
        /** @var Collection<int, CustomPage> $customPages */
        $customPages = $ownerRecord->getRelation('customPages') ?? collect();

        return (string) Number::format($customPages->count());
    }

    public function form(Schema $schema): Schema
    {
        return CustomPageResource::form($schema);
    }

    public function table(Table $table): Table
    {
        return CustomPageResource::table($table)
            ->headerActions([
                CreateAction::make(),
            ]);
    }
}
