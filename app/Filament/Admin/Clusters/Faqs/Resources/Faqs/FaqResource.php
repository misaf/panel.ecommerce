<?php

declare(strict_types=1);

namespace App\Filament\Admin\Clusters\Faqs\Resources\Faqs;

use App\Filament\Admin\Clusters\Faqs\FaqsCluster;
use App\Filament\Admin\Clusters\Faqs\Resources\Faqs\Pages\CreateFaq;
use App\Filament\Admin\Clusters\Faqs\Resources\Faqs\Pages\EditFaq;
use App\Filament\Admin\Clusters\Faqs\Resources\Faqs\Pages\ListFaqs;
use App\Filament\Admin\Clusters\Faqs\Resources\Faqs\Pages\ViewFaq;
use App\Forms\Components\SlugTextInput;
use App\Forms\Components\StatusToggle;
use App\Forms\Components\WysiwygEditor;
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
use Filament\Forms\Components\Select;
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
use Misaf\Faq\Models\Faq;
use Misaf\Faq\Models\FaqCategory;
use Misaf\Tenant\Models\Tenant;

final class FaqResource extends Resource
{
    use Translatable;

    protected static ?string $model = Faq::class;

    protected static ?int $navigationSort = 1;

    protected static ?string $slug = 'faqs';

    /**
     * @var class-string<Cluster>|null
     */
    protected static ?string $cluster = FaqsCluster::class;

    public static function getBreadcrumb(): string
    {
        return __('navigation.faq');
    }

    public static function getDefaultTranslatableLocale(): string
    {
        return app()->getLocale();
    }

    public static function getModelLabel(): string
    {
        return __('navigation.faq');
    }

    public static function getNavigationGroup(): string
    {
        return __('navigation.faq_management');
    }

    public static function getNavigationLabel(): string
    {
        return __('navigation.faq');
    }

    public static function getPluralModelLabel(): string
    {
        return __('navigation.faq');
    }

    /**
     * @return array<string, PageRegistration>
     */
    public static function getPages(): array
    {
        return [
            'index'  => ListFaqs::route('/'),
            'create' => CreateFaq::route('/create'),
            'view'   => ViewFaq::route('/{record}'),
            'edit'   => EditFaq::route('/{record}/edit'),
        ];
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('faq_category_id')
                    ->columnSpanFull()
                    ->getOptionLabelFromRecordUsing(fn(FaqCategory $record, $livewire) => $record->getTranslation('name', $livewire->activeLocale))
                    ->label(__('model.faq_category'))
                    ->native(false)
                    ->preload()
                    ->relationship('faqCategory', 'name')
                    ->required()
                    ->searchable(),

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
                WysiwygEditor::make('description'),
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
            ])
            ->defaultSort('position', 'desc')
            ->paginatedWhileReordering()
            ->reorderable('position');
    }
}
