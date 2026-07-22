<?php

declare(strict_types=1);

namespace App\Filament\Console\Resources\Resellers\Schemas;

use App\Models\Reseller;
use App\Models\ResellerUser;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rules\Unique;
use Misaf\VendraSubscription\Models\Plan;

final class ResellerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('username')
                    ->label(__('console.username'))
                    ->minLength(3)
                    ->maxLength(12)
                    ->rules(['alpha_dash'])
                    ->required()
                    ->unique(
                        table: ResellerUser::class,
                        column: 'username',
                        modifyRuleUsing: fn(Unique $rule): Unique => $rule
                            ->withoutTrashed(),
                    )
                    ->visibleOn('create'),

                TextInput::make('email')
                    ->label(__('console.email'))
                    ->email()
                    ->maxLength(255)
                    ->required(fn(string $operation): bool => 'create' === $operation)
                    ->rules(fn(string $operation): array => 'create' === $operation
                        ? [Rule::unique(ResellerUser::class, 'email')->withoutTrashed()]
                        : []),

                TextInput::make('password')
                    ->label(__('console.new_password'))
                    ->password()
                    ->revealable(filament()->arePasswordsRevealable())
                    ->required()
                    ->confirmed()
                    ->rule(Password::default())
                    ->visibleOn('create'),

                TextInput::make('password_confirmation')
                    ->label(__('console.confirm_password'))
                    ->password()
                    ->revealable(filament()->arePasswordsRevealable())
                    ->required()
                    ->dehydrated(false)
                    ->visibleOn('create'),

                Select::make('plan_id')
                    ->label(__('console.subscription_plan'))
                    ->options(fn(): array => Plan::query()->enabled()->pluck('name', 'id')->all())
                    ->required()
                    ->native(false)
                    ->visibleOn('create'),

                Toggle::make('status')
                    ->label(__('console.status'))
                    ->columnSpanFull()
                    ->default(true)
                    ->required(),

                Section::make(__('console.current_subscription'))
                    ->visibleOn('edit')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('current_plan')
                            ->label(__('console.plan'))
                            ->state(fn(?Reseller $record): string => $record?->activeSubscription()?->plan->name ?? '—'),

                        TextEntry::make('current_status')
                            ->label(__('console.status'))
                            ->state(fn(?Reseller $record): string => $record?->activeSubscription()?->status->value ?? '—'),

                        TextEntry::make('current_ends_at')
                            ->label(__('console.ends_at'))
                            ->state(fn(?Reseller $record): string => $record?->activeSubscription()?->ends_at?->toDayDateTimeString() ?? '—'),
                    ]),
            ])
            ->columns(2);
    }
}
