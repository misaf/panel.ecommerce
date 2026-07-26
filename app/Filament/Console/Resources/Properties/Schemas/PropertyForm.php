<?php

declare(strict_types=1);

namespace App\Filament\Console\Resources\Properties\Schemas;

use App\Models\Reseller;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Livewire\Component as Livewire;
use Misaf\LaravelEmailValidation\Rules\EmailValidation;
use Misaf\VendraTenant\Models\TenantDomain;

final class PropertyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('reseller_id')
                    ->afterStateUpdated(fn(Livewire $livewire) => $livewire->validateOnly('data.reseller_id'))
                    ->label(__('console.reseller'))
                    ->live()
                    ->options(fn(): array => Reseller::query()->enabled()->pluck('name', 'id')->all())
                    ->required()
                    ->native(false)
                    ->visibleOn('create'),

                TextInput::make('domain')
                    ->afterStateUpdated(fn(Livewire $livewire) => $livewire->validateOnly('data.domain'))
                    ->helperText(__('console.domain_helper_text'))
                    ->label(__('console.domain'))
                    ->live(onBlur: true)
                    ->maxLength(255)
                    ->required()
                    ->rules(TenantDomain::activeDomainRules())
                    ->dehydrateStateUsing(fn(?string $state): ?string => null === $state
                        ? null
                        : TenantDomain::normalizeDomain($state))
                    ->visibleOn('create'),

                TextInput::make('email')
                    ->afterStateUpdated(fn(Livewire $livewire) => $livewire->validateOnly('data.email'))
                    ->label(__('console.email'))
                    ->email()
                    ->extraAttributes(['dir' => 'ltr'])
                    ->live(onBlur: true)
                    ->maxLength(255)
                    ->required()
                    ->rules([
                        'bail',
                        'email:rfc,strict,spoof,filter,filter_unicode',
                        new EmailValidation(),
                    ])
                    ->visibleOn('create'),

                Toggle::make('status')
                    ->afterStateUpdated(fn(Livewire $livewire) => $livewire->validateOnly('data.status'))
                    ->label(__('console.status'))
                    ->columnSpanFull()
                    ->default(true)
                    ->live()
                    ->required(),
            ])
            ->columns(2);
    }
}
