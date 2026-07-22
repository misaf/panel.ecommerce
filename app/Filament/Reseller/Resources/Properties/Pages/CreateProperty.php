<?php

declare(strict_types=1);

namespace App\Filament\Reseller\Resources\Properties\Pages;

use App\Actions\ProvisionTenantAction;
use App\Exceptions\SubscriptionLimitException;
use App\Filament\Reseller\Resources\Properties\PropertyResource;
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
        $resellerId = PropertyResource::currentResellerId();

        if (null === $resellerId) {
            throw new InvalidArgumentException('No billing reseller for the current user.');
        }

        $domain = $data['domain'] ?? null;
        $email = $data['email'] ?? null;

        if ( ! is_string($domain) || ! is_string($email)) {
            throw new InvalidArgumentException('Invalid property details provided.');
        }

        $reseller = Reseller::query()->findOrFail($resellerId);

        try {
            $result = app(ProvisionTenantAction::class)->execute(
                data: [
                    'domain' => $domain,
                    'email'  => $email,
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
            ->body(__('console.owner_credentials', [
                'username' => $result['user']->username,
                'password' => $result['password'],
            ]))
            ->persistent()
            ->send();

        return $result['tenant'];
    }
}
