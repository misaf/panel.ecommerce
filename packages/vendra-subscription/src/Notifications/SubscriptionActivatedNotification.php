<?php

declare(strict_types=1);

namespace Misaf\VendraSubscription\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Misaf\VendraSubscription\Models\Plan;

final class SubscriptionActivatedNotification extends Notification
{
    public function __construct(private readonly Plan $plan) {}

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
            ->subject('Your subscription is active')
            ->line("Your account is now subscribed to the {$this->plan->name} plan.")
            ->line("You can run up to {$this->plan->max_websites} website(s).");
    }
}
