<?php

declare(strict_types=1);

namespace Misaf\VendraFaq\Filament\Clusters\Resources\Faqs\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Number;
use LaraZeus\SpatieTranslatable\Resources\RelationManagers\Concerns\Translatable;
use Livewire\Attributes\Reactive;
use Misaf\VendraFaq\Filament\Clusters\Resources\Faqs\FaqResource;
use Misaf\VendraFaq\Models\Faq;

final class FaqRelationManager extends RelationManager
{
    use Translatable;

    #[Reactive]
    public ?string $activeLocale = null;

    protected static string $relationship = 'faqs';

    protected static bool $isLazy = false;

    public static function getModelLabel(): string
    {
        return __('vendra-faq::navigation.faq');
    }

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('vendra-faq::navigation.faq');
    }

    public function isReadOnly(): bool
    {
        return false;
    }

    public static function getBadge(Model $ownerRecord, string $pageClass): string
    {
        /** @var Collection<int, Faq> $faqs */
        $faqs = $ownerRecord->getRelation('faqs') ?? collect();

        return (string) Number::format($faqs->count());
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
