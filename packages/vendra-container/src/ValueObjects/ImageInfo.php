<?php

declare(strict_types=1);

namespace Misaf\VendraContainer\ValueObjects;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

/**
 * What the runtime knows about an image that is present locally.
 */
final class ImageInfo
{
    /**
     * @param list<string> $tags
     */
    public function __construct(
        public readonly ImageReference $reference,
        public readonly ?string $id = null,
        public readonly ?string $digest = null,
        public readonly array $tags = [],
    ) {}

    /**
     * Build from an Engine image-inspect body.
     *
     * The digest is read from RepoDigests rather than Id: Id is the local image
     * config hash, which differs from the digest the registry serves, so
     * recording it would name something no other host can pull.
     *
     * @param array<array-key, mixed> $payload
     */
    public static function fromEnginePayload(ImageReference $reference, array $payload): self
    {
        return new self(
            reference: $reference,
            id: self::string($payload, 'Id'),
            digest: $reference->digest ?? self::repoDigest($payload),
            tags: self::tags($payload),
        );
    }

    /**
     * @param  array<array-key, mixed> $payload
     * @return list<string>
     */
    private static function tags(array $payload): array
    {
        $tags = [];

        foreach ((array) Arr::get($payload, 'RepoTags', []) as $tag) {
            if (is_string($tag) && '' !== $tag) {
                $tags[] = $tag;
            }
        }

        return $tags;
    }

    /**
     * @param array<array-key, mixed> $payload
     */
    private static function repoDigest(array $payload): ?string
    {
        foreach ((array) Arr::get($payload, 'RepoDigests', []) as $digest) {
            if (is_string($digest) && Str::contains($digest, '@sha256:')) {
                return 'sha256:' . Str::after($digest, '@sha256:');
            }
        }

        return null;
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
