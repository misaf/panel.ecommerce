<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Geographical;

use App\Filament\Admin\Resources\Geographical\GeographicalCountryResource\Pages;
use App\Models\Geographical\GeographicalCountry;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Unique;

final class GeographicalCountryResource extends Resource
{
    protected static ?string $model = GeographicalCountry::class;

    protected static ?int $navigationSort = 2;

    protected static ?string $slug = 'geographicals/countries';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('geographical_zone_id')
                    ->columnSpanFull()
                    ->label(__('model.geographical_zone'))
                    ->native(false)
                    ->preload()
                    ->relationship('geographicalZone', 'name')
                    ->required()
                    ->searchable(),

                Forms\Components\TextInput::make('name')
                    ->afterStateUpdated(function (Get $get, Set $set, ?string $old, ?string $state): void {
                        if (($get('slug') ?? '') !== Str::slug($old)) {
                            return;
                        }

                        $set('slug', Str::slug($state));
                    })
                    ->autofocus()
                    ->columnSpan([
                        'lg' => 1,
                    ])
                    ->label(__('form.name'))
                    ->live(onBlur: true)
                    ->required()
                    ->unique(ignoreRecord: true, modifyRuleUsing: fn(Unique $rule) => $rule->whereNull('deleted_at')),

                Forms\Components\TextInput::make('slug')
                    ->columnSpan([
                        'lg' => 1,
                    ])
                    ->label(__('form.slug'))
                    ->required()
                    ->unique(ignoreRecord: true, modifyRuleUsing: fn(Unique $rule) => $rule->whereNull('deleted_at')),

                Forms\Components\Textarea::make('description')
                    ->columnSpanFull()
                    ->label(__('form.description'))
                    ->rows(5),

                Forms\Components\SpatieMediaLibraryFileUpload::make('image')
                    ->collection('geographicals/countries')
                    ->columnSpanFull()
                    ->image()
                    ->label(__('form.image')),

                Forms\Components\Toggle::make('status')
                    ->columnSpanFull()
                    ->label(__('form.status'))
                    ->rules('required'),
            ]);
    }

    public static function getBreadcrumb(): string
    {
        return __('navigation.geographical');
    }

    public static function getModelLabel(): string
    {
        return __('model.geographical_country');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('navigation.geographical');
    }

    public static function getNavigationLabel(): string
    {
        return __('model.geographical_country');
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListGeographicalCountry::route('/'),
            'create' => Pages\CreateGeographicalCountry::route('/create'),
            'view'   => Pages\ViewGeographicalCountry::route('/{record}'),
            'edit'   => Pages\EditGeographicalCountry::route('/{record}/edit'),
        ];
    }

    public static function getpluralModelLabel(): string
    {
        return __('model.geographical_country');
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\SpatieMediaLibraryImageColumn::make('image')
                    ->circular()
                    ->conversion('thumb-table')
                    ->extraImgAttributes(['class' => 'saturate-50', 'loading' => 'lazy'])
                    ->label(__('form.image'))
                    ->stacked()
                    ->defaultImageUrl(url('coin-payment/images/default.png')),

                Tables\Columns\TextColumn::make('geographicalZone.name')
                    ->label(__('model.geographical_zone'))
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('name')
                    ->label(__('form.name'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\ToggleColumn::make('status')
                    ->label(__('form.status')),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('form.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('form.updated_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ])
            ->defaultSort('id', 'desc');
    }
}
