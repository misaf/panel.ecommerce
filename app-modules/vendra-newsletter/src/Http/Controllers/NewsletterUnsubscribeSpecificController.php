<?php

declare(strict_types=1);

namespace Misaf\VendraNewsletter\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Config;
use Misaf\VendraNewsletter\Actions\NewsletterSubscriber\UnsubscribeFromNewsletterAction;
use Misaf\VendraNewsletter\Http\Requests\UnsubscribeSpecificNewsletterRequest;
use Misaf\VendraNewsletter\Models\Newsletter;
use Misaf\VendraNewsletter\Models\NewsletterSubscriber;

final class NewsletterUnsubscribeSpecificController
{
    private const ERROR_INVALID_TOKEN = 'Invalid unsubscribe token.';

    private const ERROR_SUBSCRIBER_NOT_FOUND = 'Subscriber not found.';

    private const ERROR_NEWSLETTER_NOT_FOUND = 'Newsletter not found.';

    private const SUCCESS_MESSAGE = "You've been successfully unsubscribed from this newsletter.";

    public function __construct(
        private readonly UnsubscribeFromNewsletterAction $action,
    ) {}

    public function __invoke(UnsubscribeSpecificNewsletterRequest $request): Response
    {
        $email = $request->string('email')->toString();
        $token = $request->string('token')->toString();
        $newsletterSlug = $request->string('newsletter_slug')->toString();

        if ( ! $this->isValidToken($email, $token)) {
            return $this->errorResponse(self::ERROR_INVALID_TOKEN, 400);
        }

        $subscriber = NewsletterSubscriber::where('email', $email)->first();
        if ( ! $subscriber) {
            return $this->errorResponse(self::ERROR_SUBSCRIBER_NOT_FOUND, 404);
        }

        $newsletter = Newsletter::whereJsonContainsLocales('slug', $this->getAvailableLocales(), $newsletterSlug)->first();
        if ( ! $newsletter) {
            return $this->errorResponse(self::ERROR_NEWSLETTER_NOT_FOUND, 404);
        }

        $success = $this->action->execute($subscriber, $newsletter);

        if ( ! $success) {
            return $this->errorResponse(self::ERROR_SUBSCRIBER_NOT_FOUND, 404);
        }

        return $this->successResponse(self::SUCCESS_MESSAGE);
    }

    private function isValidToken(string $email, string $token): bool
    {
        $appKey = Config::string('app.key');
        $expectedToken = hash_hmac('sha256', $email, $appKey);

        return hash_equals($expectedToken, $token);
    }

    private function errorResponse(string $message, int $status): Response
    {
        return response($message, $status)
            ->header('Content-Type', 'text/plain');
    }

    private function successResponse(string $message): Response
    {
        return response($message, 200)
            ->header('Content-Type', 'text/plain');
    }

    /**
     * @return array<string>
     */
    private function getAvailableLocales(): array
    {
        return array_values(array_unique(array_filter([
            app()->getLocale(),
            Config::string('app.fallback_locale', 'en'),
        ])));
    }
}
