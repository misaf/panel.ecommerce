<?php

declare(strict_types=1);

namespace App\Filament\Console\Resources\Resellers\Pages;

use App\Actions\SubscribeResellerAction;
use App\Exceptions\SubscriptionLimitException;
use App\Filament\Console\Resources\Resellers\ResellerResource;
use App\Models\Reseller;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Misaf\VendraSubscription\Models\Plan;

final class EditReseller extends EditRecord
{
    protected static string $resource = ResellerResource::class;

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
            ->label(__('console.change_plan'))
            ->icon('heroicon-o-arrows-right-left')
            ->schema([
                Select::make('plan_id')
                    ->label(__('console.plan'))
                    ->options(fn(): array => Plan::query()->enabled()->pluck('name', 'id')->all())
                    ->required()
                    ->native(false),
            ])
            ->action(function (array $data): void {
                $record = $this->getRecord();
                $planId = $data['plan_id'] ?? null;

                if ( ! $record instanceof Reseller || ! is_numeric($planId)) {
                    return;
                }

                $plan = Plan::query()->findOrFail((int) $planId);

                try {
                    app(SubscribeResellerAction::class)->execute($record, $plan);
                } catch (SubscriptionLimitException $exception) {
                    Notification::make()
                        ->danger()
                        ->title(__('console.downgrade_blocked'))
                        ->body($exception->getMessage())
                        ->send();

                    return;
                }

                Notification::make()
                    ->success()
                    ->title(__('console.plan_changed'))
                    ->send();
            });
    }

    private function renewAction(): Action
    {
        return Action::make('renew')
            ->label(__('console.renew'))
            ->icon('heroicon-o-arrow-path')
            ->requiresConfirmation()
            ->action(function (): void {
                $record = $this->getRecord();

                if ( ! $record instanceof Reseller) {
                    return;
                }

                $subscription = $record->activeSubscription()
                    ?? $record->subscriptions()->latest('starts_at')->first();
                $plan = $subscription?->plan;

                if (null === $plan) {
                    Notification::make()
                        ->danger()
                        ->title(__('console.no_active_subscription'))
                        ->send();

                    return;
                }

                app(SubscribeResellerAction::class)->execute($record, $plan);

                Notification::make()
                    ->success()
                    ->title(__('console.subscription_renewed'))
                    ->send();
            });
    }
}
