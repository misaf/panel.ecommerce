<?php

declare(strict_types=1);

namespace Misaf\VendraContainer\ValueObjects;

use Illuminate\Support\Arr;

/**
 * What a runtime reports about one network.
 */
final class NetworkInfo
{
    public function __construct(
        public readonly string $name,
        public readonly ?string $id = null,
        public readonly ?string $driver = null,
    ) {}

    /**
     * @param array<array-key, mixed> $payload
     */
    public static function fromEnginePayload(string $name, array $payload): self
    {
        return new self(
            name: self::string($payload, 'Name') ?? $name,
            id: self::string($payload, 'Id'),
            driver: self::string($payload, 'Driver'),
        );
    }

    /**
     * @param array<array-key, mixed> $payload
     */
    private static function string(array $payload, string $key): ?string
    {
        $value = Arr::get($payload, $key);

        return is_string($value) && '' !== $value ? $value : null;
    }
}
