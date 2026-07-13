<?php

declare(strict_types=1);

namespace Misaf\VendraGeo\Filament\Clusters\Resources\Cities\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;
use Livewire\Component as Livewire;
use Misaf\VendraGeo\Models\State;

final class CityForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('state_id')
                    ->afterStateUpdated(function (Set $set, ?int $stateId): void {
                        $set('country_id', State::query()->find($stateId)?->country_id);
                    })
                    ->relationship('state', 'name')
                    ->label(__('vendra-geo::attributes.state'))
                    ->live()
                    ->required()
                    ->searchable()
                    ->preload(),

                Select::make('country_id')
                    ->relationship('country', 'name')
                    ->label(__('vendra-geo::attributes.country'))
                    ->required()
                    ->searchable()
                    ->preload(),

                TextInput::make('name')
                    ->afterStateUpdated(function (Get $get, Set $set, ?string $old, ?string $state): void {
                        if (($get->string('slug', isNullable: true) ?? '') === Str::slug($old ?? '')) {
                            $set('slug', Str::slug($state ?? ''));
                        }
                    })
                    ->autofocus()
                    ->label(__('vendra-geo::attributes.name'))
                    ->live(onBlur: true)
                    ->required(),

                TextInput::make('slug')
                    ->afterStateUpdated(fn(Livewire $livewire) => $livewire->validateOnly('data.slug'))
                    ->helperText(__('vendra-geo::attributes.slug_helper_text'))
                    ->label(__('vendra-geo::attributes.slug'))
                    ->required(),

                TextInput::make('latitude')
                    ->label(__('vendra-geo::attributes.latitude'))
                    ->numeric(),

                TextInput::make('longitude')
                    ->label(__('vendra-geo::attributes.longitude'))
                    ->numeric(),

                Toggle::make('status')
                    ->columnSpanFull()
                    ->default(false)
                    ->label(__('vendra-geo::attributes.status'))
                    ->onIcon('heroicon-m-bolt')
                    ->required()
                    ->rules(['boolean']),
            ]);
    }
}
