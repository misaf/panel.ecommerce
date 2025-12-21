<?php

declare(strict_types=1);

namespace App\Filament\Admin\Clusters\Users\Resources\UserProfileBalances\Pages;

use App\Filament\Admin\Clusters\Users\Resources\UserProfileBalances\UserProfileBalanceResource;
use Filament\Facades\Filament;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

final class CreateUserProfileBalance extends CreateRecord
{
    protected static string $resource = UserProfileBalanceResource::class;

    public function getBreadcrumb(): string
    {
        return self::$breadcrumb ?? __('filament-panels::resources/pages/create-record.breadcrumb') . ' ' . __('navigation.user_profile_balance');
    }

    protected function handleRecordCreation(array $data): Model
    {
        // $record = new ($this->getModel())($data);

        $record = $this->getModel()::updateOrCreate(
            ['user_profile_id' => $data['user_profile_id'], 'currency_id' => $data['currency_id']],
            collect($data)->except('user_profile_id', 'currency_id')->toArray(),
        );

        if (
            self::getResource()::isScopedToTenant()
            && ($tenant = Filament::getTenant())
        ) {
            return $this->associateRecordWithTenant($record, $tenant);
        }

        $record->save();

        return $record;
    }
}
