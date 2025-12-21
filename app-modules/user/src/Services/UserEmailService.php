<?php

declare(strict_types=1);

namespace Misaf\User\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Misaf\User\Models\User;

final class UserEmailService
{
    /**
     * Get email domain for analytics
     *
     * @param User $user
     * @return string|null
     */
    public function getEmailDomain(User $user): ?string
    {
        if ( ! $user->email) {
            return null;
        }

        if ( ! Str::contains($user->email, '@')) {
            return null;
        }

        return Str::afterLast($user->email, '@');
    }

    /**
     * Check if email is verified
     *
     * @param User $user
     * @return bool
     */
    public function isEmailVerified(User $user): bool
    {
        return null !== $user->email_verified_at;
    }

    /**
     * Get email verification status with human-readable message
     *
     * @param User $user
     * @return string
     */
    public function getEmailVerificationStatus(User $user): string
    {
        if ($this->isEmailVerified($user)) {
            return 'Verified';
        }

        return 'Unverified';
    }

    /**
     * Get users by email domain
     *
     * @param string $domain
     * @return Collection
     */
    public function getUsersByDomain(string $domain): Collection
    {
        return User::where('email', 'like', "%@{$domain}")->get();
    }

    /**
     * Get email domain statistics
     *
     * @return array
     */
    public function getDomainStatistics(): array
    {
        return User::selectRaw('
                CASE 
                    WHEN email LIKE ? THEN SUBSTRING_INDEX(email, "@", -1)
                    ELSE NULL 
                END as domain, 
                COUNT(*) as count
            ', ['%@%'])
            ->groupBy('domain')
            ->having('domain', 'IS NOT', null)
            ->orderByDesc('count')
            ->get()
            ->toArray();
    }
}
