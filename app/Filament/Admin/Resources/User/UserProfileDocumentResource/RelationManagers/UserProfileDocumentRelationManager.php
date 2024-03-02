<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\User\UserProfileDocumentResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

final class UserProfileDocumentRelationManager extends RelationManager
{
    protected static string $relationship = 'userProfileDocuments';

    public static function getIcon(Model $ownerRecord, string $pageClass): ?string
    {
        return 'heroicon-o-clipboard-document-check';
    }

    public static function getModelLabel(): string
    {
        return __('navigation.user_profile_document');
    }

    public static function getpluralModelLabel(): string
    {
        return __('navigation.user_profile_document');
    }

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('navigation.user_profile_document');
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
            ->recordTitleAttribute('name')
            ->heading(__('model.user_profile_document'))
            ->columns([
                Tables\Columns\SpatieMediaLibraryImageColumn::make('image')
                    ->circular()
                    ->conversion('thumb-table')
                    ->extraImgAttributes(['class' => 'saturate-50', 'loading' => 'lazy'])
                    ->label(__('form.image'))
                    ->stacked()
                    ->defaultImageUrl(url('coin-payment/images/default.png')),

                Tables\Columns\TextColumn::make('status')
                    ->label(__('form.status'))
                    ->badge(),

                Tables\Columns\TextColumn::make('verified_at')
                    ->alignCenter()
                    ->extraCellAttributes(['dir' => 'ltr'])
                    ->badge()
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
