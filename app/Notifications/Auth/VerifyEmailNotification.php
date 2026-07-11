<?php

declare(strict_types=1);

namespace App\Notifications\Auth;

use Illuminate\Auth\Notifications\VerifyEmail as LaravelVerifyEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use InvalidArgumentException;
use Misaf\VendraUser\Models\User;

final class VerifyEmailNotification extends LaravelVerifyEmail implements ShouldQueue
{
    use Queueable;

    public function __construct()
    {
        $this->onQueue('transactional-email');
    }

    public function toMail(mixed $notifiable): MailMessage
    {
        if ( ! $notifiable instanceof User) {
            throw new InvalidArgumentException(sprintf('Expected %s, got %s.', User::class, get_debug_type($notifiable)));
        }

        $verificationUrl = $this->verificationUrl($notifiable);
        $appName = config('app.name');

        return (new MailMessage())
            ->subject(__('mail.verify_email.subject'))
            ->greeting(__('mail.verify_email.greeting', ['user' => $notifiable->username]))
            ->line(__('mail.verify_email.line'))
            ->action(__('mail.verify_email.action'), $verificationUrl)
            ->line(__('mail.verify_email.no_action'))
            ->salutation(__('mail.verify_email.salutation') . "\n" . (is_string($appName) ? $appName : ''));
    }
}
