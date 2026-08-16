<?php

declare(strict_types=1);

namespace Misaf\VendraContainer\ValueObjects;

use Illuminate\Support\Str;
use InvalidArgumentException;
use Stringable;

/**
 * A registry reference, parsed once instead of at every call site.
 *
 * Splitting "repo:tag", "repo@sha256:…" and "registry:5000/repo" correctly is
 * the kind of detail that gets re-implemented slightly differently in each
 * adapter — the final-slash check below is what keeps a registry port from being
 * read as a tag. Parsing it here means a runtime receives a decided value.
 */
final class ImageReference implements Stringable
{
    public readonly string $repository;

    /**
     * The tag, or an empty string for a digest-pinned reference.
     */
    public readonly string $tag;

    /**
     * The digest including its algorithm ("sha256:…"), when pinned.
     */
    public readonly ?string $digest;

    public function __construct(public readonly string $value)
    {
        if ('' === mb_trim($value)) {
            throw new InvalidArgumentException('An image reference is required.');
        }

        if (Str::contains($value, '@')) {
            $this->repository = Str::before($value, '@');
            $this->tag = '';
            $this->digest = Str::after($value, '@');

            return;
        }

        $colon = mb_strrpos($value, ':');

        if (false === $colon || mb_strrpos($value, '/') > $colon) {
            $this->repository = $value;
            $this->tag = 'latest';
            $this->digest = null;

            return;
        }

        $this->repository = mb_substr($value, 0, $colon);
        $this->tag = mb_substr($value, $colon + 1);
        $this->digest = null;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    /**
     * Whether the reference names one immutable image rather than a moving tag.
     */
    public function isPinned(): bool
    {
        return null !== $this->digest;
    }
}
