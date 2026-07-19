<?php

declare(strict_types=1);

namespace Misaf\VendraCurrency\Filament\Clusters\Resources\CurrencyCategories\Schemas;

use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Unique;
use Livewire\Component as Livewire;
use Misaf\VendraCurrency\Models\CurrencyCategory;
use Misaf\VendraSupport\Support\TenantAwareness;

final class CurrencyCategoryForm
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
                    ->columnSpan(['lg' => 1])
                    ->label(__('vendra-currency::attributes.name'))
                    ->live(onBlur: true)
                    ->maxLength(255)
                    ->required()
                    ->unique(
                        modifyRuleUsing: fn(Unique $rule): Unique => TenantAwareness::constrainUniqueRule($rule)
                            ->withoutTrashed(),
                    ),

                TextInput::make('slug')
                    ->afterStateUpdated(fn(Livewire $livewire) => $livewire->validateOnly('data.slug'))
                    ->columnSpan(['lg' => 1])
                    ->helperText(__('vendra-currency::attributes.slug_helper_text'))
                    ->label(__('vendra-currency::attributes.slug'))
                    ->maxLength(255)
                    ->required()
                    ->unique(
                        modifyRuleUsing: fn(Unique $rule): Unique => TenantAwareness::constrainUniqueRule($rule)
                            ->withoutTrashed(),
                    ),

                Textarea::make('description')
                    ->columnSpanFull()
                    ->label(__('vendra-currency::attributes.description'))
                    ->maxLength(255)
                    ->rows(5),

                SpatieMediaLibraryFileUpload::make('image')
                    ->collection(CurrencyCategory::MEDIA_COLLECTION)
                    ->columnSpanFull()
                    ->image()
                    ->label(__('vendra-currency::attributes.image'))
                    ->panelLayout('grid')
                    ->responsiveImages(),

                Toggle::make('status')
                    ->afterStateUpdated(fn(Livewire $livewire) => $livewire->validateOnly('data.status'))
                    ->columnSpanFull()
                    ->default(false)
                    ->label(__('vendra-currency::attributes.status'))
                    ->onIcon(Heroicon::Bolt)
                    ->required()
                    ->rules([
                        'boolean',
                    ]),
            ]);
    }
}
