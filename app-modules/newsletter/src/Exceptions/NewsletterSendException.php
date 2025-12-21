<?php

declare(strict_types=1);

namespace Misaf\Newsletter\Exceptions;

use Exception;

final class NewsletterSendException extends Exception
{
    public static function newsletterDisabled(string $newsletterSlug): self
    {
        return new self("Newsletter is disabled. Newsletter Slug: {$newsletterSlug}");
    }

    public static function noReadyPosts(): self
    {
        return new self('No ready newsletter posts found.');
    }

    public static function noSubscribers(): self
    {
        return new self('No subscribed users found.');
    }

    public static function postNotReady(int $postId): self
    {
        return new self("Newsletter post is not ready. Post ID: {$postId}");
    }

    public static function sendHistoryAlreadySending(int $sendHistoryId): self
    {
        return new self("Newsletter send history is already sending. Send History ID: {$sendHistoryId}");
    }

    public static function subscriberAlreadySending(int $subscriberId): self
    {
        return new self("Newsletter send history subscriber is already sending. Subscriber ID: {$subscriberId}");
    }

    public static function validationFailed(string $message): self
    {
        return new self("Validation failed: {$message}");
    }

    public static function jobDispatchFailed(string $entity, int $id, string $reason): self
    {
        return new self("Failed to dispatch {$entity} job. ID: {$id}. Reason: {$reason}");
    }
}
