<?php

declare(strict_types=1);

namespace Misaf\VendraNewsletter\Filament\Clusters\Resources\NewsletterSubscribers\Actions;

use App\Forms\Components\WysiwygEditor;
use Filament\Actions\BulkAction;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Wizard\Step;
use Illuminate\Database\Eloquent\Collection;
use Misaf\VendraNewsletter\Jobs\SendAdMailerJob;
use Misaf\VendraNewsletter\Models\NewsletterSubscriber;

final class SendBulkEmailAction extends BulkAction
{
    public static function getDefaultName(): ?string
    {
        return 'sendBulkEmail';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label('ارسال ایمیل')
            ->icon('heroicon-s-envelope')
            ->deselectRecordsAfterCompletion()
            ->slideOver()
            ->steps([
                Step::make('content')
                    ->label(__('vendra-newsletter::actions.content'))
                    ->description(__('vendra-newsletter::actions.content_title_and_text'))
                    ->schema([
                        TextInput::make('subject')
                            ->label(__('vendra-newsletter::actions.subject'))
                            ->required(),
                        WysiwygEditor::make('description'),
                    ]),
            ])
            ->action(function (array $data, Collection $records): void {
                $records->each(
                    fn(NewsletterSubscriber $record) => SendAdMailerJob::dispatchAfterResponse(
                        email: $record->email,
                        subject: $data['subject'],
                        description: $data['description'],
                    )->onQueue('bulk-email')
                );
            });
    }
}
