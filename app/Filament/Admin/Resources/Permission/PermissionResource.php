<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Permission;

use App\Filament\Admin\Resources\Permission\PermissionResource\Pages;
use App\Models\Permission\Permission;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Validation\Rules\Unique;

final class PermissionResource extends Resource
{
    protected static ?string $model = Permission::class;

    protected static ?int $navigationSort = 2;

    protected static ?string $slug = 'users/permissions';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->autofocus()
                    ->columnSpan([
                        'lg' => 1,
                    ])
                    ->label(__('form.name'))
                    ->required()
                    ->unique(ignoreRecord: true, modifyRuleUsing: fn(Unique $rule) => $rule->whereNull('deleted_at')),


                Forms\Components\TextInput::make('guard_name')
                    ->columnSpan([
                        'lg' => 1,
                    ])
                    ->label(__('form.guard_name'))
                    ->required(),
            ]);
    }

    public static function getBreadcrumb(): string
    {
        return __('navigation.setting');
    }

    public static function getModelLabel(): string
    {
        return __('model.permission');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('navigation.setting');
    }

    public static function getNavigationLabel(): string
    {
        return __('navigation.permission');
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListPermission::route('/'),
            'create' => Pages\CreatePermission::route('/create'),
            'view'   => Pages\ViewPermission::route('/{record}'),
            'edit'   => Pages\EditPermission::route('/{record}/edit'),
        ];
    }

    public static function getpluralModelLabel(): string
    {
        return __('model.permission');
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('form.name'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('guard_name')
                    ->label(__('form.guard_name'))
                    ->searchable()
                    ->sortable()
                    ->badge(),

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
