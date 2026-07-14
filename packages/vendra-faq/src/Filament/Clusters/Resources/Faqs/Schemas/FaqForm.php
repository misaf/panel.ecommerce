<?php

declare(strict_types=1);

namespace Misaf\VendraFaq\Filament\Clusters\Resources\Faqs\Schemas;

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
use Misaf\VendraSupport\Support\TagIntegration;
use Misaf\VendraSupport\Support\TenantAwareness;

final class FaqForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('faq_category_id')
                    ->columnSpanFull()
                    ->label(__('vendra-faq::navigation.faq_category'))
                    ->native(false)
                    ->preload()
                    ->relationship('faqCategory', 'name')
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
                    ->required()
                    ->unique(modifyRuleUsing: fn(Unique $rule) => $rule->withoutTrashed()),

                RichEditor::make('description')
                    ->columnSpanFull()
                    ->label(__('vendra-faq::attributes.description'))
                    ->required()
                    ->json(),

                ...self::tagFields(),

                SpatieMediaLibraryFileUpload::make('image')
                    ->collection('faqs')
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

    /** @return list<Select> */
    private static function tagFields(): array
    {
        if ( ! TagIntegration::isAvailable()) {
            return [];
        }

        return [
            Select::make('tags')
                ->columnSpanFull()
                ->label(__('vendra-faq::attributes.tags'))
                ->multiple()
                ->native(false)
                ->preload()
                ->relationship('tags', 'name'),
        ];
    }
}
