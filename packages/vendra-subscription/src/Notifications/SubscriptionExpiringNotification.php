<?php

declare(strict_types=1);

namespace Misaf\VendraSubscription\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Misaf\VendraSubscription\Models\Subscription;

final class SubscriptionExpiringNotification extends Notification
{
    public function __construct(private readonly Subscription $subscription) {}

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $endsAt = $this->subscription->ends_at?->toFormattedDateString() ?? 'soon';

        return (new MailMessage())
            ->subject('Your subscription is expiring soon')
            ->line("Your subscription expires on {$endsAt}.")
            ->line('Renew now to keep your websites online.');
    }
}
