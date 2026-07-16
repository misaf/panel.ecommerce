<?php

declare(strict_types=1);

namespace Misaf\VendraDocument\Filament\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

final class DocumentsRelationManager extends RelationManager
{
    protected static string $relationship = 'documents';

    public static function getModelLabel(): string
    {
        return __('vendra-document::document.document');
    }

    public static function getPluralModelLabel(): string
    {
        return __('vendra-document::document.documents');
    }

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('vendra-document::document.documents');
    }

    public function isReadOnly(): bool
    {
        return false;
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('type')->label(__('vendra-document::document.fields.type'))->required(),
            TextInput::make('issuing_country_code')
                ->label(__('vendra-document::document.fields.issuing_country_code'))
                ->length(2),
            TextInput::make('number')->label(__('vendra-document::document.fields.number')),
            FileUpload::make('path')
                ->label(__('vendra-document::document.fields.path'))
                ->directory('user-profiles/documents')
                ->disk('local')
                ->visibility('private')
                ->downloadable()
                ->required(),
            DatePicker::make('issued_at')->label(__('vendra-document::document.fields.issued_at')),
            DatePicker::make('expires_at')
                ->label(__('vendra-document::document.fields.expires_at'))
                ->afterOrEqual('issued_at'),
            KeyValue::make('metadata')
                ->label(__('vendra-document::document.fields.metadata'))
                ->columnSpanFull(),
            Textarea::make('notes')
                ->label(__('vendra-document::document.fields.notes'))
                ->columnSpanFull(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('type')->label(__('vendra-document::document.fields.type'))->badge(),
                TextColumn::make('number')->label(__('vendra-document::document.fields.number'))->copyable(),
                TextColumn::make('issuing_country_code')
                    ->label(__('vendra-document::document.fields.issuing_country_code'))
                    ->badge(),
                TextColumn::make('expires_at')->label(__('vendra-document::document.fields.expires_at'))->date(),
                IconColumn::make('verified_at')
                    ->label(__('vendra-document::document.fields.verified_at'))
                    ->boolean(),
            ])
            ->headerActions([CreateAction::make()])
            ->recordActions([EditAction::make(), DeleteAction::make()]);
    }
}
