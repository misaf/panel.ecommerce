<?php

declare(strict_types=1);

namespace Misaf\VendraLanguage\Filament\Clusters\Resources\LanguageLines\Schemas;

use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Validation\Rules\Unique;
use Livewire\Component as Livewire;
use Misaf\VendraLanguage\Enums\LanguageLineGroupEnum;
use Misaf\VendraSupport\Support\TenantAwareness;

final class LanguageLineForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('group')
                    ->columnSpan(['lg' => 1])
                    ->label(__('vendra-language::attributes.group'))
                    ->native(false)
                    ->options(LanguageLineGroupEnum::class)
                    ->required()
                    ->searchable(),

                TextInput::make('key')
                    ->afterStateUpdated(fn(Livewire $livewire) => $livewire->validateOnly('data.key'))
                    ->autofocus()
                    ->columnSpan(['lg' => 1])
                    ->label(__('vendra-language::attributes.key'))
                    ->maxLength(255)
                    ->required()
                    ->unique(
                        ignoreRecord: true,
                        modifyRuleUsing: fn(Unique $rule): Unique => TenantAwareness::constrainUniqueRule($rule),
                    ),

                KeyValue::make('text')
                    ->columnSpanFull()
                    ->default(['en' => ''])
                    ->keyLabel(__('vendra-language::attributes.locale'))
                    ->label(__('vendra-language::attributes.text'))
                    ->required()
                    ->valueLabel(__('vendra-language::attributes.translation')),
            ]);
    }
}
