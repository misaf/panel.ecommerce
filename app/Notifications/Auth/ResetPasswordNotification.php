<?php

declare(strict_types=1);

namespace App\Notifications\Auth;

use Filament\Auth\Notifications\ResetPassword;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use InvalidArgumentException;
use Misaf\VendraUser\Models\User;

final class ResetPasswordNotification extends ResetPassword implements ShouldQueue
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

        $guard = config('auth.defaults.passwords');
        $expire = config('auth.passwords.' . (is_string($guard) ? $guard : '') . '.expire');

        return (new MailMessage())
            ->subject(__('mail.reset_password.subject'))
            ->line(__('mail.reset_password.greeting', ['user' => $notifiable->username]))
            ->line(__('mail.reset_password.line'))
            ->action(__('mail.reset_password.action'), $this->url)
            ->line(__('mail.reset_password.expire', [
                'count' => is_int($expire) ? $expire : 0,
            ]))
            ->line(__('mail.reset_password.no_action'));
    }
}
