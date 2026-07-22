<?php

declare(strict_types=1);

namespace App\Filament\Console\Resources\Properties\Pages;

use App\Actions\ProvisionTenantAction;
use App\Exceptions\SubscriptionLimitException;
use App\Filament\Console\Resources\Properties\PropertyResource;
use App\Models\Reseller;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Exceptions\Halt;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

final class CreateProperty extends CreateRecord
{
    protected static string $resource = PropertyResource::class;

    /**
     * @param  array<string, mixed>  $data
     */
    protected function handleRecordCreation(array $data): Model
    {
        $resellerId = $data['reseller_id'] ?? null;
        $name = $data['name'] ?? null;
        $domain = $data['domain'] ?? null;
        $username = $data['owner_username'] ?? null;
        $email = $data['owner_email'] ?? null;

        if ( ! is_numeric($resellerId)) {
            throw new InvalidArgumentException('Invalid reseller provided.');
        }

        if ( ! is_string($name) || ! is_string($domain) || ! is_string($username) || ! is_string($email)) {
            throw new InvalidArgumentException('Invalid property details provided.');
        }

        $reseller = Reseller::query()->findOrFail((int) $resellerId);

        try {
            $result = app(ProvisionTenantAction::class)->execute(
                data: [
                    'name'     => $name,
                    'domain'   => $domain,
                    'username' => $username,
                    'email'    => $email,
                ],
                reseller: $reseller,
            );
        } catch (SubscriptionLimitException $exception) {
            Notification::make()
                ->danger()
                ->title(__('console.property_limit_reached'))
                ->body($exception->getMessage())
                ->send();

            throw new Halt();
        }

        Notification::make()
            ->success()
            ->title(__('console.property_created'))
            ->body(__('console.owner_password', ['password' => $result['password']]))
            ->persistent()
            ->send();

        return $result['tenant'];
    }
}
