<?php

declare(strict_types=1);

namespace Misaf\Newsletter\Traits;

use Exception;
use Misaf\Newsletter\Services\NewsletterPostService;
use Misaf\Newsletter\Services\NewsletterService;
use Misaf\Newsletter\Services\NewsletterSubscriberService;

trait HasCacheClearing
{
    /**
     * Clear cache for a specific service and optionally related services
     *
     * @param string $service
     * @param bool $clearRelated
     * @param NewsletterService $newsletterService
     * @param NewsletterPostService $postService
     * @param NewsletterSubscriberService $subscriberService
     * @return void
     */
    protected function clearNewsletterCache(
        string $service,
        bool $clearRelated = false,
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

    /**
     * Clear cache for a specific service
     *
     * @param string $service
     * @param NewsletterService $newsletterService
     * @param NewsletterPostService $postService
     * @param NewsletterSubscriberService $subscriberService
     * @return void
     */
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

    /**
     * Clear related caches for a specific service
     *
     * @param string $service
     * @param NewsletterService $newsletterService
     * @param NewsletterPostService $postService
     * @param NewsletterSubscriberService $subscriberService
     * @return void
     */
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

    /**
     * Clear all newsletter-related caches
     *
     * @param NewsletterService $newsletterService
     * @param NewsletterPostService $postService
     * @param NewsletterSubscriberService $subscriberService
     * @return void
     */
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
