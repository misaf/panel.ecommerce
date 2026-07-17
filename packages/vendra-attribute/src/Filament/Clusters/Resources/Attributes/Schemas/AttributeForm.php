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

use Misaf\VendraSupport\Filament\Concerns\InteractsWithTagFields;
use Misaf\VendraSupport\Support\TenantAwareness;

final class AttributeForm
{
    use InteractsWithTagFields;

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
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

                ...self::tagFields(),

                Toggle::make('status')
                    ->columnSpanFull()
                    ->default(true)
                    ->label(__('vendra-attribute::attributes.status'))
                    ->required(),
            ])
            ->columns(2);
    }

}
