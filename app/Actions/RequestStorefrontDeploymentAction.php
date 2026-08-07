<?php

declare(strict_types=1);

namespace App\Actions;

use App\Enums\StorefrontDeploymentStatus;
use App\Jobs\ProvisionStorefrontJob;
use App\Models\StorefrontDeployment;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Config;
use Misaf\VendraTenant\Models\Tenant;

final class RequestStorefrontDeploymentAction
{
    /**
     * @param array<string, mixed> $form
     */
    public function execute(Tenant $tenant, string $domain, array $form): StorefrontDeployment
    {
        $configuration = [
            'name' => [
                'en' => Arr::string($form, 'storefront_name_en'),
                'fa' => Arr::string($form, 'storefront_name_fa'),
            ],
            'businessType'  => Arr::string($form, 'storefront_business_type'),
            'priceCurrency' => mb_strtoupper(Arr::string($form, 'storefront_price_currency')),
            'ogImage'       => null === ($form['storefront_og_image'] ?? null)
                ? ''
                : Arr::string($form, 'storefront_og_image'),
            'address'       => [
                'locality' => Arr::string($form, 'storefront_locality'),
                'country'  => mb_strtoupper(Arr::string($form, 'storefront_country')),
            ],
            'contact' => [
                'mobilePhone' => Arr::string($form, 'storefront_mobile_phone'),
                'officePhone' => Arr::string($form, 'storefront_office_phone'),
                'email'       => Arr::string($form, 'storefront_contact_email'),
                'hoursOpen'   => Arr::string($form, 'storefront_hours_open'),
                'hoursClose'  => Arr::string($form, 'storefront_hours_close'),
                'mapQuery'    => Arr::string($form, 'storefront_map_query'),
            ],
            'social' => [
                'whatsappPhone'     => Arr::string($form, 'storefront_whatsapp_phone'),
                'telegramUsername'  => Arr::string($form, 'storefront_telegram_username'),
                'instagramUsername' => Arr::string($form, 'storefront_instagram_username'),
            ],
        ];

        // Per-locale copy overrides. One image serves the whole fleet and its
        // message catalogue is deliberately brand-neutral, so this is the only
        // channel a property has for wording of its own — without it every
        // storefront reads identically. Omitted when empty: the storefront
        // treats an absent key and an empty object the same way.
        $messages = $this->messages($form);

        if ([] !== $messages) {
            $configuration['messages'] = $messages;
        }

        $deployment = StorefrontDeployment::query()->create([
            'tenant_id'     => $tenant->id,
            'slug'          => Arr::string($form, 'storefront_slug'),
            'domain'        => $domain,
            'theme'         => Arr::string($form, 'storefront_theme'),
            'configuration' => $configuration,
            'status'        => StorefrontDeploymentStatus::Pending,
        ]);

        if (
            '' !== Config::string('services.storefront.provisioner_url')
            && '' !== Config::string('services.storefront.provisioner_token')
        ) {
            ProvisionStorefrontJob::dispatch($deployment->id)->afterCommit();
        }

        return $deployment;
    }

    /**
     * Locale-keyed message overrides, deep-merged over the storefront's base
     * catalogue at render time.
     *
     * Shape: ['en' => ['products' => ['title' => 'Our Breads']], 'fa' => [...]].
     * Anything that is not an array keyed by a locale string is dropped rather
     * than passed on, because the storefront validates the encoded
     * configuration at boot and refuses to render when it does not parse.
     *
     * @param  array<string, mixed>                  $form
     * @return array<string, array<string, mixed>>
     */
    private function messages(array $form): array
    {
        $value = $form['storefront_messages'] ?? null;

        if ( ! is_array($value)) {
            return [];
        }

        $messages = [];

        foreach ($value as $locale => $overrides) {
            if (is_string($locale) && '' !== $locale && is_array($overrides) && [] !== $overrides) {
                $messages[$locale] = $overrides;
            }
        }

        return $messages;
    }
}
