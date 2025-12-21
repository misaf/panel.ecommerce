<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Tags\Tags;

use App\Filament\Admin\Resources\Tags\Pages\CreateTag;
use App\Filament\Admin\Resources\Tags\Pages\EditTag;
use App\Filament\Admin\Resources\Tags\Pages\ListTags;
use App\Tables\Columns\CreatedAtTextColumn;
use App\Tables\Columns\UpdatedAtTextColumn;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\PageRegistration;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\QueryBuilder;
use Filament\Tables\Filters\QueryBuilder\Constraints\DateConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\TextConstraint;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Unique;
use LaraZeus\SpatieTranslatable\Resources\Concerns\Translatable;
use Livewire\Component as Livewire;
use Misaf\Tag\Models\Tag;
use Misaf\Tenant\Models\Tenant;

final class TagResource extends Resource
{
    use Translatable;

    protected static ?string $model = Tag::class;

    protected static ?int $navigationSort = 1;

    protected static ?string $slug = 'tags';

    public static function getNavigationGroup(): string
    {
        return __('navigation.content_management');
    }

    public static function getBreadcrumb(): string
    {
        return __('tag::navigation.tag');
    }

    public static function getModelLabel(): string
    {
        return __('tag::navigation.tag');
    }

    public static function getNavigationLabel(): string
    {
        return __('tag::navigation.tag');
    }

    public static function getPluralModelLabel(): string
    {
        return __('tag::navigation.tag');
    }

    /**
     * @return array<string, PageRegistration>
     */
    public static function getPages(): array
    {
        return [
            'create' => CreateTag::route('/create'),
            'edit'   => EditTag::route('/{record}/edit'),
            'index'  => ListTags::route('/'),
        ];
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('type')
                    ->columnSpanFull()
                    ->label(__('tag::attributes.type')),
                TextInput::make('name')
                    ->afterStateUpdated(function (Get $get, Set $set, ?string $old, ?string $state): void {
                        if (($get('slug') ?? '') === Str::slug($old ?? '')) {
                            $set('slug', Str::slug($state));
                        }
                    })
                    ->autofocus()
                    ->columnSpan(['lg' => 1])
                    ->label(__('tag::attributes.name'))
                    ->live(onBlur: true)
                    ->required()
                    ->unique(
                        column: fn(Livewire $livewire) => 'name->' . $livewire->activeLocale,
                        modifyRuleUsing: function (Unique $rule): void {
                            $rule->where('tenant_id', Tenant::current()->id);
                        },
                    ),
                TextInput::make('slug')
                    ->afterStateUpdated(function (Livewire $livewire): void {
                        $livewire->validateOnly("data.{$livewire->getName()}");
                    })
                    ->columnSpan(['lg' => 1])
                    ->helperText(__('شناسه یکتای URL, نیازی به وارد کردن این قسمت نمی باشد به صورت خودکار بعد از وارد کردن فیلد نام پر می گردد.'))
                    ->label(__('tag::attributes.slug'))
                    ->live(onBlur: true)
                    ->required()
                    ->unique(
                        modifyRuleUsing: function (Unique $rule): void {
                            $rule->where('tenant_id', Tenant::current()->id);
                        },
                    ),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('row')
                    ->label('#')
                    ->rowIndex(),

                TextColumn::make('type')
                    ->label(__('tag::attributes.type')),
                TextColumn::make('name')
                    ->label(__('tag::attributes.name'))
                    ->searchable(),
                CreatedAtTextColumn::make('created_at')
                    ->label(__('tag::attributes.created_at')),
                UpdatedAtTextColumn::make('updated_at')
                    ->label(__('tag::attributes.updated_at')),
            ])
            ->filters(
                [
                    QueryBuilder::make()
                        ->constraints([
                            TextConstraint::make('type')
                                ->label(__('tag::attributes.type')),
                            TextConstraint::make('name')
                                ->label(__('tag::attributes.name')),
                            DateConstraint::make('created_at')
                                ->label(__('tag::attributes.created_at')),
                            DateConstraint::make('updated_at')
                                ->label(__('tag::attributes.updated_at')),
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
            ->paginatedWhileReordering()
            ->reorderable('position');
    }
}
