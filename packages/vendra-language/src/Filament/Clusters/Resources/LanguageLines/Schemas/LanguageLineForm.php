<?php

declare(strict_types=1);

namespace Misaf\VendraLanguage\Filament\Clusters\Resources\LanguageLines\Schemas;

use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Validation\Rules\Unique;
use Livewire\Component as Livewire;
use Misaf\VendraLanguage\Support\Locales;
use Misaf\VendraLanguage\Support\TranslationCatalog;
use Misaf\VendraSupport\Support\TenantAwareness;

final class LanguageLineForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('namespace')
                    ->columnSpan(['lg' => 1])
                    ->helperText(__('vendra-language::attributes.namespace_help'))
                    ->label(__('vendra-language::attributes.namespace'))
                    ->live()
                    ->native(false)
                    ->options(fn(TranslationCatalog $catalog): array => $catalog->namespaceOptions())
                    ->placeholder(__('vendra-language::attributes.namespace_none'))
                    ->preload()
                    ->searchable()
                    ->afterStateUpdated(function (Set $set): void {
                        $set('group', null);
                        $set('key', null);
                    }),

                Select::make('group')
                    ->columnSpan(['lg' => 1])
                    ->helperText(__('vendra-language::attributes.group_help'))
                    ->label(__('vendra-language::attributes.group'))
                    ->live()
                    ->native(false)
                    ->options(fn(Get $get, TranslationCatalog $catalog): array => $catalog->groupOptions(
                        $get->string('namespace', isNullable: true),
                    ))
                    ->preload()
                    ->required()
                    ->searchable()
                    ->afterStateUpdated(function (Set $set): void {
                        $set('key', null);
                    }),

                Select::make('key')
                    ->afterStateUpdated(function (Livewire $livewire): void {
                        $livewire->validateOnly('data.key');
                    })
                    ->autofocus()
                    ->columnSpan(['lg' => 1])
                    ->label(__('vendra-language::attributes.key'))
                    ->native(false)
                    ->options(fn(Get $get, TranslationCatalog $catalog): array => $catalog->keyOptions(
                        $get->string('namespace', isNullable: true),
                        $get->string('group', isNullable: true),
                    ))
                    ->preload()
                    ->required()
                    ->searchable()
                    ->unique(
                        ignoreRecord: true,
                        modifyRuleUsing: function (Unique $rule, Get $get): void {
                            TenantAwareness::constrainUniqueRule($rule);

                            $group = $get->string('group', isNullable: true);

                            if (null !== $group) {
                                $rule->where('group', $group);
                            }

                            $namespace = $get->string('namespace', isNullable: true);

                            if (null === $namespace) {
                                $rule->whereNull('namespace');
                            } else {
                                $rule->where('namespace', $namespace);
                            }
                        },
                    ),

                KeyValue::make('text')
                    ->columnSpanFull()
                    ->default(fn(): array => Locales::translationDefaults())
                    ->keyLabel(__('vendra-language::attributes.locale'))
                    ->label(__('vendra-language::attributes.text'))
                    ->required()
                    ->valueLabel(__('vendra-language::attributes.translation')),
            ]);
    }
}
