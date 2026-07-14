<?php

declare(strict_types=1);

namespace Misaf\VendraAttribute\Filament\Clusters\Resources\Attributes\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Validation\Rules\Unique;
use Misaf\VendraAttribute\Support\AttributeUnits;
use Misaf\VendraSupport\Support\TagIntegration;
use Misaf\VendraSupport\Support\TenantAwareness;

final class AttributeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->autofocus()
                    ->label(trans_choice('vendra-attribute::attributes.name', 1))
                    ->maxLength(255)
                    ->required()
                    ->unique(
                        modifyRuleUsing: fn(Unique $rule): Unique => TenantAwareness::constrainUniqueRule($rule)
                            ->withoutTrashed(),
                    ),

                Select::make('unit')
                    ->label(trans_choice('vendra-attribute::attributes.unit', 1))
                    ->native(false)
                    ->options(AttributeUnits::options())
                    ->searchable(),

                Textarea::make('description')
                    ->columnSpanFull()
                    ->label(trans_choice('vendra-attribute::attributes.description', 1))
                    ->rows(4),

                ...self::tagFields(),

                Toggle::make('status')
                    ->columnSpanFull()
                    ->default(true)
                    ->label(trans_choice('vendra-attribute::attributes.status', 1))
                    ->required(),
            ])
            ->columns(2);
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
                ->label(trans_choice('vendra-attribute::attributes.tags', 2))
                ->multiple()
                ->native(false)
                ->preload()
                ->relationship('tags', 'name'),
        ];
    }
}
