<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\User\UserProfileBalanceResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

final class UserProfileBalanceRelationManager extends RelationManager
{
    protected static string $relationship = 'userProfileBalances';

    public static function getIcon(Model $ownerRecord, string $pageClass): ?string
    {
        return 'heroicon-o-device-phone-mobile';
    }

    public static function getModelLabel(): string
    {
        return __('model.user_profile_phone');
    }

    public static function getpluralModelLabel(): string
    {
        return __('navigation.user_profile_phone');
    }

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('model.user_profile_phone');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function isReadOnly(): bool
    {
        return true;
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('phone')
            ->heading(__('model.user_profile_phone'))
            ->columns([
                Tables\Columns\TextColumn::make('phone')
                    ->alignCenter()
                    ->extraCellAttributes(['dir' => 'ltr'])
                    ->label(__('form.phone'))
                    ->badge(),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->label(__('form.status')),

                Tables\Columns\TextColumn::make('verified_at')
                    ->alignCenter()
                    ->badge()
                    ->extraCellAttributes(['dir' => 'ltr'])
                    ->jalaliDateTime('Y-m-d H:i')
                    ->label(__('form.verified_at'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->alignCenter()
                    ->extraCellAttributes(['dir' => 'ltr'])
                    ->jalaliDateTime('Y-m-d H:i')
                    ->label(__('form.updated_at'))
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([

            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
