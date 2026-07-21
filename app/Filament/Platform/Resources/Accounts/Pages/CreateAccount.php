<?php

declare(strict_types=1);

namespace App\Filament\Platform\Resources\Accounts\Pages;

use App\Filament\Platform\Resources\Accounts\AccountResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;
use Misaf\VendraSubscription\Actions\CreateAccountAction;
use Misaf\VendraSubscription\Models\Plan;

final class CreateAccount extends CreateRecord
{
    protected static string $resource = AccountResource::class;

    /**
     * @param  array<string, mixed>  $data
     */
    protected function handleRecordCreation(array $data): Model
    {
        $name = $data['name'] ?? null;
        $planId = $data['plan_id'] ?? null;
        $ownerName = $data['owner_name'] ?? null;
        $ownerEmail = $data['owner_email'] ?? null;

        if ( ! is_string($name) || '' === $name) {
            throw new InvalidArgumentException('Invalid account name provided.');
        }

        if ( ! is_numeric($planId)) {
            throw new InvalidArgumentException('Invalid plan provided.');
        }

        $plan = Plan::query()->findOrFail((int) $planId);

        return app(CreateAccountAction::class)->execute(
            name: $name,
            plan: $plan,
            ownerName: is_string($ownerName) ? $ownerName : null,
            ownerEmail: is_string($ownerEmail) ? $ownerEmail : null,
        )['account'];
    }
}
