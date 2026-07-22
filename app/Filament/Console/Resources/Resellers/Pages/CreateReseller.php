<?php

declare(strict_types=1);

namespace App\Filament\Console\Resources\Resellers\Pages;

use App\Actions\CreateResellerAction;
use App\Filament\Console\Resources\Resellers\ResellerResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;
use Misaf\VendraSubscription\Models\Plan;

final class CreateReseller extends CreateRecord
{
    protected static string $resource = ResellerResource::class;

    /**
     * @param  array<string, mixed>  $data
     */
    protected function handleRecordCreation(array $data): Model
    {
        $name = $data['name'] ?? null;
        $planId = $data['plan_id'] ?? null;
        $ownerName = $data['owner_name'] ?? null;
        $ownerEmail = $data['owner_email'] ?? null;
        $status = $data['status'] ?? true;

        if ( ! is_string($name) || '' === $name) {
            throw new InvalidArgumentException('Invalid reseller name provided.');
        }

        if ( ! is_numeric($planId)) {
            throw new InvalidArgumentException('Invalid plan provided.');
        }

        $plan = Plan::query()->findOrFail((int) $planId);

        return app(CreateResellerAction::class)->execute(
            name: $name,
            plan: $plan,
            ownerName: is_string($ownerName) ? $ownerName : null,
            ownerEmail: is_string($ownerEmail) ? $ownerEmail : null,
            status: is_bool($status) ? $status : true,
        )['reseller'];
    }
}
