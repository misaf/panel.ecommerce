<?php

declare(strict_types=1);

namespace Misaf\AuthifyLog\Enums;

use Filament\Support\Contracts\HasLabel;

enum AuthifyLogActionEnum: int implements HasLabel
{
    case Authenticated = 1;
    case AuthenticationAttempt = 2;
    case CurrentDeviceLogout = 3;
    case FailedLogin = 4;
    case Lockout = 5;
    case OtherDeviceLogout = 6;
    case PasswordReset = 7;
    case RegisteredUser = 8;
    case SuccessfulLogin = 9;
    case SuccessfulLogout = 10;
    case Validated = 11;
    case Verified = 12;

    /**
     * @return array<int, int>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Authenticated         => __('authify-log::action-enum.authenticated'),
            self::AuthenticationAttempt => __('authify-log::action-enum.authentication_attempt'),
            self::CurrentDeviceLogout   => __('authify-log::action-enum.current_device_logout'),
            self::FailedLogin           => __('authify-log::action-enum.failed_login'),
            self::Lockout               => __('authify-log::action-enum.lockout'),
            self::OtherDeviceLogout     => __('authify-log::action-enum.other_device_logout'),
            self::PasswordReset         => __('authify-log::action-enum.password_reset'),
            self::RegisteredUser        => __('authify-log::action-enum.registered_user'),
            self::SuccessfulLogin       => __('authify-log::action-enum.successful_login'),
            self::SuccessfulLogout      => __('authify-log::action-enum.successful_logout'),
            self::Validated             => __('authify-log::action-enum.validated'),
            self::Verified              => __('authify-log::action-enum.verified'),
        };
    }
}
