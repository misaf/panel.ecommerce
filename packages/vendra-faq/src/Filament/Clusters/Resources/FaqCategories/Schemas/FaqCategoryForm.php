<?php

declare(strict_types=1);

namespace Misaf\VendraFaq\Filament\Clusters\Resources\FaqCategories\Schemas;

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

final class FaqCategoryForm
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
                    ->label(__('vendra-faq::attributes.name'))
                    ->live(onBlur: true)
                    ->required()
                    ->unique(
                        modifyRuleUsing: fn(Unique $rule): Unique => TenantAwareness::constrainUniqueRule($rule)
                            ->withoutTrashed(),
                    ),

                TextInput::make('slug')
                    ->afterStateUpdated(fn(Livewire $livewire) => $livewire->validateOnly("data.slug"))
                    ->columnSpan(['lg' => 1])
                    ->helperText(__('vendra-faq::attributes.slug_helper_text'))
                    ->label(__('vendra-faq::attributes.slug'))
                    ->label(__('vendra-faq::attributes.slug'))
                    ->required()
                    ->unique(modifyRuleUsing: fn(Unique $rule) => $rule->withoutTrashed()),

                Textarea::make('description')
                    ->columnSpanFull()
                    ->label(__('vendra-faq::attributes.description'))
                    ->rows(5),

                SpatieMediaLibraryFileUpload::make('image')
                    ->collection('faqs/categories')
                    ->columnSpanFull()
                    ->image()
                    ->label(__('vendra-faq::attributes.image'))
                    ->panelLayout('grid')
                    ->responsiveImages(),

                Toggle::make('status')
                    ->afterStateUpdated(fn(Livewire $livewire) => $livewire->validateOnly("data.status"))
                    ->columnSpanFull()
                    ->default(false)
                    ->label(__('vendra-faq::attributes.status'))
                    ->onIcon('heroicon-m-bolt')
                    ->required()
                    ->rules([
                        'boolean',
                    ]),
            ]);
    }
}
