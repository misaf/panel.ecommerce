<?php

declare(strict_types=1);

namespace App\Filament\Admin\Clusters\Newsletters\Resources\Newsletters\Pages;

use App\Filament\Admin\Clusters\Newsletters\Resources\Newsletters\NewsletterResource;
use Exception;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Exceptions\Halt;
use LaraZeus\SpatieTranslatable\Actions\LocaleSwitcher;
use LaraZeus\SpatieTranslatable\Resources\Pages\EditRecord\Concerns\Translatable;
use Misaf\Newsletter\Actions\Newsletter\RetryFailedSendsAction;
use Misaf\Newsletter\Actions\Newsletter\SendAction;
use Misaf\Newsletter\Enums\NewsletterStatusEnum;
use Misaf\Newsletter\Models\Newsletter;

final class EditNewsletter extends EditRecord
{
    use Translatable;

    protected static string $resource = NewsletterResource::class;

    public function getBreadcrumb(): string
    {
        return self::$breadcrumb ?? __('filament-panels::resources/pages/edit-record.breadcrumb') . ' ' . __('newsletter::navigation.newsletter');
    }

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),

            DeleteAction::make(),

            LocaleSwitcher::make(),
            // Action::make('retryFailed')
            //     ->label(__('newsletter::actions.retry_failed_only'))
            //     ->color('danger')
            //     // ->visible(fn(Newsletter $record) => NewsletterStatusEnum::FAILED === $record->status)
            //     ->action(function (): void {
            //         /** @var Newsletter $newsletter */
            //         $newsletter = $this->record;

            //         $post = $newsletter->newsletterPosts()
            //             ->latest()
            //             ->first();

            //         if (null === $post) {
            //             Notification::make()
            //                 ->title(__('newsletter::notifications.retry.no_post.title'))
            //                 ->body(__('newsletter::notifications.retry.no_post.body'))
            //                 ->warning()
            //                 ->send();
            //             return;
            //         }

            //         try {
            //             $count = app(RetryFailedSendsAction::class)->execute($newsletter);

            //             if (0 === $count) {
            //                 Notification::make()
            //                     ->title(__('newsletter::notifications.retry.failed.none'))
            //                     ->warning()
            //                     ->send();
            //                 return;
            //             }

            //             Notification::make()
            //                 ->title(__('newsletter::notifications.retry.failed.requeued', ['count' => $count]))
            //                 ->success()
            //                 ->send();
            //         } catch (Exception $e) {
            //             /** @todo Avoid displaying sensitive newsletter identifiers (like slug) in notifications */
            //             Notification::make()
            //                 ->title(__('newsletter::notifications.retry.failed.error.title'))
            //                 ->body(__('newsletter::notifications.retry.failed.error.body', ['error' => $e->getMessage()]))
            //                 ->danger()
            //                 ->send();
            //         }
            //     }),
        ];
    }

    /**
     * @return void
     */
    protected function afterSave(): void
    {
        // $newsletter = $this->record;

        // if ( ! $this->shouldSendNewsletter($newsletter)) {
        //     return;
        // }

        // $this->sendNewsletter($newsletter);
    }

    /**
     * @param Newsletter $newsletter
     * @return bool
     */
    private function shouldSendNewsletter(Newsletter $newsletter): bool
    {
        // if ( ! $newsletter->wasChanged('status')) {
        //     return false;
        // }

        // if (NewsletterStatusEnum::READY !== $newsletter->status) {
        //     return false;
        // }

        // return null === $newsletter->scheduled_at;

        return true;
    }

    /**
     * @param Newsletter $newsletter
     * @return void
     */
    private function sendNewsletter(Newsletter $newsletter): void
    {
        // try {
        //     $sendAction = app(SendAction::class);
        //     $result = $sendAction->execute($newsletter);
        // } catch (Exception $e) {
        //     Notification::make()
        //         ->title(__('newsletter::notifications.send.failed.title', ['newsletter' => $newsletter->name]))
        //         ->body(__('newsletter::notifications.send.failed.body', ['error' => $e->getMessage()]))
        //         ->danger()
        //         ->send();

        //     // Use Halt exception to rollback the transaction while showing the notification
        //     throw new Halt($e->getMessage());
        // }
    }
}
