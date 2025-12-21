<?php

declare(strict_types=1);

namespace Misaf\Newsletter\Actions;

use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Misaf\Newsletter\Exceptions\NewsletterSendException;
use Misaf\Newsletter\Services\NewsletterSendService;
use Misaf\Newsletter\Services\NewsletterValidationService;
use Misaf\Newsletter\ValueObjects\NewsletterSendContext;
use Misaf\Newsletter\ValueObjects\NewsletterSendResult;

abstract class AbstractSendAction
{
    public function __construct(
        protected NewsletterSendService $sendService,
        protected NewsletterValidationService $validationService
    ) {}

    /**
     * Execute the send action
     *
     * @param Collection $entities
     * @param bool $isDryRun
     * @return array<string, mixed>
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
     *
     * @param Collection $entities
     * @param bool $isDryRun
     * @return NewsletterSendContext
     */
    abstract protected function buildContext(Collection $entities, bool $isDryRun): NewsletterSendContext;

    /**
     * Dispatch jobs for sending
     *
     * @param NewsletterSendContext $context
     * @param Collection $entities
     * @throws NewsletterSendException
     */
    abstract protected function dispatch(NewsletterSendContext $context, Collection $entities): void;

    /**
     * Get the entity type name for logging
     *
     * @return string
     */
    abstract protected function getEntityType(): string;

    /**
     * Validate entities before processing
     *
     * @param Collection $entities
     * @throws NewsletterSendException
     */
    protected function validateEntities(Collection $entities): void
    {
        // Override in child classes if entity-specific validation is needed
    }

    /**
     * Validate the send context
     *
     * @param NewsletterSendContext $context
     * @throws NewsletterSendException
     */
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

    /**
     * Perform additional validation specific to the child class
     *
     * @param NewsletterSendContext $context
     * @throws NewsletterSendException
     */
    protected function performAdditionalValidation(NewsletterSendContext $context): void
    {
        // Override in child classes if needed
    }

    /**
     * Handle dry run execution
     *
     * @param NewsletterSendContext $context
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

    /**
     * Log successful operation
     *
     * @param NewsletterSendContext $context
     */
    protected function logSuccess(NewsletterSendContext $context): void
    {
        Log::info("{$this->getEntityType()} sending completed", [
            'newsletter_count' => $context->newsletters->count(),
            'subscriber_count' => $context->subscriberCount,
            'post_count'       => $context->posts->count(),
            'is_dry_run'       => false,
        ]);
    }

    /**
     * Log dry run operation
     *
     * @param NewsletterSendContext $context
     */
    protected function logDryRun(NewsletterSendContext $context): void
    {
        Log::info("{$this->getEntityType()} dry run completed", [
            'newsletter_count' => $context->newsletters->count(),
            'subscriber_count' => $context->subscriberCount,
            'post_count'       => $context->posts->count(),
            'is_dry_run'       => true,
        ]);
    }

    /**
     * Log error
     *
     * @param Exception $e
     * @param int $entityCount
     * @param bool $isDryRun
     */
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
     * @param callable $jobCreator
     * @param array $logContext
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
