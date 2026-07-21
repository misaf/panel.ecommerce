<?php

declare(strict_types=1);

namespace App\Filament\Platform\Resources\Websites\Pages;

use App\Filament\Platform\Resources\Websites\WebsiteResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Exceptions\Halt;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;
use Misaf\VendraSubscription\Actions\ProvisionTenantAction;
use Misaf\VendraSubscription\Exceptions\SubscriptionLimitException;
use Misaf\VendraSubscription\Models\Account;

final class CreateWebsite extends CreateRecord
{
    protected static string $resource = WebsiteResource::class;

    /**
     * @param  array<string, mixed>  $data
     */
    protected function handleRecordCreation(array $data): Model
    {
        $accountId = $data['account_id'] ?? null;
        $name = $data['name'] ?? null;
        $domain = $data['domain'] ?? null;
        $username = $data['owner_username'] ?? null;
        $email = $data['owner_email'] ?? null;

        if ( ! is_numeric($accountId)) {
            throw new InvalidArgumentException('Invalid account provided.');
        }

        if ( ! is_string($name) || ! is_string($domain) || ! is_string($username) || ! is_string($email)) {
            throw new InvalidArgumentException('Invalid website details provided.');
        }

        $account = Account::query()->findOrFail((int) $accountId);

        try {
            $result = app(ProvisionTenantAction::class)->execute(
                data: [
                    'name'     => $name,
                    'domain'   => $domain,
                    'username' => $username,
                    'email'    => $email,
                ],
                account: $account,
            );
        } catch (SubscriptionLimitException $exception) {
            Notification::make()
                ->danger()
                ->title(__('platform.website_limit_reached'))
                ->body($exception->getMessage())
                ->send();

            throw new Halt();
        }

        Notification::make()
            ->success()
            ->title(__('platform.website_created'))
            ->body(__('platform.owner_password', ['password' => $result['password']]))
            ->persistent()
            ->send();

        return $result['tenant'];
    }
}
