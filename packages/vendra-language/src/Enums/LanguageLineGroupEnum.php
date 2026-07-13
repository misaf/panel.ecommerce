<?php

declare(strict_types=1);

namespace Misaf\VendraLanguage\Enums;

use Filament\Support\Contracts\HasLabel;
use Illuminate\Support\Str;

enum LanguageLineGroupEnum: string implements HasLabel
{
    case Authentication = 'authentication';
    case Billing = 'billing';
    case Content = 'content';
    case Core = 'core';
    case Dashboard = 'dashboard';
    case Emails = 'emails';
    case Modules = 'modules';
    case Navigation = 'navigation';
    case Notifications = 'notifications';
    case Settings = 'settings';
    case Users = 'users';
    case Validation = 'validation';
    case Website = 'website';

    public function getLabel(): string
    {
        return Str::headline($this->value);
    }
}
