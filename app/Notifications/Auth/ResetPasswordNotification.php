<?php

declare(strict_types=1);

namespace App\Notifications\Auth;

use Filament\Auth\Notifications\ResetPassword;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

final class ResetPasswordNotification extends ResetPassword implements ShouldQueue
{
    use Queueable;

    public function __construct()
    {
        $this->onQueue('transactional-email');
    }
    /**
     * @param  mixed  $notifiable
     * @return MailMessage
     */
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage())
            ->subject(__('mail.reset_password.subject'))
            ->line(__('mail.reset_password.greeting', ['user' => $notifiable->username]))
            ->line(__('mail.reset_password.line'))
            ->action(__('mail.reset_password.action'), $this->url)
            ->line(__('mail.reset_password.expire', [
                'count' => config('auth.passwords.' . config('auth.defaults.passwords') . '.expire'),
            ]))
            ->line(__('mail.reset_password.no_action'));
    }
}
