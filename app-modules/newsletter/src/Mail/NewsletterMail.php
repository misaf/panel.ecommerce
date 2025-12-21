<?php

declare(strict_types=1);

namespace Misaf\Newsletter\Mail;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Support\Facades\Config;
use Misaf\Newsletter\Models\Newsletter;
use Misaf\Newsletter\Models\NewsletterPost;
use Misaf\Newsletter\Models\NewsletterSubscriber;

final class NewsletterMail extends Mailable
{
    public function __construct(
        public Newsletter $newsletter,
        public NewsletterSubscriber $subscriber,
        public Collection|NewsletterPost $posts,
    ) {}

    public function envelope(): Envelope
    {
        $subject = $this->newsletter->name;
        $subjectString = is_string($subject) ? $subject : '';

        return new Envelope(
            subject: $subjectString,
            to: [$this->subscriber->email],
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'newsletter::mail.newsletter',
            with: [
                'newsletter'              => $this->newsletter,
                'posts'                   => $this->posts,
                'unsubscribeSpecificUrl'  => $this->generateUnsubscribeSpecificUrl(),
                'unsubscribeAllUrl'       => $this->generateUnsubscribeAllUrl(),
            ],
        );
    }

    private function generateUnsubscribeSpecificUrl(): string
    {
        return route('newsletter.unsubscribe.specific', [
            'email'           => $this->subscriber->email,
            'token'           => $this->generateUnsubscribeToken(),
            'newsletter_slug' => $this->newsletter->slug,
        ]);
    }

    private function generateUnsubscribeAllUrl(): string
    {
        return route('newsletter.unsubscribe', [
            'email' => $this->subscriber->email,
            'token' => $this->generateUnsubscribeToken(),
        ]);
    }

    private function generateUnsubscribeToken(): string
    {
        $appKey = Config::string('app.key');

        return hash_hmac('sha256', $this->subscriber->email, $appKey);
    }
}
