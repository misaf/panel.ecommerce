<?php

declare(strict_types=1);

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function (): void {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('horizon:snapshot')->everyFiveMinutes();

Schedule::command('vendra-subscription:enforce-subscriptions')->daily();

Schedule::command('vendra-subscription:recover-payments')
    ->everyFiveMinutes()
    ->withoutOverlapping()
    ->onOneServer();

Schedule::command('vendra-subscription:report-payment-backlog')
    ->everyFifteenMinutes()
    ->withoutOverlapping()
    ->onOneServer();
