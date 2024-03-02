<?php

declare(strict_types=1);

namespace App\Filament\User\Resources\UserResource\Pages\Auth;

use Filament\Forms;
use Filament\Pages\Auth\Register as BaseRegister;

final class Register extends BaseRegister
{
    // protected static string $view = 'filament.user.resources.user-resource.pages.auth.register';

    protected function getForms(): array
    {
        return [
            'form' => $this->form(
                $this->makeForm()
                    ->schema([
                        Forms\Components\TextInput::make('username')
                            ->label(__('form.username'))
                            ->required()
                            ->maxLength(255)
                            ->autofocus(),
                        $this->getEmailFormComponent(),
                        $this->getPasswordFormComponent(),
                        $this->getPasswordConfirmationFormComponent(),
                    ])
                    ->statePath('data'),
            ),
        ];
    }
}
