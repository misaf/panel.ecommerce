<?php

declare(strict_types=1);

namespace Misaf\VendraAttribute\Filament\Clusters\Resources\Attributes\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieTagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Validation\Rules\Unique;
use Livewire\Component as Livewire;
use Misaf\VendraAttribute\Models\Attribute;
use Misaf\VendraAttribute\Support\AttributeUnits;
use Misaf\VendraSupport\Support\TagIntegration;
use Misaf\VendraSupport\Support\TenantAwareness;

final class AttributeForm
{
    public static function configure(Schema $schema): Schema
    {
        $components = [
            TextInput::make('name')
                ->autofocus()
                ->label(__('vendra-attribute::attributes.name'))
                ->maxLength(255)
                ->required()
                ->unique(
                    modifyRuleUsing: fn(Unique $rule): Unique => TenantAwareness::constrainUniqueRule($rule)
                        ->withoutTrashed(),
                ),

            Select::make('unit')
                ->label(__('vendra-attribute::attributes.unit'))
                ->native(false)
                ->options(AttributeUnits::options())
                ->searchable(),

            Textarea::make('description')
                ->columnSpanFull()
                ->label(__('vendra-attribute::attributes.description'))
                ->rows(4),

            Toggle::make('status')
                ->afterStateUpdated(fn(Livewire $livewire) => $livewire->validateOnly('data.status'))
                ->columnSpanFull()
                ->default(true)
                ->label(__('vendra-attribute::attributes.status'))
                ->onIcon(Heroicon::Bolt)
                ->required()
                ->rules([
                    'boolean',
                ]),
        ];

        if (TagIntegration::isAvailable()) {
            $components[] = SpatieTagsInput::make('tags')
                ->columnSpanFull()
                ->label(__('vendra-support::attributes.tags'))
                ->type(Attribute::TAG_TYPE);
        }

        return $schema
            ->components($components)
            ->columns(2);
    }
}
