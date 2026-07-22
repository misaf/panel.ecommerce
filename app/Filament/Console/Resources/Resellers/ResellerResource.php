<?php

declare(strict_types=1);

namespace App\Filament\Console\Resources\Resellers;

use App\Filament\Console\Resources\Resellers\Pages\CreateReseller;
use App\Filament\Console\Resources\Resellers\Pages\EditReseller;
use App\Filament\Console\Resources\Resellers\Pages\ListResellers;
use App\Models\Reseller;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Misaf\VendraSubscription\Models\Plan;

final class ResellerResource extends Resource
{
    protected static ?string $model = Reseller::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingOffice2;

    protected static ?string $slug = 'resellers';

    public static function getModelLabel(): string
    {
        return __('console.reseller');
    }

    public static function getPluralModelLabel(): string
    {
        return __('console.resellers');
    }

    public static function getNavigationLabel(): string
    {
        return __('console.resellers');
    }

    public static function getNavigationGroup(): string
    {
        return __('console.navigation_group');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label(__('console.name'))
                    ->maxLength(255)
                    ->required(),

                Select::make('plan_id')
                    ->label(__('console.subscription_plan'))
                    ->options(fn(): array => Plan::query()->enabled()->pluck('name', 'id')->all())
                    ->required()
                    ->native(false)
                    ->visibleOn('create'),

                TextInput::make('owner_name')
                    ->label(__('console.owner_name'))
                    ->maxLength(255),

                TextInput::make('owner_email')
                    ->label(__('console.owner_email'))
                    ->email()
                    ->maxLength(255),

                Toggle::make('status')
                    ->label(__('console.status'))
                    ->columnSpanFull()
                    ->default(true)
                    ->required(),

                Section::make(__('console.current_subscription'))
                    ->visibleOn('edit')
                    ->columns(3)
                    ->schema([
                        Placeholder::make('current_plan')
                            ->label(__('console.plan'))
                            ->content(fn(?Reseller $record): string => $record?->activeSubscription()?->plan->name ?? '—'),

                        Placeholder::make('current_status')
                            ->label(__('console.status'))
                            ->content(fn(?Reseller $record): string => $record?->activeSubscription()?->status->value ?? '—'),

                        Placeholder::make('current_ends_at')
                            ->label(__('console.ends_at'))
                            ->content(fn(?Reseller $record): string => $record?->activeSubscription()?->ends_at?->toDayDateTimeString() ?? '—'),
                    ]),
            ])
            ->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn(Builder $query): Builder => $query->withCount('tenants'))
            ->columns([
                TextColumn::make('name')
                    ->label(__('console.name'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('tenants_count')
                    ->label(__('console.properties_count'))
                    ->alignCenter(),

                IconColumn::make('status')
                    ->label(__('console.status'))
                    ->boolean(),

                TextColumn::make('created_at')
                    ->dateTime('Y-m-d H:i')
                    ->sortable(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('id', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListResellers::route('/'),
            'create' => CreateReseller::route('/create'),
            'edit'   => EditReseller::route('/{record}/edit'),
        ];
    }
}
