<?php

declare(strict_types=1);

namespace Misaf\VendraStore\Actions;

use Illuminate\Support\Facades\DB;
use LogicException;
use Misaf\VendraStore\Jobs\CompleteStoreProvisioningJob;
use Misaf\VendraStore\Models\Store;
use Misaf\VendraTenant\Enums\TenantProvisioningStatus;

final class RetryStoreProvisioningAction
{
    public function execute(Store $store): Store
    {
        return DB::transaction(function () use ($store): Store {
            $lockedStore = Store::query()->whereKey($store->getKey())->lockForUpdate()->firstOrFail();

            if (TenantProvisioningStatus::Ready === $lockedStore->provisioning_status) {
                throw new LogicException("Store [{$lockedStore->id}] is already provisioned.");
            }

            if (TenantProvisioningStatus::Failed === $lockedStore->provisioning_status) {
                $lockedStore->forceFill([
                    'active'                 => false,
                    'provisioning_status'    => TenantProvisioningStatus::Pending,
                    'provisioning_failed_at' => null,
                    'provisioning_error'     => null,
                ])->save();
            }

            CompleteStoreProvisioningJob::dispatch($lockedStore->id)->afterCommit();

            return $lockedStore;
        });
    }
}
