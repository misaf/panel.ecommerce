<?php

declare(strict_types=1);

namespace App\Actions;

use App\Enums\StorefrontDeploymentStatus;
use App\Jobs\ProvisionStorefrontJob;
use App\Models\StorefrontDeployment;
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
                'en' => (string) $form['storefront_name_en'],
                'fa' => (string) $form['storefront_name_fa'],
            ],
            'businessType'  => (string) $form['storefront_business_type'],
            'priceCurrency' => mb_strtoupper((string) $form['storefront_price_currency']),
            'ogImage'       => (string) ($form['storefront_og_image'] ?? ''),
            'address'       => [
                'locality' => (string) $form['storefront_locality'],
                'country'  => mb_strtoupper((string) $form['storefront_country']),
            ],
            'contact' => [
                'mobilePhone' => (string) $form['storefront_mobile_phone'],
                'officePhone' => (string) $form['storefront_office_phone'],
                'email'       => (string) $form['storefront_contact_email'],
                'hoursOpen'   => (string) $form['storefront_hours_open'],
                'hoursClose'  => (string) $form['storefront_hours_close'],
                'mapQuery'    => (string) $form['storefront_map_query'],
            ],
            'social' => [
                'whatsappPhone'    => (string) $form['storefront_whatsapp_phone'],
                'telegramUsername' => (string) $form['storefront_telegram_username'],
                'instagramUsername'=> (string) $form['storefront_instagram_username'],
            ],
        ];

        $deployment = StorefrontDeployment::query()->create([
            'tenant_id'     => $tenant->getKey(),
            'slug'          => (string) $form['storefront_slug'],
            'domain'        => $domain,
            'theme'         => (string) $form['storefront_theme'],
            'configuration' => $configuration,
            'status'        => StorefrontDeploymentStatus::Pending,
        ]);

        if (
            '' !== (string) Config::get('services.storefront.provisioner_url')
            && '' !== (string) Config::get('services.storefront.provisioner_token')
        ) {
            ProvisionStorefrontJob::dispatch($deployment->getKey())->afterCommit();
        }

        return $deployment;
    }
}
