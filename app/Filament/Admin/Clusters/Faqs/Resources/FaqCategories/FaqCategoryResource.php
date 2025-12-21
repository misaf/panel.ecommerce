<?php

declare(strict_types=1);

namespace App\Filament\Admin\Clusters\Faqs\Resources\FaqCategories;

use App\Filament\Admin\Clusters\Faqs\FaqsCluster;
use App\Filament\Admin\Clusters\Faqs\Resources\FaqCategories\Pages\CreateFaqCategory;
use App\Filament\Admin\Clusters\Faqs\Resources\FaqCategories\Pages\EditFaqCategory;
use App\Filament\Admin\Clusters\Faqs\Resources\FaqCategories\Pages\ListFaqCategories;
use App\Filament\Admin\Clusters\Faqs\Resources\FaqCategories\Pages\ViewFaqCategory;
use App\Filament\Admin\Clusters\Faqs\Resources\Faqs\RelationManagers\FaqRelationManager;
use App\Forms\Components\SlugTextInput;
use App\Forms\Components\StatusToggle;
use App\Forms\Components\TranslatableDescriptionTextarea;
use App\Tables\Columns\CreatedAtTextColumn;
use App\Tables\Columns\NameTextColumn;
use App\Tables\Columns\StatusToggleColumn;
use App\Tables\Columns\UpdatedAtTextColumn;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Clusters\Cluster;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\QueryBuilder;
use Filament\Tables\Filters\QueryBuilder\Constraints\BooleanConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\DateConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\TextConstraint;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Unique;
use LaraZeus\SpatieTranslatable\Resources\Concerns\Translatable;
use Livewire\Component as Livewire;
use Misaf\Faq\Models\FaqCategory;
use Misaf\Tenant\Models\Tenant;

final class FaqCategoryResource extends Resource
{
    use Translatable;

    protected static ?string $model = FaqCategory::class;

    protected static ?int $navigationSort = 2;

    protected static ?string $slug = 'categories';

    /**
     * @var class-string<Cluster>|null
     */
    protected static ?string $cluster = FaqsCluster::class;

    public static function getBreadcrumb(): string
    {
        return __('navigation.faq_category');
    }

    public static function getDefaultTranslatableLocale(): string
    {
        return app()->getLocale();
    }

    public static function getModelLabel(): string
    {
        return __('navigation.faq_category');
    }

    public static function getNavigationGroup(): string
    {
        return __('navigation.faq_management');
    }

    public static function getNavigationLabel(): string
    {
        return __('navigation.faq_category');
    }

    public static function getPluralModelLabel(): string
    {
        return __('navigation.faq_category');
    }

    /**
     * @return array<string, PageRegistration>
     */
    public static function getPages(): array
    {
        return [
            'index'  => ListFaqCategories::route('/'),
            'create' => CreateFaqCategory::route('/create'),
            'view'   => ViewFaqCategory::route('/{record}'),
            'edit'   => EditFaqCategory::route('/{record}/edit'),
        ];
    }

    public static function getRelations(): array
    {
        return [
            FaqRelationManager::class,
        ];
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->afterStateUpdated(function (Get $get, Set $set, ?string $old, ?string $state): void {
                        if (($get('slug') ?? '') === Str::slug($old ?? '')) {
                            $set('slug', Str::slug($state));
                        }
                    })
                    ->autofocus()
                    ->columnSpan(['lg' => 1])
                    ->label(__('form.name'))
                    ->live(onBlur: true)
                    ->required()
                    ->unique(
                        column: fn(Livewire $livewire) => 'name->' . $livewire->activeLocale,
                        modifyRuleUsing: function (Unique $rule): void {
                            $rule->where('tenant_id', Tenant::current()->id)
                                ->withoutTrashed();
                        },
                    ),

                SlugTextInput::make('slug'),
                TranslatableDescriptionTextarea::make('description'),
                SpatieMediaLibraryFileUpload::make('image')
                    ->columnSpanFull()
                    ->image()
                    ->label(__('form.image'))
                    ->panelLayout('grid'),

                StatusToggle::make('status'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('row')
                    ->label('#')
                    ->rowIndex(),

                SpatieMediaLibraryImageColumn::make('image')
                    ->circular()
                    ->conversion('thumb-table')
                    ->defaultImageUrl(url('coin-payment/images/default.png'))
                    ->extraImgAttributes(['class' => 'saturate-50', 'loading' => 'lazy'])
                    ->label(__('form.image'))
                    ->stacked(),

                NameTextColumn::make('name'),
                StatusToggleColumn::make('status'),
                CreatedAtTextColumn::make('created_at'),
                UpdatedAtTextColumn::make('updated_at'),
            ])
            ->filters(
                [
                    QueryBuilder::make()
                        ->constraints([
                            TextConstraint::make('name'),
                            BooleanConstraint::make('status'),
                            DateConstraint::make('created_at')
                                ->label(__('form.created_at')),
                            DateConstraint::make('updated_at')
                                ->label(__('form.updated_at')),
                        ]),
                ],
                layout: FiltersLayout::AboveContentCollapsible,
            )
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),

                    EditAction::make(),

                    DeleteAction::make(),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
