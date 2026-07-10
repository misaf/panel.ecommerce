<?php

declare(strict_types=1);

namespace Misaf\VendraGeo\Filament\Clusters\Resources\Countries\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Unique;
use Livewire\Component as Livewire;
use Misaf\VendraSupport\Support\TenantAwareness;

final class CountryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
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

                TextInput::make('iso2')
                    ->label(__('vendra-geo::attributes.iso2'))
                    ->length(2)
                    ->required()
                    ->unique(modifyRuleUsing: fn(Unique $rule) => TenantAwareness::constrainUniqueRule($rule)->withoutTrashed()),

                TextInput::make('iso3')
                    ->label(__('vendra-geo::attributes.iso3'))
                    ->length(3),

                TextInput::make('numeric_code')
                    ->label(__('vendra-geo::attributes.numeric_code'))
                    ->maxLength(3),

                TextInput::make('phone_code')
                    ->label(__('vendra-geo::attributes.phone_code'))
                    ->maxLength(16),

                TextInput::make('currency_code')
                    ->label(__('vendra-geo::attributes.currency_code'))
                    ->maxLength(3),

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
