<?php

declare(strict_types=1);

namespace Misaf\VendraCustomPage\Filament\Clusters\Resources\CustomPages\Schemas;

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Unique;
use Livewire\Component as Livewire;
use Misaf\VendraSupport\Support\TenantAwareness;

final class CustomPageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('custom_page_category_id')
                    ->columnSpanFull()
                    ->label(__('vendra-custom-page::navigation.custom_page_category'))
                    ->native(false)
                    ->preload()
                    ->relationship('customPageCategory', 'name')
                    ->required()
                    ->searchable(),

                TextInput::make('name')
                    ->afterStateUpdated(function (Get $get, Set $set, ?string $old, ?string $state): void {
                        if (($get->string('slug', isNullable: true) ?? '') === Str::slug($old ?? '')) {
                            $set('slug', Str::slug($state ?? ''));
                        }
                    })
                    ->autofocus()
                    ->columnSpan(['lg' => 1])
                    ->label(__('vendra-custom-page::attributes.name'))
                    ->live(onBlur: true)
                    ->required()
                    ->unique(
                        modifyRuleUsing: fn(Unique $rule): Unique => TenantAwareness::constrainUniqueRule($rule)
                            ->withoutTrashed(),
                    ),

                TextInput::make('slug')
                    ->afterStateUpdated(fn(Livewire $livewire) => $livewire->validateOnly("data.slug"))
                    ->columnSpan(['lg' => 1])
                    ->helperText(__('vendra-custom-page::attributes.slug_helper_text'))
                    ->label(__('vendra-custom-page::attributes.slug'))
                    ->label(__('vendra-custom-page::attributes.slug'))
                    ->required()
                    ->unique(modifyRuleUsing: fn(Unique $rule) => $rule->withoutTrashed()),

                RichEditor::make('description')
                    ->columnSpanFull()
                    ->label(__('vendra-custom-page::attributes.description'))
                    ->required()
                    ->json(),

                SpatieMediaLibraryFileUpload::make('image')
                    ->collection('custom-pages')
                    ->columnSpanFull()
                    ->image()
                    ->label(__('vendra-custom-page::attributes.image'))
                    ->panelLayout('grid')
                    ->responsiveImages(),

                Toggle::make('status')
                    ->afterStateUpdated(fn(Livewire $livewire) => $livewire->validateOnly("data.status"))
                    ->columnSpanFull()
                    ->default(false)
                    ->label(__('vendra-custom-page::attributes.status'))
                    ->onIcon('heroicon-m-bolt')
                    ->required()
                    ->rules([
                        'boolean',
                    ]),
            ]);
    }
}
