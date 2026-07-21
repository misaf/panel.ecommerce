<?php

declare(strict_types=1);

namespace Misaf\VendraSubscription\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

final class WebsitesSuspendedNotification extends Notification
{
    public function __construct(private readonly int $suspendedCount) {}

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage())
            ->subject('Your websites have been suspended')
            ->line("{$this->suspendedCount} website(s) were suspended because your subscription lapsed.")
            ->line('Renew your subscription to bring them back online.');
    }
}
