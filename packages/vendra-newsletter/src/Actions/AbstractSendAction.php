<?php

declare(strict_types=1);

namespace Misaf\VendraNewsletter\Actions;

use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Misaf\VendraNewsletter\Exceptions\NewsletterSendException;
use Misaf\VendraNewsletter\Services\NewsletterSendService;
use Misaf\VendraNewsletter\Services\NewsletterValidationService;
use Misaf\VendraNewsletter\ValueObjects\NewsletterSendContext;
use Misaf\VendraNewsletter\ValueObjects\NewsletterSendResult;

abstract class AbstractSendAction
{
    public function __construct(
        protected NewsletterSendService $sendService,
        protected NewsletterValidationService $validationService
    ) {}

    /**
     * Execute the send action
     *
     * @return array<string, mixed>
     *
     * @throws NewsletterSendException
     */
    public function execute(Collection $entities, bool $isDryRun = false): array
    {
        try {
            // Perform entity-specific validation first
            $this->validateEntities($entities);

            // Build context with optimized queries
            $context = $this->buildContext($entities, $isDryRun);

            // Validate all data
            $this->validate($context);

            if ($isDryRun) {
                return $this->handleDryRun($context);
            }

            // Execute within transaction for data consistency
            return DB::transaction(function () use ($context, $entities) {
                $this->dispatch($context, $entities);

                $this->logSuccess($context);

                return NewsletterSendResult::success(
                    $context->subscriberCount,
                    false
                )->toArray();
            });

        } catch (NewsletterSendException $e) {
            $this->logError($e, $entities->count(), $isDryRun);
            throw $e;
        } catch (Exception $e) {
            $this->logError($e, $entities->count(), $isDryRun);
            throw NewsletterSendException::validationFailed($e->getMessage());
        }
    }

    /**
     * Build the send context from entities
     */
    abstract protected function buildContext(Collection $entities, bool $isDryRun): NewsletterSendContext;

    /**
     * Dispatch jobs for sending
     *
     * @throws NewsletterSendException
     */
    abstract protected function dispatch(NewsletterSendContext $context, Collection $entities): void;

    abstract protected function getEntityType(): string;

    /**
     * Validate entities before processing
     *
     * @throws NewsletterSendException
     */
    protected function validateEntities(Collection $entities): void
    {
        // Override in child classes if entity-specific validation is needed
    }

    protected function validate(NewsletterSendContext $context): void
    {
        $this->validationService->validateNewsletters($context->newsletters);

        if ($context->subscribers->isEmpty()) {
            throw NewsletterSendException::noSubscribers();
        }
        $this->validationService->validateNewsletterSubscribers($context->subscribers);

        if ($context->posts->isEmpty()) {
            throw NewsletterSendException::noReadyPosts();
        }
        $this->validationService->validateNewsletterPosts($context->posts);

        // Additional validation can be added by child classes
        $this->performAdditionalValidation($context);
    }

    protected function performAdditionalValidation(NewsletterSendContext $context): void
    {
        // Override in child classes if needed
    }

    /**
     * Handle dry run execution
     *
     * @return array<string, mixed>
     */
    protected function handleDryRun(NewsletterSendContext $context): array
    {
        $this->logDryRun($context);

        return NewsletterSendResult::success(
            $context->subscriberCount,
            true
        )->toArray();
    }

    protected function logSuccess(NewsletterSendContext $context): void
    {
        Log::info("{$this->getEntityType()} sending completed", [
            'newsletter_count' => $context->newsletters->count(),
            'subscriber_count' => $context->subscriberCount,
            'post_count'       => $context->posts->count(),
            'is_dry_run'       => false,
        ]);
    }

    protected function logDryRun(NewsletterSendContext $context): void
    {
        Log::info("{$this->getEntityType()} dry run completed", [
            'newsletter_count' => $context->newsletters->count(),
            'subscriber_count' => $context->subscriberCount,
            'post_count'       => $context->posts->count(),
            'is_dry_run'       => true,
        ]);
    }

    protected function logError(Exception $e, int $entityCount, bool $isDryRun): void
    {
        Log::error("{$this->getEntityType()} sending failed", [
            'entity_count' => $entityCount,
            'error'        => $e->getMessage(),
            'trace'        => $e->getTraceAsString(),
            'is_dry_run'   => $isDryRun,
        ]);
    }

    /**
     * Dispatch a single job with error handling
     *
     * @throws NewsletterSendException
     */
    protected function dispatchJob(callable $jobCreator, array $logContext): void
    {
        try {
            $jobCreator();

            Log::debug("{$this->getEntityType()} job dispatched", $logContext);
        } catch (Exception $e) {
            Log::error("Failed to dispatch {$this->getEntityType()} job", array_merge($logContext, [
                'error' => $e->getMessage(),
            ]));

            throw NewsletterSendException::jobDispatchFailed(
                $this->getEntityType(),
                $logContext['id'] ?? 0,
                $e->getMessage()
            );
        }
    }
}
