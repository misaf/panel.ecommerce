<?php

declare(strict_types=1);

namespace Misaf\VendraConsole\Filament\Resources\Stores\Actions;

use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;
use Misaf\VendraStore\Actions\OffboardStoreAction;
use Misaf\VendraStore\Actions\ReactivateStoreAction;
use Misaf\VendraStore\Actions\RedeployStoreStorefrontAction;
use Misaf\VendraStore\Actions\RestartStoreStorefrontAction;
use Misaf\VendraStore\Actions\RestoreOffboardedStoreAction;
use Misaf\VendraStore\Actions\RetryFailedStorefrontDeploymentAction;
use Misaf\VendraStore\Actions\RetryStoreProvisioningAction;
use Misaf\VendraStore\Actions\StartStoreStorefrontAction;
use Misaf\VendraStore\Actions\StopStoreStorefrontAction;
use Misaf\VendraStore\Actions\SuspendStoreAction;
use Misaf\VendraStore\Enums\StorefrontDeploymentStatus;
use Misaf\VendraStore\Enums\StorefrontDesiredState;
use Misaf\VendraStore\Models\Store;
use Misaf\VendraStore\Models\StorefrontDeployment;
use Misaf\VendraTenant\Enums\TenantProvisioningStatus;

final class StoreOperatorActions
{
    /** @return list<Action> */
    public static function make(): array
    {
        return [
            self::suspend(),
            self::reactivate(),
            self::retryProvisioning(),
            self::startStorefront(),
            self::stopStorefront(),
            self::restartStorefront(),
            self::redeployStorefront(),
            self::retryStorefront(),
            self::offboard(),
            self::restore(),
        ];
    }

    private static function suspend(): Action
    {
        return Action::make('suspendStore')
            ->label(__('console.suspend_store'))
            ->icon(Heroicon::OutlinedPauseCircle)
            ->color('warning')
            ->requiresConfirmation()
            ->visible(fn(Store $record): bool => ! $record->trashed() && $record->active)
            ->action(function (Store $record, SuspendStoreAction $suspendStore): void {
                $suspendStore->execute($record);
                self::notify(__('console.store_suspended'));
            });
    }

    private static function reactivate(): Action
    {
        return Action::make('reactivateStore')
            ->label(__('console.reactivate_store'))
            ->icon(Heroicon::OutlinedPlayCircle)
            ->visible(fn(Store $record): bool => ! $record->trashed()
                && ! $record->active
                && TenantProvisioningStatus::Ready === $record->provisioning_status)
            ->action(function (Store $record, ReactivateStoreAction $reactivateStore): void {
                $reactivateStore->execute($record);
                self::notify(__('console.store_reactivated'));
            });
    }

    private static function retryProvisioning(): Action
    {
        return Action::make('retryStoreProvisioning')
            ->label(__('console.retry_store_provisioning'))
            ->icon(Heroicon::OutlinedArrowPath)
            ->visible(fn(Store $record): bool => ! $record->trashed()
                && TenantProvisioningStatus::Ready !== $record->provisioning_status)
            ->action(function (Store $record, RetryStoreProvisioningAction $retryStoreProvisioning): void {
                $retryStoreProvisioning->execute($record);
                self::notify(__('console.store_provisioning_queued'));
            });
    }

    private static function startStorefront(): Action
    {
        return Action::make('startStorefront')
            ->label(__('console.start_storefront'))
            ->icon(Heroicon::OutlinedPlay)
            ->visible(fn(Store $record): bool => StorefrontDesiredState::Stopped === self::deployment($record)?->desired_state)
            ->action(function (Store $record, StartStoreStorefrontAction $startStorefront): void {
                $deployment = self::deployment($record);

                if ($deployment instanceof StorefrontDeployment) {
                    $startStorefront->execute($deployment);
                    self::notify(__('console.storefront_started'));
                }
            });
    }

    private static function stopStorefront(): Action
    {
        return Action::make('stopStorefront')
            ->label(__('console.stop_storefront'))
            ->icon(Heroicon::OutlinedStop)
            ->color('warning')
            ->requiresConfirmation()
            ->visible(fn(Store $record): bool => StorefrontDesiredState::Running === self::deployment($record)?->desired_state)
            ->action(function (Store $record, StopStoreStorefrontAction $stopStorefront): void {
                $deployment = self::deployment($record);

                if ($deployment instanceof StorefrontDeployment) {
                    $stopStorefront->execute($deployment);
                    self::notify(__('console.storefront_stopped'));
                }
            });
    }

    private static function restartStorefront(): Action
    {
        return Action::make('restartStorefront')
            ->label(__('console.restart_storefront'))
            ->icon(Heroicon::OutlinedArrowPath)
            ->requiresConfirmation()
            ->visible(fn(Store $record): bool => self::deployment($record) instanceof StorefrontDeployment)
            ->action(function (Store $record, RestartStoreStorefrontAction $restartStorefront): void {
                $deployment = self::deployment($record);

                if ($deployment instanceof StorefrontDeployment) {
                    $restartStorefront->execute($deployment);
                    self::notify(__('console.storefront_restarted'));
                }
            });
    }

    private static function redeployStorefront(): Action
    {
        return Action::make('redeployStorefront')
            ->label(__('console.redeploy_storefront'))
            ->icon(Heroicon::OutlinedCloudArrowUp)
            ->requiresConfirmation()
            ->visible(fn(Store $record): bool => self::deployment($record) instanceof StorefrontDeployment)
            ->action(function (Store $record, RedeployStoreStorefrontAction $redeployStorefront): void {
                $deployment = self::deployment($record);

                if ($deployment instanceof StorefrontDeployment) {
                    $redeployStorefront->execute($deployment);
                    self::notify(__('console.storefront_redeployment_queued'));
                }
            });
    }

    private static function retryStorefront(): Action
    {
        return Action::make('retryStorefront')
            ->label(__('console.retry_storefront'))
            ->icon(Heroicon::OutlinedArrowPath)
            ->visible(fn(Store $record): bool => StorefrontDeploymentStatus::Failed === self::deployment($record)?->status)
            ->action(function (Store $record, RetryFailedStorefrontDeploymentAction $retryStorefront): void {
                $deployment = self::deployment($record);

                if ($deployment instanceof StorefrontDeployment) {
                    $retryStorefront->execute($deployment);
                    self::notify(__('console.storefront_retry_queued'));
                }
            });
    }

    private static function offboard(): Action
    {
        return Action::make('offboardStore')
            ->label(__('console.offboard_store'))
            ->icon(Heroicon::OutlinedArchiveBox)
            ->color('danger')
            ->visible(fn(Store $record): bool => ! $record->trashed())
            ->schema([
                Textarea::make('reason')
                    ->label(__('console.offboarding_reason'))
                    ->required()
                    ->maxLength(OffboardStoreAction::MAX_REASON_LENGTH),
            ])
            ->action(function (Store $record, array $data, OffboardStoreAction $offboardStore): void {
                $offboardStore->execute($record, (string) $data['reason']);
                self::notify(__('console.store_offboarded'));
            });
    }

    private static function restore(): Action
    {
        return Action::make('restoreOffboardedStore')
            ->label(__('console.restore_store'))
            ->icon(Heroicon::OutlinedArrowUturnLeft)
            ->visible(fn(Store $record): bool => $record->trashed())
            ->action(function (Store $record, RestoreOffboardedStoreAction $restoreOffboardedStore): void {
                $restoreOffboardedStore->execute($record);
                self::notify(__('console.store_restored'));
            });
    }

    private static function deployment(Store $store): ?StorefrontDeployment
    {
        $deployment = $store->storefrontDeployments->first();

        return $deployment instanceof StorefrontDeployment ? $deployment : null;
    }

    private static function notify(string $title): void
    {
        Notification::make()->success()->title($title)->send();
    }
}
