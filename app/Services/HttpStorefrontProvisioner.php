<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\StorefrontProvisioner;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use UnexpectedValueException;

final class HttpStorefrontProvisioner implements StorefrontProvisioner
{
    public function provision(array $payload): array
    {
        $response = Http::asJson()
            ->acceptJson()
            ->withToken(Config::string('services.storefront.provisioner_token'))
            ->connectTimeout(5)
            ->timeout(150)
            ->retry([500, 1000])
            ->post(Config::string('services.storefront.provisioner_url'), $payload)
            ->throw();

        $result = $response->json();

        if ( ! is_array($result)) {
            throw new UnexpectedValueException('The storefront provisioner returned an invalid response.');
        }

        return [
            'status'       => $result['status'] ?? null,
            'reference'    => $result['reference'] ?? null,
            'image_digest' => $result['image_digest'] ?? null,
        ];
    }
}
