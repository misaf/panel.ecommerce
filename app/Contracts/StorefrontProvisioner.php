<?php

declare(strict_types=1);

namespace App\Contracts;

interface StorefrontProvisioner
{
    /**
     * @param array<string, mixed> $payload
     * @return array{status: mixed, reference: mixed, image_digest: mixed}
     */
    public function provision(array $payload): array;
}
