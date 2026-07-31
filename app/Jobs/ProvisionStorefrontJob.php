<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Contracts\StorefrontProvisioner;
use App\Enums\StorefrontDeploymentStatus;
use App\Models\StorefrontDeployment;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use JsonException;
use Spatie\Multitenancy\Jobs\NotTenantAware;
use Throwable;

final class ProvisionStorefrontJob implements NotTenantAware, ShouldBeUnique, ShouldQueue
{
    use Queueable;

    public int $tries = 5;

    public int $timeout = 180;

    public int $uniqueFor = 3600;

    public function __construct(
        public readonly int $deploymentId,
        public readonly bool $force = false,
    ) {}

    public function handle(StorefrontProvisioner $provisioner): void
    {
        $deployment = StorefrontDeployment::query()->findOrFail($this->deploymentId);

        if ( ! $this->force && StorefrontDeploymentStatus::Ready === $deployment->status) {
            return;
        }

        $deployment->forceFill([
            'status'    => StorefrontDeploymentStatus::Processing,
            'failed_at' => null,
            'error'     => null,
        ])->save();

        try {
            $configuration = [
                'slug'    => $deployment->slug,
                'theme'   => $deployment->theme,
                'domain'  => $deployment->domain,
                'siteUrl' => 'https://' . $deployment->domain,
                ...$deployment->configuration,
            ];

            $response = $provisioner->provision([
                'template'             => 'vendra-storefront-florist',
                'image'                => Config::string('services.storefront.image'),
                'tenant_id'            => $deployment->tenant_id,
                'slug'                 => $deployment->slug,
                'domain'               => $deployment->domain,
                'theme'                => $deployment->theme,
                'configuration'        => $configuration,
                'configuration_base64' => base64_encode($this->encodeConfiguration($configuration)),
            ]);

            $status = $response['status'];
            $reference = $response['reference'];
            $imageDigest = $response['image_digest'];
            $ready = 'ready' === $status;
            $deployment->forceFill([
                'status'             => $ready ? StorefrontDeploymentStatus::Ready : StorefrontDeploymentStatus::Requested,
                'provider_reference' => is_string($reference) && '' !== $reference ? $reference : null,
                'image_digest'       => is_string($imageDigest) && '' !== $imageDigest ? $imageDigest : null,
                'requested_at'       => now(),
                'deployed_at'        => $ready ? now() : null,
            ])->save();
        } catch (Throwable $exception) {
            $this->markFailed($exception);

            throw $exception;
        }
    }

    /** @return list<int> */
    public function backoff(): array
    {
        return [5, 30, 120, 300];
    }

    public function uniqueId(): string
    {
        return (string) $this->deploymentId;
    }

    public function failed(?Throwable $exception): void
    {
        $this->markFailed($exception);
    }

    private function markFailed(?Throwable $exception): void
    {
        StorefrontDeployment::query()->whereKey($this->deploymentId)->update([
            'status'    => StorefrontDeploymentStatus::Failed->value,
            'failed_at' => now(),
            'error'     => null === $exception
                ? 'Storefront provisioning failed.'
                : Str::limit($exception->getMessage(), 2000, ''),
        ]);
    }

    /**
     * @param array<string, mixed> $configuration
     *
     * @throws JsonException
     */
    private function encodeConfiguration(array $configuration): string
    {
        return json_encode($configuration, JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }
}
