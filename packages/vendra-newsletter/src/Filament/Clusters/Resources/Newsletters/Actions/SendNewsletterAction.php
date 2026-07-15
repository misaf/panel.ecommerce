<?php

declare(strict_types=1);

namespace Misaf\VendraNewsletter\Filament\Clusters\Resources\Newsletters\Actions;

use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Gate;
use Misaf\VendraNewsletter\Actions\SendNewsletter;
use Misaf\VendraNewsletter\Enums\NewsletterStatusEnum;
use Misaf\VendraNewsletter\Models\Newsletter;

final class SendNewsletterAction
{
    public static function make(): Action
    {
        return Action::make('send')
            ->label(__('vendra-newsletter::actions.send'))
            ->icon(Heroicon::OutlinedPaperAirplane)
            ->color('primary')
            ->requiresConfirmation()
            ->modalHeading(__('vendra-newsletter::actions.send'))
            ->modalDescription(__('vendra-newsletter::actions.send_confirmation'))
            ->authorize(fn(Newsletter $record): bool => Gate::allows('send', $record))
            ->visible(fn(Newsletter $record): bool => NewsletterStatusEnum::Sent !== $record->status)
            ->action(function (Newsletter $record): void {
                $recipients = app(SendNewsletter::class)->execute($record);

                Notification::make()
                    ->title(__('vendra-newsletter::actions.send_success', ['count' => $recipients]))
                    ->success()
                    ->send();
            });
    }
}
