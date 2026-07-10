<?php

declare(strict_types=1);

namespace Misaf\VendraNewsletter\Traits;

use Exception;
use Misaf\VendraNewsletter\Services\NewsletterPostService;
use Misaf\VendraNewsletter\Services\NewsletterService;
use Misaf\VendraNewsletter\Services\NewsletterSubscriberService;

trait HasCacheClearing
{
    protected function clearNewsletterCache(
        string $service,
        bool $clearRelated,
        NewsletterService $newsletterService,
        NewsletterPostService $postService,
        NewsletterSubscriberService $subscriberService
    ): void {
        try {
            $this->clearServiceCache($service, $newsletterService, $postService, $subscriberService);

            if ($clearRelated) {
                $this->clearRelatedCaches($service, $newsletterService, $postService, $subscriberService);
            }
        } catch (Exception $e) {
            logger()->error('Failed to clear newsletter cache', [
                'service' => $service,
                'error'   => $e->getMessage(),
            ]);
        }
    }

    private function clearServiceCache(
        string $service,
        NewsletterService $newsletterService,
        NewsletterPostService $postService,
        NewsletterSubscriberService $subscriberService
    ): void {
        match ($service) {
            'newsletter'            => $newsletterService->clearCache(),
            'newsletter-post'       => $postService->clearCache(),
            'newsletter-subscriber' => $subscriberService->clearCache(),
            default                 => null,
        };
    }

    private function clearRelatedCaches(
        string $service,
        NewsletterService $newsletterService,
        NewsletterPostService $postService,
        NewsletterSubscriberService $subscriberService
    ): void {
        if ('newsletter' === $service) {
            $postService->clearCache();
            $subscriberService->clearCache();
        } elseif ('newsletter-post' === $service) {
            $newsletterService->clearCache();
        } elseif ('newsletter-subscriber' === $service) {
            $newsletterService->clearCache();
        }
    }

    protected function clearAllNewsletterCache(
        NewsletterService $newsletterService,
        NewsletterPostService $postService,
        NewsletterSubscriberService $subscriberService
    ): void {
        try {
            $newsletterService->clearCache();
            $postService->clearCache();
            $subscriberService->clearCache();
        } catch (Exception $e) {
            logger()->error('Failed to clear all newsletter caches', [
                'error' => $e->getMessage(),
            ]);
        }
    }
}
