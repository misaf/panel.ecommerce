<?php

declare(strict_types=1);

namespace App\Filament\Platform\Resources\Accounts\Pages;

use App\Filament\Platform\Resources\Accounts\AccountResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Misaf\VendraSubscription\Actions\SubscribeAccountAction;
use Misaf\VendraSubscription\Exceptions\SubscriptionLimitException;
use Misaf\VendraSubscription\Models\Account;
use Misaf\VendraSubscription\Models\Plan;

final class EditAccount extends EditRecord
{
    protected static string $resource = AccountResource::class;

    protected function getHeaderActions(): array
    {
        return [
            $this->changePlanAction(),
            $this->renewAction(),
            DeleteAction::make(),
        ];
    }

    private function changePlanAction(): Action
    {
        return Action::make('changePlan')
            ->label(__('platform.change_plan'))
            ->icon('heroicon-o-arrows-right-left')
            ->schema([
                Select::make('plan_id')
                    ->label(__('platform.plan'))
                    ->options(fn(): array => Plan::query()->enabled()->pluck('name', 'id')->all())
                    ->required()
                    ->native(false),
            ])
            ->action(function (array $data): void {
                $record = $this->getRecord();
                $planId = $data['plan_id'] ?? null;

                if ( ! $record instanceof Account || ! is_numeric($planId)) {
                    return;
                }

                $plan = Plan::query()->findOrFail((int) $planId);

                try {
                    app(SubscribeAccountAction::class)->execute($record, $plan);
                } catch (SubscriptionLimitException $exception) {
                    Notification::make()
                        ->danger()
                        ->title(__('platform.downgrade_blocked'))
                        ->body($exception->getMessage())
                        ->send();

                    return;
                }

                Notification::make()
                    ->success()
                    ->title(__('platform.plan_changed'))
                    ->send();
            });
    }

    private function renewAction(): Action
    {
        return Action::make('renew')
            ->label(__('platform.renew'))
            ->icon('heroicon-o-arrow-path')
            ->requiresConfirmation()
            ->action(function (): void {
                $record = $this->getRecord();

                if ( ! $record instanceof Account) {
                    return;
                }

                $subscription = $record->activeSubscription()
                    ?? $record->subscriptions()->latest('starts_at')->first();
                $plan = $subscription?->plan;

                if (null === $plan) {
                    Notification::make()
                        ->danger()
                        ->title(__('platform.no_active_subscription'))
                        ->send();

                    return;
                }

                app(SubscribeAccountAction::class)->execute($record, $plan);

                Notification::make()
                    ->success()
                    ->title(__('platform.subscription_renewed'))
                    ->send();
            });
    }
}
