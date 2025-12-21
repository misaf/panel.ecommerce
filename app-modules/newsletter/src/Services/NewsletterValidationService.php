<?php

declare(strict_types=1);

namespace Misaf\Newsletter\Services;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Misaf\Newsletter\Models\Newsletter;
use Misaf\Newsletter\Models\NewsletterPost;
use Misaf\Newsletter\Models\NewsletterSendHistory;
use Misaf\Newsletter\Models\NewsletterSubscriber;

final class NewsletterValidationService
{
    /**
     * Validate newsletters
     *
     * @param Collection<int, Newsletter> $newsletters
     * @throws ValidationException
     */
    public function validateNewsletters(Collection $newsletters): void
    {
        $validator = Validator::make(
            ['newsletters' => $newsletters],
            [
                'newsletters' => [
                    'required',
                    'array',
                    'min:1',
                    function ($attribute, $value, $fail): void {
                        if ( ! $value instanceof Newsletter) {
                            $fail('Each item must be a Newsletter instance.');
                            return;
                        }

                        if ( ! $value->isEnabled()) {
                            $fail('Newsletter is disabled. Newsletter ID: ' . $value->id);
                        }
                    },
                ],
            ],
            [
                'newsletters.required' => 'Newsletters are required.',
                'newsletters.array'    => 'Newsletters must be an array.',
                'newsletters.min'      => 'At least one newsletter is required.',
            ]
        );

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }

    /**
     * Validate newsletter subscribers
     *
     * @param Collection<int, NewsletterSubscriber> $newsletterSubscribers
     * @throws ValidationException
     */
    public function validateNewsletterSubscribers(Collection $newsletterSubscribers): void
    {
        $validator = Validator::make(
            ['newsletter_subscribers' => $newsletterSubscribers],
            [
                'newsletter_subscribers'   => ['required', 'array', 'min:1'],
                'newsletter_subscribers.*' => ['required', function ($attribute, $value, $fail): void {
                    if ( ! $value instanceof NewsletterSubscriber) {
                        $fail('Each item must be a NewsletterSubscriber instance.');
                        return;
                    }
                }],
            ],
            [
                'newsletter_subscribers.required' => 'Newsletter subscribers collection is required.',
                'newsletter_subscribers.array'    => 'Newsletter subscribers must be an array.',
                'newsletter_subscribers.min'      => 'At least one newsletter subscriber is required.',
            ]
        );

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }

    /**
     * Validate newsletter posts
     *
     * @param Collection<int, NewsletterPost> $newsletterPosts
     * @throws ValidationException
     */
    public function validateNewsletterPosts(Collection $newsletterPosts): void
    {
        $validator = Validator::make(
            ['newsletter_posts' => $newsletterPosts],
            [
                'newsletter_posts'   => ['required', 'array', 'min:1'],
                'newsletter_posts.*' => [
                    'required',
                    function ($attribute, $value, $fail): void {
                        if ( ! $value instanceof NewsletterPost) {
                            $fail('Each item must be a NewsletterPost instance.');
                            return;
                        }

                        if ( ! $value->isReady()) {
                            $fail('Newsletter post is not ready. Newsletter ID: ' . $value->newsletter_id);
                        }
                    },
                ],
            ],
            [
                'newsletter_posts.required' => 'Newsletter posts are required.',
                'newsletter_posts.array'    => 'Newsletter posts must be an array.',
                'newsletter_posts.min'      => 'At least one newsletter post is required.',
            ]
        );

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }

    /**
     * Validate send histories are valid and not already sending
     *
     * @param Collection<int, NewsletterSendHistory> $sendHistories
     * @throws ValidationException
     */
    public function validateSendHistories(Collection $sendHistories): void
    {
        $validator = Validator::make(
            ['send_history' => $sendHistories],
            [
                'send_history' => [
                    'required',
                    'array',
                    'min:1',
                    function ($attribute, $value, $fail): void {
                        if ( ! $value instanceof NewsletterSendHistory) {
                            $fail('Each item must be a NewsletterSendHistory instance.');
                            return;
                        }

                        if ($value->isSending()) {
                            $fail('Newsletter send history is already sending. Newsletter ID: ' . $value->newsletter_id);
                        }
                    },
                ],
            ],
            [
                'send_history.required' => 'Send history is required.',
                'send_history.array'    => 'Send history must be an array.',
                'send_history.min'      => 'At least one send history is required.',
            ]
        );

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }
}
