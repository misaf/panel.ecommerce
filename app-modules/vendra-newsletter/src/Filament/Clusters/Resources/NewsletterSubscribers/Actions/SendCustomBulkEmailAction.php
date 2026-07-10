<?php

declare(strict_types=1);

namespace Misaf\VendraNewsletter\Filament\Clusters\Resources\NewsletterSubscribers\Actions;

use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Support\Enums\Size;
use Misaf\VendraNewsletter\Jobs\SendCustomBulkAdMailerJob;

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
            ->label(__('vendra-newsletter::actions.send_advertisement'))
            ->size(Size::Small)
            ->steps([
                Step::make('content')
                    ->label(__('vendra-newsletter::actions.content'))
                    ->description(__('vendra-newsletter::actions.content_title_and_text'))
                    ->schema([
                        TextInput::make('subject')
                            ->label(__('vendra-newsletter::actions.subject'))
                            ->required(),

                        TextInput::make('description'),

                        Fieldset::make('limitations')
                            ->label(__('vendra-newsletter::actions.apply_limit'))
                            ->schema([
                                TextInput::make('count')
                                    ->label(__('vendra-newsletter::actions.count'))
                                    ->numeric()
                                    ->columnSpanFull()
                                    ->extraAttributes(['dir' => 'ltr']),

                                TextInput::make('from_record')
                                    ->label(__('vendra-newsletter::actions.from_record'))
                                    ->numeric()
                                    ->extraAttributes(['dir' => 'ltr']),

                                TextInput::make('to_record')
                                    ->label(__('vendra-newsletter::actions.to_record'))
                                    ->numeric()
                                    ->extraAttributes(['dir' => 'ltr']),
                            ]),
                    ]),
            ])
            ->action(
                fn(array $data) => SendCustomBulkAdMailerJob::dispatch(
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
