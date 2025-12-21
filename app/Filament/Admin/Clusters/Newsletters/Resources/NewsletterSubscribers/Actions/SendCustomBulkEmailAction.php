<?php

declare(strict_types=1);

namespace App\Filament\Admin\Clusters\Newsletters\Resources\NewsletterSubscribers\Actions;

use App\Jobs\SendCustomBulkAdMailerJob;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Support\Enums\Size;

final class SendCustomBulkEmailAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'sendCustomBulkEmail';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->color('gray')
            ->icon('heroicon-o-envelope')
            ->label(__('ارسال تبلیغات'))
            ->size(Size::Small)
            ->steps([
                Step::make('content')
                    ->label(__('محتوا'))
                    ->description(__('عنوان و متن محتوا'))
                    ->schema([
                        TextInput::make('subject')
                            ->label(__('عنوان'))
                            ->required(),

                        TextInput::make('description'),

                        Fieldset::make('limitations')
                            ->label(__('اعمال محدودیت'))
                            ->schema([
                                TextInput::make('count')
                                    ->label(__('تعداد'))
                                    ->numeric()
                                    ->columnSpanFull()
                                    ->extraAttributes(['dir' => 'ltr']),

                                TextInput::make('from_record')
                                    ->label(__('از رکورد'))
                                    ->numeric()
                                    ->extraAttributes(['dir' => 'ltr']),

                                TextInput::make('to_record')
                                    ->label(__('تا رکورد'))
                                    ->numeric()
                                    ->extraAttributes(['dir' => 'ltr']),
                            ]),
                    ]),
            ])
            ->action(
                fn(array $data)
                => SendCustomBulkAdMailerJob::dispatch(
                    subject: $data['subject'],
                    description: $data['description'],
                    count: $data['count'],
                    fromRecord: $data['from_record'],
                    toRecord: $data['to_record'],
                )
            )
            ->slideOver();
    }
}
