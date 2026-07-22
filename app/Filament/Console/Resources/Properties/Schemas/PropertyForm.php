<?php

declare(strict_types=1);

namespace App\Filament\Console\Resources\Properties\Schemas;

use App\Models\Reseller;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Misaf\VendraTenant\Models\TenantDomain;

final class PropertyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('reseller_id')
                    ->label(__('console.reseller'))
                    ->options(fn(): array => Reseller::query()->enabled()->pluck('name', 'id')->all())
                    ->required()
                    ->native(false)
                    ->visibleOn('create'),

                TextInput::make('domain')
                    ->label(__('console.domain'))
                    ->maxLength(255)
                    ->required()
                    ->rules(TenantDomain::activeDomainRules())
                    ->dehydrateStateUsing(fn(?string $state): ?string => null === $state
                        ? null
                        : TenantDomain::normalizeDomain($state))
                    ->visibleOn('create'),

                TextInput::make('email')
                    ->label(__('console.email'))
                    ->email()
                    ->maxLength(255)
                    ->required()
                    ->visibleOn('create'),

                Toggle::make('status')
                    ->label(__('console.status'))
                    ->columnSpanFull()
                    ->default(true)
                    ->required(),
            ])
            ->columns(2);
    }
}
