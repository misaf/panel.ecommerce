<?php

declare(strict_types=1);

namespace App\Filament\Admin\Clusters\Newsletters\Resources\NewsletterSendHistorySubscribers\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Livewire;
use Filament\Schemas\Schema;
use Misaf\User\Rules\EmailValidation;

final class NewsletterSendHistorySubscriberForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('email')
                    ->required()
                    ->email()
                    ->maxLength(255)
                    ->autofocus()
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn(Livewire $livewire) => $livewire->validateOnly("data.email"))
                    ->rules(['bail', 'email:rfc,strict,spoof,filter,filter_unicode', new EmailValidation(app()->isProduction())])
                    ->extraAttributes(['dir' => 'ltr'])
                    ->label(__('newsletter::attributes.email'))
                    ->columnSpan(['lg' => 2]),
            ]);
    }
}
