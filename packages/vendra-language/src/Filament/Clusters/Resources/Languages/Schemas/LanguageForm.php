<?php

declare(strict_types=1);

namespace Misaf\VendraLanguage\Filament\Clusters\Resources\Languages\Schemas;

use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Unique;
use Livewire\Component as Livewire;
use Misaf\VendraSupport\Support\TenantAwareness;

final class LanguageForm
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
                    ->label(__('vendra-language::attributes.name'))
                    ->live(onBlur: true)
                    ->required()
                    ->unique(
                        modifyRuleUsing: fn(Unique $rule): Unique => TenantAwareness::constrainUniqueRule($rule)
                            ->withoutTrashed(),
                    ),

                TextInput::make('slug')
                    ->afterStateUpdated(fn(Livewire $livewire) => $livewire->validateOnly('data.slug'))
                    ->columnSpan(['lg' => 1])
                    ->helperText(__('vendra-language::attributes.slug_helper_text'))
                    ->label(__('vendra-language::attributes.slug'))
                    ->required()
                    ->unique(modifyRuleUsing: fn(Unique $rule) => $rule->withoutTrashed()),

                TextInput::make('iso_code')
                    ->columnSpan(['lg' => 1])
                    ->label(__('vendra-language::attributes.iso_code'))
                    ->maxLength(8)
                    ->required(),

                Textarea::make('description')
                    ->columnSpanFull()
                    ->label(__('vendra-language::attributes.description'))
                    ->rows(5),

                SpatieMediaLibraryFileUpload::make('image')
                    ->collection('languages')
                    ->columnSpanFull()
                    ->image()
                    ->label(__('vendra-language::attributes.image'))
                    ->panelLayout('grid')
                    ->responsiveImages(),

                Toggle::make('is_default')
                    ->columnSpan(['lg' => 1])
                    ->default(false)
                    ->label(__('vendra-language::attributes.is_default'))
                    ->required(),

                Toggle::make('status')
                    ->afterStateUpdated(fn(Livewire $livewire) => $livewire->validateOnly('data.status'))
                    ->columnSpan(['lg' => 1])
                    ->default(false)
                    ->label(__('vendra-language::attributes.status'))
                    ->onIcon('heroicon-m-bolt')
                    ->required()
                    ->rules([
                        'boolean',
                    ]),
            ]);
    }
}
