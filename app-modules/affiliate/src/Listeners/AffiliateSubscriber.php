<?php

declare(strict_types=1);

namespace Misaf\Affiliate\Listeners;

use Illuminate\Contracts\Queue\ShouldQueueAfterCommit;
use Illuminate\Events\Dispatcher;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;
use Misaf\User\Models\User;

final class AffiliateSubscriber implements ShouldQueueAfterCommit
{
    use InteractsWithQueue;

    public function userCreated(User $user): void
    {
        DB::transaction(function () use ($user): void {
            $user->affiliates()
                ->create([
                    'commission_percent' => 20,
                    'status'             => true,
                ]);

            // $user->assignRole('reseller');
        }, 5);
    }

    /**
     * @return array<string, string>
     */
    public function subscribe(Dispatcher $events): array
    {
        return [
            'eloquent.created: ' . User::class => 'userCreated',
        ];
    }
}
