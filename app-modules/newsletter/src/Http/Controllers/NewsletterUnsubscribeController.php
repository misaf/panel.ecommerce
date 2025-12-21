<?php

declare(strict_types=1);

namespace Misaf\Newsletter\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Config;
use Misaf\Newsletter\Actions\NewsletterSubscriber\UnsubscribeFromAllAction;
use Misaf\Newsletter\Http\Requests\UnsubscribeNewsletterRequest;

final class NewsletterUnsubscribeController
{
    private const ERROR_INVALID_TOKEN = 'Invalid unsubscribe token.';
    private const ERROR_SUBSCRIBER_NOT_FOUND = 'Subscriber not found.';
    private const SUCCESS_MESSAGE = "You've been successfully unsubscribed from all newsletters.";

    /**
     * @param UnsubscribeFromAllAction $action
     */
    public function __construct(
        private readonly UnsubscribeFromAllAction $action,
    ) {}

    /**
     * @param UnsubscribeNewsletterRequest $request
     * @return Response
     */
    public function __invoke(UnsubscribeNewsletterRequest $request): Response
    {
        $email = $request->validated('email');
        $token = $request->validated('token');

        if ( ! $this->isValidToken($email, $token)) {
            return $this->errorResponse(self::ERROR_INVALID_TOKEN, 400);
        }

        $success = $this->action->execute($email);

        if ( ! $success) {
            return $this->errorResponse(self::ERROR_SUBSCRIBER_NOT_FOUND, 404);
        }

        return $this->successResponse(self::SUCCESS_MESSAGE);
    }

    /**
     * @param string $email
     * @param string $token
     * @return bool
     */
    private function isValidToken(string $email, string $token): bool
    {
        $appKey = Config::string('app.key');
        $expectedToken = hash_hmac('sha256', $email, $appKey);

        return hash_equals($expectedToken, $token);
    }

    /**
     * @param string $message
     * @param int $status
     * @return Response
     */
    private function errorResponse(string $message, int $status): Response
    {
        return response($message, $status)
            ->header('Content-Type', 'text/plain');
    }

    /**
     * @param string $message
     * @return Response
     */
    private function successResponse(string $message): Response
    {
        return response($message, 200)
            ->header('Content-Type', 'text/plain');
    }
}
