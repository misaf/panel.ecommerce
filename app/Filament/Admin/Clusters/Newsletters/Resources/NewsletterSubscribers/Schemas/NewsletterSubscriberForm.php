<?php

declare(strict_types=1);

namespace App\Filament\Admin\Clusters\Newsletters\Resources\NewsletterSubscribers\Schemas;

use Filament\Forms\Components\SpatieTagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Livewire;
use Filament\Schemas\Schema;
use Misaf\User\Rules\EmailValidation;

final class NewsletterSubscriberForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('email')
                    ->afterStateUpdated(fn(Livewire $livewire) => $livewire->validateOnly("data.email"))
                    ->autofocus()
                    ->columnSpan(['lg' => 2])
                    ->email()
                    ->extraAttributes(['dir' => 'ltr'])
                    ->label(__('newsletter::attributes.email'))
                    ->live(onBlur: true)
                    ->maxLength(255)
                    ->required()
                    ->rules(['bail', 'email:rfc,strict,spoof,filter,filter_unicode', new EmailValidation(app()->isProduction())]),

                SpatieTagsInput::make('tags')
                    ->label(__('tag::navigation.tag'))
                    ->columnSpan(['lg' => 2]),
            ]);
    }
}
