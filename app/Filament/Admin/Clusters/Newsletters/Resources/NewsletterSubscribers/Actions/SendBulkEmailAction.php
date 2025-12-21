<?php

declare(strict_types=1);

namespace App\Filament\Admin\Clusters\Newsletters\Resources\NewsletterSubscribers\Actions;

use App\Forms\Components\WysiwygEditor;
use App\Jobs\SendAdMailerJob;
use Filament\Actions\BulkAction;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Wizard\Step;
use Illuminate\Database\Eloquent\Collection;
use Misaf\Newsletter\Models\NewsletterSubscriber;

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
                    ->label(__('محتوا'))
                    ->description(__('عنوان و متن محتوا'))
                    ->schema([
                        TextInput::make('subject')
                            ->label(__('عنوان'))
                            ->required(),
                        WysiwygEditor::make('description'),
                    ]),
            ])
            ->action(function (array $data, Collection $records): void {
                $records->each(
                    fn(NewsletterSubscriber $record)
                    => SendAdMailerJob::dispatchAfterResponse(
                        email: $record->email,
                        subject: $data['subject'],
                        description: $data['description'],
                    )->onQueue('bulk-email')
                );
            });
    }
}
