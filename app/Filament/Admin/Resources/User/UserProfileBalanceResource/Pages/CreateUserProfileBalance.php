<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\User\UserProfileBalanceResource\Pages;

use App\Filament\Admin\Resources\User\UserProfileBalanceResource;
use Filament\Facades\Filament;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

final class CreateUserProfileBalance extends CreateRecord
{
    protected static string $resource = UserProfileBalanceResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        // $record = new ($this->getModel())($data);

        $record = $this->getModel()::updateOrCreate(
            ['user_profile_id' => $data['user_profile_id'], 'currency_id' => $data['currency_id']],
            collect($data)->except('user_profile_id', 'currency_id')->toArray()
        );

        if (
            static::getResource()::isScopedToTenant() &&
            ($tenant = Filament::getTenant())
        ) {
            return $this->associateRecordWithTenant($record, $tenant);
        }

        $record->save();

        return $record;
    }
}
