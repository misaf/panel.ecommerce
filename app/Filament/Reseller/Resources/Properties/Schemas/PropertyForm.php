<?php

declare(strict_types=1);

namespace App\Filament\Reseller\Resources\Properties\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Misaf\VendraTenant\Models\TenantDomain;

final class PropertyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('domain')
                    ->label(__('console.domain'))
                    ->maxLength(255)
                    ->required()
                    ->rules(TenantDomain::activeDomainRules())
                    ->dehydrateStateUsing(fn(?string $state): ?string => null === $state
                        ? null
                        : TenantDomain::normalizeDomain($state)),

                TextInput::make('email')
                    ->label(__('console.email'))
                    ->email()
                    ->maxLength(255)
                    ->required(),
            ])
            ->columns(2);
    }
}
