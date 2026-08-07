<?php

declare(strict_types=1);

namespace App\Filament\Console\Resources\Properties\Pages;

use App\Actions\ProvisionTenantAction;
use App\Actions\RequestStorefrontDeploymentAction;
use App\Filament\Console\Resources\Properties\PropertyResource;
use App\Filament\Console\Resources\Properties\Schemas\PropertyForm;
use App\Filament\Properties\Schemas\StorefrontConfigurationFields;
use App\Models\Reseller;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Support\Exceptions\Halt;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;
use Misaf\VendraSubscription\Exceptions\SubscriptionLimitException;

final class CreateProperty extends CreateRecord
{
    use CreateRecord\Concerns\HasWizard;

    protected static string $resource = PropertyResource::class;

    public function hasSkippableSteps(): bool
    {
        return true;
    }

    public function getTitle(): string
    {
        return __('console.create_florist_storefront');
    }

    public function getSubheading(): ?string
    {
        return __('console.create_florist_storefront_description');
    }

    protected function getCreateFormAction(): Action
    {
        return parent::getCreateFormAction()
            ->label(__('console.create_storefront_action'));
    }

    /**
     * @return list<Step>
     */
    protected function getSteps(): array
    {
        return [
            Step::make(__('console.property_details'))
                ->description(__('console.property_details_description'))
                ->icon(Heroicon::BuildingStorefront)
                ->completedIcon(Heroicon::CheckCircle)
                ->schema([
                    ...PropertyForm::propertyFields(),
                    PropertyForm::activeField(),
                ])
                ->columns(['default' => 1, 'md' => 2]),
            Step::make(__('console.storefront_identity'))
                ->description(__('console.storefront_identity_description'))
                ->icon(Heroicon::Sparkles)
                ->completedIcon(Heroicon::CheckCircle)
                ->schema(StorefrontConfigurationFields::identityFields(optional: false))
                ->columns(['default' => 1, 'md' => 2]),
            Step::make(__('console.storefront_contact'))
                ->description(__('console.storefront_contact_description'))
                ->icon(Heroicon::Phone)
                ->completedIcon(Heroicon::CheckCircle)
                ->schema(StorefrontConfigurationFields::contactFields(optional: false))
                ->columns(['default' => 1, 'md' => 2]),
            Step::make(__('console.storefront_location_social'))
                ->description(__('console.storefront_location_social_description'))
                ->icon(Heroicon::MapPin)
                ->completedIcon(Heroicon::CheckCircle)
                ->schema(StorefrontConfigurationFields::locationAndSocialFields(optional: false))
                ->columns(['default' => 1, 'md' => 2]),
        ];
    }

    /**
     * @param  array<string, mixed>  $data
     */
    protected function handleRecordCreation(array $data): Model
    {
        $resellerId = $data['reseller_id'] ?? null;
        $domain = $data['domain'] ?? null;
        $email = $data['email'] ?? null;

        if ( ! is_numeric($resellerId)) {
            throw new InvalidArgumentException('Invalid reseller provided.');
        }

        if ( ! is_string($domain) || ! is_string($email)) {
            throw new InvalidArgumentException('Invalid property details provided.');
        }

        $reseller = Reseller::query()->findOrFail((int) $resellerId);

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

        app(RequestStorefrontDeploymentAction::class)->execute(
            tenant: $result['tenant'],
            domain: $domain,
            form: $data,
        );

        return $result['tenant'];
    }
}
