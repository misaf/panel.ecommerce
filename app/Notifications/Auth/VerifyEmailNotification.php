<?php

declare(strict_types=1);

namespace App\Notifications\Auth;

use Illuminate\Auth\Notifications\VerifyEmail as LaravelVerifyEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

final class VerifyEmailNotification extends LaravelVerifyEmail implements ShouldQueue
{
    use Queueable;

    public function __construct()
    {
        $this->onQueue('transactional-email');
    }

    /**
     * @param mixed $notifiable
     * @return MailMessage
     */
    public function toMail($notifiable): MailMessage
    {
        $verificationUrl = $this->verificationUrl($notifiable);

        return (new MailMessage())
            ->subject(__('mail.verify_email.subject'))
            ->greeting(__('mail.verify_email.greeting', ['user' => $notifiable->username]))
            ->line(__('mail.verify_email.line'))
            ->action(__('mail.verify_email.action'), $verificationUrl)
            ->line(__('mail.verify_email.no_action'))
            ->salutation(__('mail.verify_email.salutation') . "\n" . config('app.name'));
    }
}
